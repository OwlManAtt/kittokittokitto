<?php
/**
 * News aggregator classfile. 
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
 * @subpackage News 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
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
     * @param object $db 
     * @return integer
     **/
    static public function grabNewsSize($db)
    {
        $result = new BoardThread($db);
        $result= $result->findBy(array(
                array(
                    'table' => 'board',
                    'column' => 'news_source',
                    'value' => 'Y',
                ),
            ),
        '',true);

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
        $threads = new BoardThread($db);
        $threads = $threads->findBy(array(
                array(
                    'table' => 'board',
                    'column' => 'news_source',
                    'value' => 'Y',
                ),
            ),
            "ORDER BY board_thread.thread_created_datetime DESC",
            false,
            $start,$end
        );

        $NEWS = array();
        foreach($threads as $thread)
        {
            $post = new BoardPost($db);
            $post = $post->findOneByBoardThreadId($thread->getBoardThreadId(),'ORDER BY board_thread_post.posted_datetime ASC');

            // The opposite of this should never occur...but handle it anyway.
            if($post != null)
            {
                $item = new News($post->getPostedDatetime(),$thread->getBoardThreadId(),$post->getPostText(),$post->getUserName(),$post->getUserId(),($thread->grabPosts(null,true) - 1),$thread->getThreadName()); 
                $NEWS[] = $item;
            }
        } // end thread loop
        
        return $NEWS;
    } // end grabLatestNews
} // end News

?>
