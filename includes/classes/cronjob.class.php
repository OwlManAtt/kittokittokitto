<?php
/**
 *  
 **/

/**
 * A cronjob entry in the database. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class Cronjob extends ActiveTable
{
    protected $table_name = 'cron_tab';
    protected $primary_key = 'cron_tab_id';

    /**
     * Fetch a list of pending cronjobs. 
     *
     * @param $db PEAR::DB connector 
     * @return array An array of Cronjob objects
     **/
    static public function listPendingJobs($db)
    {  
        $jobs = new Cronjob($db);
    
        return $jobs->findBy(array(
            'enabled' => 'Y',
            array(
                'table' => 'cron_tab',
                'column' => 'unixtime_next_run',
                'value' => time(),
                'search_type' => '<=',
            ),
        ));
    } // end listPendingJobs

    /**
     * Execute a cronjob.
     * 
     * @return bool 
     **/
    public function run()
    {
        if(class_exists($this->getCronClass()) == false)
        {
            return false;
        }
       
        // Create the job and call the required method. 
        eval('$job = new '.$this->getCronClass().'($this->db);');
        $result = $job->performJob();
        $this->complete();

        return $result;
    } // end run

    /**
     * Complete a job.
     *
     * This updates the record with the time it should
     * run at next.
     * 
     * @return bool 
     **/
    public function complete()
    {
        $this->setUnixtimeNextRun((time() + $this->getCronFrequencySeconds()));

        return $this->save();
    } // end complete
    
} // end Cronjob

/**
 * An interface for cronjob classes. 
 * 
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
interface Job 
{
    /**
     * Instruct a job to perform it's work.
     * 
     * @return bool 
     **/
    public function performJob();
} // end Cron interface

?>
