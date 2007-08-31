<?php
/**
 *  
 **/

/**
 * The shop restocking job. 
 * 
 * @uses Cron
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
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
