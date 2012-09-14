<?php

/**
 * listpresence actions.
 *
 * @package    sf_sandbox
 * @subpackage listpresence
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class listpresenceActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
  		$this->moduleens = $this->getUser()->getAttribute('moduleens', array());
  		if($this->moduleens == null){
  			$this->forward('choicemodule','index');
  		}
		$this->setGroups($this->moduleens->getCid());
		$this->setSequences($this->moduleens->getId());

		$groupchoices = array();
		foreach($this->groups as $group){
			$groupchoices[$group->getId()] = $group->getName();
		} 
		$sequencechoices = array();
		foreach($this->sequences as $sequence){
			$sequencechoices[$sequence->getId()] = $sequence->getName();
		}
		$this->form = new SequenceGroupForm(array('group'=>$groupchoices, 'sequence'=>$sequencechoices));
	}
	private function setAttendances($mid){
		$attendances = Doctrine_Core::getTable('Attendance')
		->createQuery('a')
		->innerJoin('a.Sequence s')
		->addWhere('s.mid = ' . $mid)
		->addOrderBy('a.person_id')
		->addOrderBy('a.sequence_id')
		->execute();
		$this->attendances = array();
		foreach($attendances as $presence){
			$uid = (int) $presence->getPersonId();
			$seqid = (int) $presence->getSequenceId();
			if(!array_key_exists($uid,$this->attendances)){
				$this->attendances[$uid] = array();
			}
			if(!array_key_exists($seqid,$this->attendances[$uid])){
				$this->attendances[$uid][$seqid] = $presence;
			}
		}

		unset($attendances); 
	}
	private function setSequences($mid){
		// read the sequences and store them in an array indexed by keys
		$sequences = Doctrine_Core::getTable('Sequence')
		->createQuery('s')
		->addWhere('s.mid = ' . $mid)
		->addOrderBy('s.id')
		->execute();
		$this->sequences = array();
		foreach($sequences as $sequence){
			$this->sequences[$sequence->getId()] = $sequence;
		}
		unset($sequences);
	}
	private function setGroups($cid){
		// read the groupes
		$rawGroupes = Doctrine_Core::getTable('StudentGroup')
		->createQuery('g')
		->addWhere('g.cid = '. $cid)
		->execute();

		$this->groups = array();
		foreach($rawGroupes as $group){
			$this->groups[$group->getId()] = $group;
		}
		unset($rawGroupes);
	}
	public function executeDisplay(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$this->moduleens = $this->getUser()->getAttribute('moduleens', array());
			if($this->moduleens == null){
				$this->forward('choicemodule','index');
			}
			$this->setGroups($this->moduleens->getCid());
			$this->setSequences($this->moduleens->getId());
			
			$this->setAttendances($this->moduleens->getId());
			$formparams=$request->getParameter('sequencegroup');

			if(isset($formparams) && array_key_exists('sequence',$formparams) 
				&& is_array($formparams['sequence']) && count($formparams['sequence'])){
				$this->seqids = $formparams['sequence'];
			}else{
				$this->seqids= array();
				foreach($this->sequences as $seq){
					$this->seqids[] = $seq->getId();
				}
			}
			$this->listSeqs = '';
			foreach($this->seqids as $sid){
				$this->listSeqs .= $this->sequences[$sid].' ';
			}
			
			$this->listGroups = '';
			$this->gids = array();	
			if(isset($formparams) && array_key_exists('group',$formparams) 
				&& is_array($formparams['group']) && count($formparams['group'])){
				$this->gids = $formparams['group'];
				$this->etudiants = array();
				foreach($this->gids as $gid){
					$unGroupe = Doctrine_Core::getTable('Person')
					->createQuery()
					->addWhere('gid = '.$gid)
					->addOrderBy('lastname')
					->addOrderBy('firstname')
					->execute();
					foreach($unGroupe as $unEtudiant){
						$this->etudiants[] = $unEtudiant;
					}
					$this->listGroups .= $this->groups[$gid];
				}
			} else {
				$this->etudiants = Doctrine_Core::getTable('Person')
				->createQuery('p')
				->innerJoin('p.StudentGroup g')
				->addWhere('g.cid = '.$this->moduleens->getCid())
				->addOrderBy('lastname')
				->addOrderBy('firstname')
				->execute();
			}
		}
	}
}
