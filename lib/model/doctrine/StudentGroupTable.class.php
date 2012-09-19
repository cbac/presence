<?php

/**
 * StudentGroupTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class StudentGroupTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object StudentGroupTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('StudentGroup');
    }
    public static function getGroups($cid){
    	// read the groupes
    	$rawGroups = self::getInstance()
    	->createQuery('g')
    	->addWhere('g.cid = '. $cid)
    	->execute();
    
    	$groups = array();
    	foreach($rawGroups as $group){
    		$groups[$group->getId()] = $group;
    	}
    	return $groups;
    }
}