<?php

/**
 * AttendanceTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AttendanceTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object AttendanceTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Attendance');
    }
    public static function getAttendances($mid){
    	$rawAttendances = self::getInstance()
    	->createQuery('a')
    	->innerJoin('a.Sequence s')
    	->addWhere('s.mid = ' . $mid)
    	->addOrderBy('a.person_id')
    	->addOrderBy('a.sequence_id')
    	->execute();
    	$attendances = array();
    	foreach($rawAttendances as $presence){
    		$uid = (int) $presence->getPersonId();
    		$seqid = (int) $presence->getSequenceId();
    		if(!array_key_exists($uid,$attendances)){
    			$attendances[$uid] = array();
    		}
    		$attendances[$uid][$seqid] = $presence;
    	}
       	return $attendances;
    }
}