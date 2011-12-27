<?php

/**
 * presence actions.
 *
 * @package    sf_sandbox
 * @subpackage presence
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class presenceActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->setGroupes();
		$this->setSequences();
		$this->form = new SequenceGroupeForm();
	}
	private function setPresences(){
		$presences = Doctrine_Core::getTable('Presence')
		->createQuery()
		->addOrderBy('person_id')
		->addOrderBy('sequence_id')
		->execute();
		$this->presences = array();
		foreach($presences as $presence){
			$uid = $presence->getPersonId();
			$seqid = $presence->getSequenceId();
			if(!array_key_exists($uid,$this->presences)){
				$this->presences[$uid] = array();
			}
			if(!array_key_exists($seqid,$this->presences[$uid])){
				$this->presences[$uid][$seqid] = 1;
			}
		}
		unset($presences); 
	}
	private function setPresencesWithUids($uids,$seqids){
		$presences = Doctrine_Core::getTable('Presence')
		->createQuery()
		->addOrderBy('person_id')
		->addOrderBy('sequence_id')
		->execute();
		$this->presences = array();
		foreach($presences as $presence){
			$uid = $presence->getPersonId();
			if(in_array($uid,$uids)){
				$seqid = $presence->getSequenceId();
				if(in_array($seqid,$seqids)){
					if(!array_key_exists($uid,$this->presences)){
						$this->presences[$uid] = array();
					}
					if(!array_key_exists($seqid,$this->presences[$uid])){
						$this->presences[$uid][$seqid] = 1;
					}
				}
			}
		}
		unset($presences); 
	}
	private function setSequences(){
		// read the sequences and store them in an array indexed by keys
		$sequences = Doctrine_Core::getTable('Sequence')
		->createQuery()
		->addOrderBy('id')
		->execute();
		$this->sequences = array();
		foreach($sequences as $sequence){
			$this->sequences[$sequence->getId()] = $sequence;
		}
		unset($sequences); // for GC
	}
	private function setGroupes(){
		// read the groupes
		$rawGroupes = Doctrine_Core::getTable('Groupe')
		->createQuery()
		->execute();

		$this->groupes = array();
		foreach($rawGroupes as $group){
			$this->groupes[$group->getId()] = $group;
		}
		unset($rawGroupes); // GC
	}
	private function setEtudiantsParGroupe($gids){
		$this->etudiants = array();
		foreach($gids as $gid){
			$unGroupe = Doctrine_Core::getTable('Person')
			->createQuery()
			->addWhere('gid = '.$gid)
			->addOrderBy('lastname')
			->addOrderBy('firstname')
			->execute();
			foreach($unGroupe as $unEtudiant){
				$this->etudiants[] = $unEtudiant;
			}
		}
	}
	private function setUids($etudiants){
		$this->uids = array();
		foreach($etudiants as $etudiant){
			$this->uids[] = $etudiant->getId();
		}
	}
	private function setEtudiantsParAlpha(){
		$this->etudiants = Doctrine_Core::getTable('Person')
			->createQuery()
			->addOrderBy('lastname')
			->addOrderBy('firstname')
			->execute();
	}
	public function executeChoose(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$formparams=$request->getParameter('sequencegroupe');

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
			$this->getUser()->setAttribute('seqids', $this->seqids);
				
			$this->listSeqs = '';
			foreach($this->seqids as $sid){
				$this->listSeqs .= $this->sequences[$sid].' ';
			}
			$this->getUser()->setAttribute('listSeqs', $this->listSeqs);
			
			$this->setGroupes();
			if(isset($formparams) && array_key_exists('groupe',($formparams)) 
				&& is_array($formparams['groupe']) && count($formparams['groupe'])){
				$this->gids = $formparams['groupe'];
				$this->setEtudiantsParGroupe($this->gids);
			} else {
				$this->setEtudiantsParAlpha();
				$this->gids= array();
			}
			$this->setUids($this->etudiants);
			$this->getUser()->setAttribute('uids', $this->uids,array());
			$this->listGroups = '';
			foreach($this->gids as $gid){
				$this->listGroups .= $this->groupes[$gid];
			}
			$this->getUser()->setAttribute('gids', $this->gids);
			$this->getUser()->setAttribute('listGroups', $this->listGroups);
			
			$this->setPresencesWithUids($this->uids,$this->seqids);
			$this->getUser()->setAttribute('presences', $this->presences);
		}
	}

	public function executeEnter(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$postedPresences = $request->getParameter('presence');
			$this->setSequences();
			$this->setGroupes();
			$this->presences = $this->getUser()->getAttribute('presences', array());
			$this->gids = $this->getUser()->getAttribute('gids', array());
			$this->listGroups = $this->getUser()->getAttribute('listGroups', array() );
			$this->seqids = $this->getUser()->getAttribute('seqids', array());
			$this->listSeqs = $this->getUser()->getAttribute('listSeqs', array() );
			$uids = $this->getUser()->getAttribute('uids',array());
			
			if(count($this->gids)){
				$this->setEtudiantsParGroupe($this->gids);
			}else{
				$this->setEtudiantsParAlpha();
			}
			// Modify db according to postedPresences
			$this->modifyPresences($postedPresences);
			// Reread from DB
			$this->setPresencesWithUids($uids,$this->seqids);
			$this->getUser()->setAttribute('presences', $this->presences);
		}
	}
	private function modifyPresences($posted){
		$this->added = array();
		$this->removed = array();
		// Fresh information that was not in db must be inserted
		if(is_array($posted)){
			foreach($posted as $uid => $values){
				foreach($values as $seqid => $value){
					if(!isset($this->presences[$uid]) || !isset($this->presences[$uid][$seqid])){
						if( $value== 'on'){
							$pres = new Presence();
							$pres->setSequenceId($seqid);
							$pres->setPersonId($uid);
							$pres->save();
						}
						if(!array_key_exists($uid, $this->added)){
							$this->added[$uid]= array();
						}
						$this->added[$uid][$seqid] = 'new';
					}
				}
			}
		}
		// Remove information in database that is not in post corresponding to an unchecked choice
		foreach($this->presences as $uid => $values){
			foreach($values as $seqid => $value){
				$delete = false;
				if(!is_array($posted) || count($posted) == 0 ) { //posted is empty
					$delete = true;
				} else {
					if(!isset($posted[$uid])){ // posted is not set for that user
						$delete = true;
					} else {
						if(!isset($posted[$uid][$seqid])){ // posted is not set for that user and sequence
							$delete = true;
						}
					}
				}
				if($delete == true){
					if(!array_key_exists($uid, $this->removed)){
						$this->removed[$uid]= array();
					}
					$this->removed[$uid][$seqid] = 'removed';
					//information is in database and not in post must be removed from db
					$presences = Doctrine_Core::getTable('Presence')
					->createQuery()
					->addWhere('person_id = '.$uid)
					->addWhere('sequence_id = '.$seqid)
					->execute();
					$pres = $presences[0];
					$pres->delete();
				}
			}
		}
	}
}
