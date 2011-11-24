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
			->addWhere('groupe = '.$gid)
			->addOrderBy('lastname')
			->addOrderBy('firstname')
			->execute();
			foreach($unGroupe as $unEtudiant){
				$this->etudiants[] = $unEtudiant;
			}
		}
	}
	private function setEtudiantsParAlpha(){
		$this->etudiants = Doctrine_Core::getTable('Person')
			->createQuery()
			->addOrderBy('lastname')
			->addOrderBy('firstname')
			->execute();
	}
	public function executeParGroupe(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$formparams=$request->getParameter('sequencegroupe');

			$this->setSequences();
			if(isset($formparams) && array_key_exists('sequence',$formparams) && is_array($formparams['sequence'])){
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

			
			$this->setGroupes();
			if(isset($formparams) && array_key_exists('sequence',($formparams)) && is_array($formparams['sequence'])){
				$this->gids = $formparams['groupe'];
			} else {
				$this->gids= array();
				foreach($this->groupes as $group){
					$this->gids[] = $group->getId();
				}
			}
			$this->listGroups = '';
			foreach($this->gids as $gid){
				$this->listGroups .= $this->groupes[$gid];
			}
					
			$this->setEtudiantsParGroupe($this->gids);
			$uids=array();
			foreach($this->etudiants as $etudiant){
				$uids[]=$etudiant->getId();
			}
			$this->setPresencesWithUids($uids,$this->seqids);		
		}
	}

	public function executeEnterParGroupe(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){

			$postedPresences = $request->getParameter('presence');
//			print_r($postedPresences);
			$this->setGroupes();
			$this->setSequences();
			$this->gids = $request->getParameter('gids');
			$this->seqids = $request->getParameter('seqids');
			$this->listGroups = '';
			foreach($this->gids as $gid){
				$this->listGroups .= $this->groupes[$gid];
			}
			$this->listSeqs = '';
			foreach($this->seqids as $sid){
				$this->listSeqs .= $this->sequences[$sid].' ';
			}
			$this->setEtudiantsParGroupe($this->gids);
			$uids=array();
			foreach($this->etudiants as $etudiant){
				$uids[]=$etudiant->getId();
			}
			// Read presences in DB
			$this->setPresencesWithUids($uids,$this->seqids);
			// Modify db according to postedPresences
			$this->modifyPresences($postedPresences);
			// Reread from DB
			$this->setPresencesWithUids($uids,$this->seqids);
			
		}
		
		//$this->redirect('presence/index');
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
//		print_r($this->presences);	
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
//					echo 'should delete '.$uid.':'.$seqid;
					if(!array_key_exists($uid, $this->removed)){
						$this->removed[$uid]= array();
					}
					$this->removed[$uid][$seqid] = 'new';
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
	public function executeSaisieAlpha(sfWebRequest $request)
	{
		$this->setSequences();
		$this->setPresences();
		$this->setEtudiantsParAlpha();
	}
	public function executeEnterAlpha(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$postedPresences = $request->getParameter('presence');
			$this->setPresences();
			$this->modifyPresences($postedPresences);
			$this->setSequences();
			$this->setPresences();
			$this->setEtudiantsParAlpha();
		}
	}
	public function executeNew(sfWebRequest $request)
	{
		$this->form = new PresenceForm();
	}

	public function executeCreate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod(sfRequest::POST));

		$this->form = new PresenceForm();

		$this->processForm($request, $this->form);

		$this->setTemplate('new');
	}

	public function executeEdit(sfWebRequest $request)
	{
		$this->forward404Unless($presence = Doctrine_Core::getTable('Presence')->find(array($request->getParameter('id'))), sprintf('Object presence does not exist (%s).', $request->getParameter('id')));
		$this->form = new PresenceForm($presence);
	}

	public function executeUpdate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
		$this->forward404Unless($presence = Doctrine_Core::getTable('Presence')->find(array($request->getParameter('id'))), sprintf('Object presence does not exist (%s).', $request->getParameter('id')));
		$this->form = new PresenceForm($presence);

		$this->processForm($request, $this->form);

		$this->setTemplate('edit');
	}

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($presence = Doctrine_Core::getTable('Presence')->find(array($request->getParameter('id'))), sprintf('Object presence does not exist (%s).', $request->getParameter('id')));
		$presence->delete();

		$this->redirect('presence/index');
	}

	protected function processForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		if ($form->isValid())
		{
			$presence = $form->save();

			$this->redirect('presence/edit?id='.$presence->getId());
		}
	}
}
