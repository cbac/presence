<?php

/**
 * College and Module form choice class.
 *
 * @method Sequence getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Christian Bac
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
class ModuleForm extends sfForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'modulelist' => new sfWidgetFormDoctrineChoice(array('multiple' => False, 'model' => 'ModuleEns', 'order_by'=>array('id','asc'))),
      'listormodify' => new sfWidgetFormChoice(array('choices' => array('lister les présences','modifier les présences'), 'multiple' => False, 'expanded' => True))
    		));
    $this->setValidators(array(
      'modulelist' => new sfValidatorDoctrineChoice(array('multiple' => False, 'model' => 'ModuleEns', 'required' => true)),
	  'listormodify' => new sfValidatorChoice(array('multiple'=>false, 'choices' => array('lister les présences','modifier les présences')))
    		));
    
    $this->widgetSchema->setNameFormat('moduleform[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

 //   $this->setupInheritance();

    parent::setup();
  }

  public function configure()
  {
	$this->useFields(array('modulelist','listormodify'));

  }

}
