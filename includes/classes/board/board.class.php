<?php
/**
 *  
 **/

/**
 * Board 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class Board extends ActiveTable
{
    protected $table_name = 'board';
    protected $primary_key = 'board_id';
    protected $RELATED = array(
        'threads' => array(
            'class' => 'BoardThread',
            'local_key' => 'board_id',
            'foreign_key' => 'board_id',
        ),
    );

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
