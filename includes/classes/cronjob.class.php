<?php
/**
 * The ghettocron subsystem, for providing emulated crontabs.
 *
 * This could be replaced with proper cron, if you have that
 * available.
 *
 * This file is part of 'Kitto_Kitto_Kitto'.
 *
 * 'Kitto_Kitto_Kitto' is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 3 of the License,
 * or (at your option) any later version.
 * 
 * 'Kitto_Kitto_Kitto' is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.  See the GNU General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU General
 * Public License along with 'Kitto_Kitto_Kitto'; if not,
 * write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author Nicholas 'Owl' Evans <owlmanatt@gmail.com>
 * @copyright Nicolas Evans, 2007
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @package Kitto_Kitto_Kitto
 * @subpackage Ghettocron
 * @version 1.0.0
 **/

/**
 * A cronjob entry in the database. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Ghettocron 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
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
 * An interface for cronjob classes to extend from. 
 * 
 * @package Kitto_Kitto_Kitto
 * @subpackage Ghettocron 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
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
