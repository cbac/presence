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

	private function getPresencesWithUids($mid,$uids,$seqids){
		$rawpresences = AttendanceTable::getAttendances($mid);
		$presences = array();
		foreach($rawpresences as $uid => $sequences){
			if(in_array($uid,$uids)){
				if(count($sequences)){
					foreach($sequences as $seqid => $presence){
						if(in_array($seqid,$seqids)){
							if(!array_key_exists($uid,$presences)){
								$presences[$uid] = array();
							}
							$presences[$uid][$seqid] = $presence;
						}
					}
				}						
			}
		}
		return $presences;
	}

	private function setEtudiantsParGroupe($gids){
		$this->etudiants = array();
		foreach($gids as $gid){
			$unGroupe = Doctrine_Core::getTable('Person')
			->createQuery('p')
			->addWhere('p.gid = '.$gid)
			->addOrderBy('p.lastname')
			->addOrderBy('p.firstname')
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
	private function setEtudiantsParAlpha($cid){
		$this->etudiants = Doctrine_Core::getTable('Person')
		->createQuery('p')
		->innerJoin('p.StudentGroup g')
		->addWhere('g.cid = '.$cid)
		->addOrderBy('lastname')
		->addOrderBy('firstname')
		->execute();
	}
	public function executeChoose(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$this->moduleens = $this->getUser()->getAttribute('moduleens', array());
			if($this->moduleens == null){
				$this->forward('choicemodule','index');
			}
			$this->groups = StudentGroupTable::getGroups($this->moduleens->getCid());
			$this->sequences = SequenceTable::getSequences($this->moduleens->getId());

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
			$this->getUser()->setAttribute('seqids', $this->seqids);

			$this->listSeqs = '';
			foreach($this->seqids as $sid){
				$this->listSeqs .= $this->sequences[$sid]->getName().' ';
			}
			$this->getUser()->setAttribute('listSeqs', $this->listSeqs);
				
			if(isset($formparams) && array_key_exists('group',($formparams))
					&& is_array($formparams['group']) && count($formparams['group'])){
				$this->gids = $formparams['group'];
				$this->setEtudiantsParGroupe($this->gids);
			} else {
				$this->setEtudiantsParAlpha($this->moduleens->getCid());
				$this->gids= array();
			}
			$this->setUids($this->etudiants);
			$this->getUser()->setAttribute('uids', $this->uids,array());
			$this->listGroups = '';
			foreach($this->gids as $gid){
				$this->listGroups .= $this->groups[$gid]->getName();
			}
			$this->getUser()->setAttribute('gids', $this->gids);
			$this->getUser()->setAttribute('listGroups', $this->listGroups);
				
			$this->presences = $this->getPresencesWithUids($this->moduleens->getId(),$this->uids,$this->seqids);
			$this->getUser()->setAttribute('presences', $this->presences);
		}
	}

	public function executeEnter(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$this->moduleens = $this->getUser()->getAttribute('moduleens', array());
			if($this->moduleens == null){
				$this->forward('choicemodule','index');
			}
			$this->groups = StudentGroupTable::getGroups($this->moduleens->getCid());
			$this->sequences = SequenceTable::getSequences($this->moduleens->getId());
				
			$postedPresences = $request->getParameter('presence');

			$this->presences = $this->getUser()->getAttribute('presences', array());
			$this->gids = $this->getUser()->getAttribute('gids', array());
			$this->listGroups = $this->getUser()->getAttribute('listGroups', array() );
			$this->seqids = $this->getUser()->getAttribute('seqids', array());
			$this->listSeqs = $this->getUser()->getAttribute('listSeqs', array() );
			$uids = $this->getUser()->getAttribute('uids',array());
				
			if(count($this->gids)){
				$this->setEtudiantsParGroupe($this->gids);
			}else{
				$this->setEtudiantsParAlpha($this->moduleens->getCid());
			}
			// Modify db according to postedPresences
			$this->modifyPresences($postedPresences);
			// Reread from DB
			$this->presences = $this->getPresencesWithUids($this->moduleens->getId(),$uids,$this->seqids);
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
						if( $this->sequences[$seqid]->getNote() === True){
							$pres = new Attendance();
							$pres->setSequenceId($seqid);
							$pres->setPersonId($uid);
							$pres->setNote($value);
							$pres->save();
						} else {
							if($value == 'on'){
								$pres = new Attendance();
								$pres->setSequenceId($seqid);
								$pres->setPersonId($uid);
								$pres->setNote(-1);
								$pres->save();
							}
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
					// can also be done without select
					$presences = Doctrine_Core::getTable('Attendance')
					->createQuery()
					->addWhere('person_id = '.$uid)
					->addWhere('sequence_id = '.$seqid)
					->execute();
					$pres = $presences[0];
					$pres->delete();
				} else {
					// check that the note is the same as in db
					if($value->getNote() != $posted[$uid][$seqid]){
						$value->setNote($posted[$uid][$seqid]);
						$value->save();
					}
				}
			}
		}
	}
}
