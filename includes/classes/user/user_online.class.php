<?php
/**
 * Online users classfile. 
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
 * @subpackage Core
 * @version 1.0.0
 **/

/**
 * UserOnline 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPL v3
 **/
class UserOnline extends ActiveTable
{
    protected $table_name = 'user_online';
    protected $primary_key = 'user_online_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'user_id',
            'foreign_table' => 'user',
            'foreign_key' => 'user_id',
            'join_type' => 'left', // might be a guest row
        ),
    );

    /**
     * Count the number of users online. 
     * 
     * @param object $db PEAR::DB connector
     * @return integer 
     **/
    static public function totalOnline($db)
    {
        $result = new UserOnline($db);
        $result = $result->findBy(array(),null,true);

        return $result;
    } // end totalOnline

    /**
     * Count the number of unhidden users online. 
     * 
     * @param object $db PEAR::DB connector
     * @return integer 
     **/
    static public function totalUnhiddenUsers($db)
    {
        $result = new UserOnline($db);
        $result = $result->findBy(array(
            array(
                'table' => 'user',
                'column' => 'show_online_status',
                'value' => 'Y',
            ),
        ),null,true);

        return $result;
    } // end totalUnhiddenUsers

    /**
     * Count the number of hidden users online. 
     * 
     * @param object $db PEAR::DB connector
     * @return integer 
     **/
    static public function totalHidden($db)
    {
        $result = new UserOnline($db);
        $result = $result->findBy(array(
            array(
                'table' => 'user',
                'column' => 'show_online_status',
                'value' => 'N',
            ),
        ),null,true);

        return $result;
    } // end totalHidden

    /**
     * Count the number of guest clients online. 
     * 
     * @param object $db PEAR::DB connector
     * @return integer 
     **/
    static public function totalGuests($db)
    {
        $result = new UserOnline($db);
        $result = $result->findBy(array('user_type' => 'guest'),null,true);

        return $result;
    } // end totalUGuests

    /**
     * Count the number of users online. 
     * 
     * @param object $db PEAR::DB connector
     * @return integer 
     **/
    static public function totalUsers($db)
    {
        $result = new UserOnline($db);
        $result = $result->findBy(array('user_type' => 'user'),null,true);

        return $result;
    } // end totalUsers

    static public function findOnlineUsers($start,$end,$db)
    {
        $users = new UserOnline($db);
        $users = $users->findBy(array(
                'user_type' => 'user',
                array(
                    'table' => 'user',
                    'column' => 'show_online_status',
                    'value' => 'Y',
                ),
            ),
            array(
                'direction' => 'ASC',
                'columns' => array(
                    array(
                        'table' => 'user',
                        'column' => 'user_name',
                    ),
                ),
            ),false,$start,$end);
        
        return $users;
    } // end findOnlineUsers

} // end UserOnline

?>
