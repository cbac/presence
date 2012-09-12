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
class SequenceGroupForm extends sfForm
{
	private $choices;
	public function __construct($options){
		$this->choices = $options;
		parent::__construct();
	}
  public function setup()
  {
    $this->setWidgets(array(
      'group' => new sfWidgetFormChoice(array('multiple' => true, 'choices'=> $this->choices['group'])),
      'sequence' => new sfWidgetFormChoice(array('multiple' => true, 'choices'=>$this->choices['sequence']))
    ));

    $this->setValidators(array(
      'group' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'StudentGroup', 'required' => true)),
      'sequence' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Sequence', 'required' => true)),
    ));
    
    $this->widgetSchema->setNameFormat('sequencegroup[%s]');

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
	$this->useFields(array('group','sequence'));

  }
  public function getName(){
  	return 'sequenceGroup';
  }
}
