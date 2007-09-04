<?php
/**
 * Ghettocronjob definition for restocking shops. 
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
 * The shop restocking job. It's a Ghettocronjob that wraps a method
 * on ShopRestock.
 * 
 * @uses Cron
 * @package Kitto_Kitto_Kitto
 * @subpackage Ghettocron 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v3 {@link http://www.gnu.org/licenses/gpl-3.0.txt}
 **/
class Job_RestockShops implements Job 
{
    protected $db;
    
    public function __construct(&$db)
    {
        $this->db = $db;
    } // end __construct

    /**
     * Find all of the items pending restock and stock 'em.
     * 
     * This is implemented in an RDMBS-agnostic manner, and as
     * a result, all of this looping can become wildly inefficient
     * when there's a lot of stuff to restock. 
     *
     * It may be better to re-implement this to use an 
     * INSERT INTO ... SELECT ... query and then an UPDATE query
     * to change the next restock time. But, since I am designing
     * a template app that needs to support many RDBMSes, I can do
     * things that are Bad(tm) and let the scalability issues be you
     * problem when you run into them. Neener-neener!
     *
     * @return bool
     **/
    public function performJob()
    {
        ShopRestock::processPendingRestocks($this->db);
    } // end performJob
} // end Job_RestockShops

?>
