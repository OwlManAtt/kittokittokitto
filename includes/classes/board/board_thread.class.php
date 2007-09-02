<?php
/**
 *  
 **/

/**
 * BoardThread 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v3 {@link http://www.gnu.org/licenses/gpl-3.0.txt}
 **/
class BoardThread extends ActiveTable
{
    protected $table_name = 'board_thread';
    protected $primary_key = 'board_thread_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'user_id',
            'foreign_table' => 'user',
            'foreign_key' => 'user_id',
            'join_type' => 'inner',
        ),
    );
    protected $RELATED = array(
        'posts' => array(
            'class' => 'BoardPost',
            'local_key' => 'board_thread_id',
            'foreign_key' => 'board_thread_id',
        ), 
    );
    
    /**
     * Determine if a thread is 'stuck'.
     * 
     * To make ORDER BY work properly for stickies (and without
     * having to do a seperate query), stickied = 0 for stuck and 
     * stickied = 1 for not stuck. Since that is pretty counter-
     * intuitive, this function translates it into stuck, true or 
     * false.
     *
     * @return bool 
     **/
    public function getStickied()
    {
        if($this->get('stickied') == 0)
        {
            return true; 
        }
        
        return false;
    } // end getStickied

    /**
     * Return the total number of posts under this thread.
     *
     * Useful for pagination. Also, that query should work across
     * all major RDBMSes.  
     * 
     * @return integer 
     **/
    public function grabPostsSize()
    {
        $result = $this->db->getOne('
            SELECT 
                count(*) AS posts
            FROM board_thread_post
            WHERE board_thread_post.board_thread_id = ?
        ',array($this->getBoardThreadId()));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
     
        return $result;
    } // end grabPostsSize

    /**
     * Grab the posts for the thread.
     *
     * The arguments allow you to only grab a slice, if they
     * are specified. That's good for pagination.
     * 
     * @param integet|null $start The beginning of a set slice.
     * @param integer|null $end The end of a set slice.
     * @return array An array of BoardPost instances.
     **/
    public function grabPosts($start=null,$end=null)
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
        $posts = $this->grab('posts','ORDER BY board_thread_post.posted_datetime ASC',$limit);

        return $posts;
    } // end grabPosts
} // end BoardThread

?>
