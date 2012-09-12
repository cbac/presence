<?php

/**
 * choixmodule actions.
 *
 * @package    sf_sandbox
 * @subpackage choixmodule
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class choicemoduleActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->modules = Doctrine_Core::getTable('ModuleEns')
		->createQuery('a')
		->execute();
		$this->form = new ModuleForm();
	}

	public function executeChoose(sfWebRequest $request)
	{
		if($request->isMethod(sfRequest::POST)){
			$moduleform = $request->getParameter('moduleform');
			$this->moduleId = $moduleform['modulelist'];
			$modules = Doctrine_Core::getTable('ModuleEns')
			->createQuery('a')
			->where('id = '. $this->moduleId)
			->execute();
			if(count($modules)>0){
			$this->getUser()->setAttribute('moduleens', $modules[0]);
			if($moduleform['listormodify'] == 0){
				$this->redirect('listpresence/index');
			}else{
				$this->redirect('presence/index');
			}
			}
			$this->redirect('choicemodule/index');
		}
	}
}
