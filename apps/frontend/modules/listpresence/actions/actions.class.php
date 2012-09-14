<?php

/**
 * listpresence actions.
 *
 * @package    attendance
 * @subpackage listpresence
 * @author     Christian Bac
 */
class listpresenceActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
  		$this->moduleens = $this->getUser()->getAttribute('moduleens', array());
  		if($this->moduleens == null){
  			$this->forward('choicemodule','index');
  		}
 		$this->groups = StudentGroupTable::getGroups($this->moduleens->getCid());
		
		$this->sequences = SequenceTable::getSequences($this->moduleens->getId());

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


	public function executeDisplay(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$this->moduleens = $this->getUser()->getAttribute('moduleens', array());
			if($this->moduleens == null){
				$this->forward('choicemodule','index');
			}
			$this->groups = StudentGroupTable::getGroups($this->moduleens->getCid());
			
			$this->sequences = SequenceTable::getSequences($this->moduleens->getId());
			
			$this->attendances = AttendanceTable::getAttendances($this->moduleens->getId());
			
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
