<?php
/**
 * Board classfile. 
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
 * @subpackage Board
 * @version 1.0.0
 **/

/**
 * Board 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class Board extends ActiveTable
{
    protected $table_name = 'board';
    protected $primary_key = 'board_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'board_category_id',
            'foreign_table' => 'board_category',
            'foreign_key' => 'board_category_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'required_permission_id',
            'foreign_table' => 'staff_permission',
            'foreign_key' => 'staff_permission_id',
            'join_type' => 'left',
        ),
        array(
            'local_key' => 'required_permission_id',
            'foreign_table' => 'staff_permission',
            'foreign_table_alias' => 'category_permission',
            'foreign_key' => 'staff_permission_id',
            'join_type' => 'left',
        ),
    );
    protected $RELATED = array(
        'threads' => array(
            'class' => 'BoardThread',
            'local_key' => 'board_id',
            'foreign_key' => 'board_id',
        ),
    );
    
    /**
     * Determine whether or not a user has access to this board.
     * 
     * @param User $user 
     * @return bool 
     **/
    public function hasAccess(User $user)
    {
        // If there is a required permission on this this, check it. 
        if($this->get('api_name','category_permission') != null)
        {
            if($user->hasPermission($this->get('api_name','category_permission')) == false)
            {
                return false;
            }
        } // end category has perm
        
        if($this->getApiName() == null)
        {
            return true;
        }
        elseif($user->hasPermission($this->getApiName()) == true)
        {
            return true; 
        }

        return false;
    } // end hasAccess

    public function getBoardLocked(User $user)
    {
        // If the user can ignore boards being locked...
        if($user->hasPermission('ignore_board_lock') == true)
        {
            return false;
        }

        if($this->get('board_locked') == 'Y')
        {
            return true;
        }
        
        return false;
    } // end getLocked

    /**
     * Returns the number of post that exist under this board.
     * 
     * @rdbms-specific MySQL / Oracle 9i (maybe?) & 10g
     * @return integer 
     **/
    public function grabPostCount()
    {
        $result = $this->db->getOne('
            SELECT 
                count(*) AS posts
            FROM board_thread_post
            INNER JOIN board_thread ON board_thread_post.board_thread_id = board_thread.board_thread_id
            WHERE board_thread.board_id = ?
        ',array($this->getBoardId()));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
     
        return $result;
    } // end grabPostCount

    /**
     * getLastPosterUserName 
     * 
     * @rdbms-specific
     * @return void
     **/
    public function getLastPosterUserName()
    {
        switch($this->db->phptype)
        {
            case 'mysql':
            case 'mysqli':
            {
                $result = $this->db->getOne('
                    SELECT
                        user.user_name
                    FROM board_thread_post
                    INNER JOIN board_thread ON board_thread.board_thread_id = board_thread_post.board_thread_id
                    INNER JOIN user ON board_thread_post.user_id = user.user_id
                    WHERE board_thread.board_id = ?
                    ORDER BY board_thread_post.posted_datetime DESC
                    LIMIT 1
                ',array($this->getBoardId()));

                break;
            } // end mysql

            case 'pgsql':
            {
                $result = $this->db->getOne('
                    SELECT
                        "user"."user_name"
                    FROM board_thread_post
                    INNER JOIN board_thread ON board_thread.board_thread_id = board_thread_post.board_thread_id
                    INNER JOIN "user" ON board_thread_post.user_id = "user".user_id
                    WHERE board_thread.board_id = ?
                    ORDER BY board_thread_post.posted_datetime DESC
                    LIMIT 1
                ',array($this->getBoardId()));

                break;
            } // end pgsql

            default:
            {
                throw new ArgumentError('Query not implmented for RDBMS.');

                break;
            } // end default
        } // end rdbms switch

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }

        return $result;
    } // end getLastPosterUserName
} // end Board

?>
