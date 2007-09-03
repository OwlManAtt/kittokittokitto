<?php
/**
 *  
 **/

/**
 * News aggregation class.
 *
 * Provides the ability to get all of the important posts
 * from newssource boards. 
 *
 * This class 'emulates' the ActiveTable API (#getFoo()) so it
 * feels like the rest of the site to a developer.
 * 
 * @uses Getter
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v3 {@link http://www.gnu.org/licenses/gpl-3.0.txt}
 **/
class News extends Getter
{
    protected $datetime;
    protected $thread_id;
    protected $title;
    protected $text;
    protected $user_name;
    protected $user_id;
    protected $comments;

    /**
     * Sets the object up. 
     * 
     * @param string $datetime 
     * @param int $thread_id 
     * @param string $text 
     * @param string $username 
     * @param int $user_id 
     * @param int $comments 
     * @param string $title 
     * @return void
     **/
    public function __construct($datetime,$thread_id,$text,$username,$user_id,$comments,$title)
    {
        $this->datetime = $datetime;
        $this->thread_id = $thread_id;
        $this->text = $text;
        $this->user_name = $username;
        $this->user_id = $user_id;
        $this->comments = $comments;
        $this->title = $title;

        return true;
    } // end __construct

    /**
     * Total number of news posts. 
     * 
     * @rdbms-specific MySQL 4/5, Oracle 9i (maybe?) / 10g.
     * @param object $db 
     * @return integer
     **/
    static public function grabNewsSize($db)
    {
        $result = $db->getOne('
            SELECT 
                count(*) AS threads 
            FROM board_thread 
            INNER JOIN board ON board_thread.board_id = board.board_id
            WHERE board.news_source = ?
        ',array('Y'));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }

        return $result;
    } // end grabNewsSize

    /**
     * Returns the latest news items in reverse-chronological order. 
     *
     * This works by finding the most recent threads in forums flagged
     * as `board`.`news_source` = 'Y', then finding the first post in
     * those threads.
     * 
     * @param int $items 
     * @param mixed $db 
     * @return array An array of News instances.
     **/
    static public function grabNews($start,$end,$db)
    {
        $length = $end - $start;
        $limit = "LIMIT $start,$length";
        
        $threads = new BoardThread($db);
        $threads = $threads->findBy(array(
                array(
                    'table' => 'board',
                    'column' => 'news_source',
                    'value' => 'Y',
                ),
            ),
            "ORDER BY board_thread.thread_created_datetime DESC {$limit}"
        );

        $NEWS = array();
        foreach($threads as $thread)
        {
            $post = new BoardPost($db);
            $post = $post->findOneByBoardThreadId($thread->getBoardThreadId(),'ORDER BY board_thread_post.posted_datetime ASC');

            // The opposite of this should never occur...but handle it anyway.
            if($post != null)
            {
                $item = new News($post->getPostedDatetime(),$thread->getBoardThreadId(),$post->getPostText(),$post->getUserName(),$post->getUserId(),($thread->grabPostsSize() - 1),$thread->getThreadName()); 
                $NEWS[] = $item;
            }
        } // end thread loop
        
        return $NEWS;
    } // end grabLatestNews
} // end News

?>
