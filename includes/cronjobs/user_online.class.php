<?php
/**
 * Ghettocronjob definition for deleting online users who
 * have not been active in the last five minutes. 
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
 * Cleans the user_online table up every few minutes. 
 * 
 * @uses Cron
 * @package Kitto_Kitto_Kitto
 * @subpackage Ghettocron 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v3 {@link http://www.gnu.org/licenses/gpl-3.0.txt}
 **/
class Job_UserOnline implements Job 
{
    protected $db;
    
    public function __construct(&$db)
    {
        $this->db = $db;
    } // end __construct

    /**
     * performJob 
     * 
     * @rdbms-specific
     * @return void
     **/
    public function performJob()
    {
        switch($this->db->phptype)
        {
            case 'oci8':
            {
                throw new ArgumentError('Not implemented for oci8.');

                break;
            } // end oci8

            case 'pgsql':
            {
                $result = $this->db->query("DELETE FROM user_online WHERE datetime_last_active + interval '5 minutes' < NOW()");

                break;
            } // end pgsql

            case 'mysql':
            case 'mysqli':
            {
                $result = $this->db->query("DELETE FROM user_online WHERE (UNIX_TIMESTAMP(datetime_last_active) + (60 * 5)) < UNIX_TIMESTAMP(NOW())");

                break;
            } // end mysql
        } // end rdbms type switch
        
        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        } // end sql error
    
        return true;
    } // end performJob
} // end Job_UserOnline

?>
