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

			$this->moduleId =$request->getParameter('moduleform')['modulelist'];
			$modules = Doctrine_Core::getTable('ModuleEns')
			->createQuery('a')
			->where('id = '. $this->moduleId)
			->execute();
			$this->getUser()->setAttribute('moduleens', $modules[0]);
			if($request->getParameter('moduleform')['listormodify'] == 'list'){
				$this->forward('listpresence','index');
			}else{
				$this->forward('presence','index');
			}
		}
	}
}
