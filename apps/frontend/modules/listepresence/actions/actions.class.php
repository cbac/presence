<?php

/**
 * presence actions.
 *
 * @package    sf_sandbox
 * @subpackage presence
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class listepresenceActions extends sfActions
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
			$uid = (int) $presence->getPersonId();
			$seqid = (int) $presence->getSequenceId();
			if(!array_key_exists($uid,$this->presences)){
				$this->presences[$uid] = array();
			}
			if(!array_key_exists($seqid,$this->presences[$uid])){
				$this->presences[$uid][$seqid] = 1;
			}
		}

		unset($presences); 
	}
	private function setSequences(){
		// read the sequences and store them in an array indexed by keys
		$sequences = Doctrine_Core::getTable('Sequence')
		->createQuery()
		->execute();
		$this->sequences = array();
		foreach($sequences as $sequence){
			$this->sequences[$sequence->getId()] = $sequence;
		}
		unset($sequences);
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
		unset($rawGroupes);
	}
	public function executeParGroupe(sfWebRequest $request)
	{
		$this->setPresences();

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
					
			$this->etudiants = array();
			foreach($this->gids as $gid){
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
	}
	public function executeAlpha(sfWebRequest $request)
	{
		$this->setPresences();
			
		$this->setGroupes();

		$this->setSequences();
			
		$this->etudiants = Doctrine_Core::getTable('Person')
		->createQuery()
		->addOrderBy('lastname')
		->addOrderBy('firstname')
		->execute();
	}
}
