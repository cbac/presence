<?php

/**
 * Presence
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sf_sandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Presence extends BasePresence
{
	public function __toString() {
		return 'presence['.$this->getPersonId().']['.$this->getSequenceId().']';
	}
}