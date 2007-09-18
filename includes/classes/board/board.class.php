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
            'local_key' => 'required_permission_id',
            'foreign_table' => 'staff_permission',
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
     * Return the total number of threads under this board.
     *
     * Useful for pagination. Also, that query should work across
     * all major RDBMSes.  
     * 
     * @return integer 
     **/
    public function grabThreadsSize()
    {
        $result = $this->db->getOne('
            SELECT 
                count(*) AS threads 
            FROM board_thread
            WHERE board_thread.board_id = ?
        ',array($this->getBoardId()));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
     
        return $result;
    } // end grabThreadsSize

    /**
     * Grab the threads for the board.
     *
     * The arguments allow you to only grab a slice, if they
     * are specified. That's good for pagination.
     * 
     * @param integet|null $start The beginning of a set slice.
     * @param integer|null $end The end of a set slice.
     * @return array An array of BoardThread instances.
     **/
    public function grabThreads($start=null,$end=null)
    {
        if(($start === null && $end === null) == false && 
            ($start !== null && $end !== null) == false
        )
        {
            throw ArgumentError('Must specify either no arguments or both arguments.');
        } // end problem w/ args.
        
        // Translate into start,# to fetch.
        $total = $end - $start;
        $limit = "LIMIT $start,$total";
        $threads = $this->grab('threads','ORDER BY board_thread.stickied, board_thread.thread_last_posted_datetime DESC',$limit);

        return $threads;
    } // end grabThreads

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
     * @rdbms-specific MySQL / Oracle 9i (maybe?) & 10g
     * @return void
     **/
    public function getLastPosterUserName()
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

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }

        return $result;
    } // end getLastPosterUserName
} // end Board

?>
