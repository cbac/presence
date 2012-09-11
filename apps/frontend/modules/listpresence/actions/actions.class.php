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
		$this->setGroups();
		$this->setSequences();
		$this->form = new SequenceGroupForm();
	}
	private function setAttendances(){
		$attendances = Doctrine_Core::getTable('Attendance')
		->createQuery()
		->addOrderBy('person_id')
		->addOrderBy('sequence_id')
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
	private function setSequences(){
		// read the sequences and store them in an array indexed by keys
		$sequences = Doctrine_Core::getTable('Sequence')
		->createQuery()
		->addWhere('mid = ' . $this->moduleens->getId())
		->addOrderBy('id')
		->execute();
		$this->sequences = array();
		foreach($sequences as $sequence){
			$this->sequences[$sequence->getId()] = $sequence;
		}
		unset($sequences);
	}
	private function setGroups(){
		// read the groupes
		$rawGroupes = Doctrine_Core::getTable('StudentGroup')
		->createQuery()
		->addWhere('cid = '. $this->moduleens->getCid())
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
			$this->setAttendances();
			$formparams=$request->getParameter('sequencegroup');

			$this->setSequences();
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
			
			$this->setGroups();
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
				->createQuery()
				->addOrderBy('lastname')
				->addOrderBy('firstname')
				->execute();
			}
		}
	}
}
