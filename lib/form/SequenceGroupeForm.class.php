<?php

/**
 * Sequence form base class.
 *
 * @method Sequence getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
class SequenceGroupeForm extends sfForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'groupe' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Groupe')),
      'sequence' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Sequence', 
      	'order_by'=>array('id','asc')))
    ));

    $this->setValidators(array(
      'groupe' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Groupe', 'required' => true)),
      'sequence' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Sequence', 'required' => true)),
    ));
    
    $this->widgetSchema->setNameFormat('sequencegroupe[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

 //   $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Planning';
  }

  public function configure()
  {
	$this->useFields(array('groupe','sequence'));

  }
  public function getName(){
  	return 'sequenceGroupe';
  }
}
