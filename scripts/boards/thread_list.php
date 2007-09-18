<?php
/**
 * List out the threads on a board. 
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

$ERRORS = array();

/**
 * Be careful setting this higher - each thread
 * is a few queries a piece (done because I'm a lazy
 * developer not worrying too much about scalability,
 * so I may as well make the ORM do something it isn't
 * too good at to save me time!), so they add up quick.
 **/
$max_threads_per_page = 15;

// This is the number of posts shown per page in a thread.
// It is used to determine whether or not to show a link to 
// the thread's last page.
$max_posts_per_page = 15; 

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{
    $page_id = 1;
}

// Where do we slice the record set? (Note: Don't worry about
// LIMIT X,Y starting from zero - that'll be abstracted away).
$start = (($page_id - 1) * $max_threads_per_page);
$end = (($page_id - 1) * $max_threads_per_page) + $max_threads_per_page;

// Load the board.
$board_id = stripinput($_REQUEST['board_id']);
$board = new Board($db);
$board = $board->findOneByBoardId($board_id);

if($board == null)
{
    $ERRORS[] = 'Invalid board.';
}
else
{
    if($board->hasAccess($User) == false)
    {
        $ERRORS[] = 'Invalid board.';
    }
}

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{
    $BOARD_DATA = array(
        'id' => $board->getBoardId(),
        'name' => $board->getBoardName(),
        'locked' => $board->getBoardLocked($User),
    );
    
    // Generate the pagination. 
    $pagination = pagination("threads/{$board->getBoardId()}",$board->grabThreadsSize(),$max_threads_per_page,$page_id);
    
    $THREAD_LIST = array();
    $threads = $board->grabThreads($start,$end);

    foreach($threads as $thread)
    {
        $THREAD_LIST[] = array(
            'id' => $thread->getBoardThreadId(),
            'topic' => $thread->getThreadName(),
            'posts' => $thread->grabPostsSize() - 1,
            'created_at' => $User->formatDate($thread->getThreadCreatedDatetime()),
            'poster_username' => $thread->getUserName(),
            'poster_id' => $thread->getUserId(),
            'last_post_at' => $User->formatDate($thread->getThreadLastPostedDatetime()),
            'last_page' => ceil($thread->grabPostsSize() / $max_posts_per_page),
            'sticky' => $thread->getStickied(),
            'locked' => $thread->getLocked(),
        );
    } // end thread loop

    if($_SESSION['board_notice'] != null)
    {
        $renderer->assign('board_notice',$_SESSION['board_notice']);
        unset($_SESSION['board_notice']);
    } // end notice

    $renderer->assign('board',$BOARD_DATA);    
    $renderer->assign('threads',$THREAD_LIST);
    $renderer->assign('pagination',$pagination);
    $renderer->display('boards/thread_list.tpl');
} // end board exists
?>
