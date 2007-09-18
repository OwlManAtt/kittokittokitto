<?php
/**
 * List out the posts in a thread. 
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
$max_items_per_page = 15;

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{
    $page_id = 1;
}

// Where do we slice the record set? (Note: Don't worry about
// LIMIT X,Y starting from zero - that'll be abstracted away).
$start = (($page_id - 1) * $max_items_per_page);
$end = (($page_id - 1) * $max_items_per_page) + $max_items_per_page;

// Load the board.
$thread_id = stripinput($_REQUEST['thread_id']);
$thread = new BoardThread($db);
$thread = $thread->findOneByBoardThreadId($thread_id);

if($thread == null)
{
    $ERRORS[] = 'Invalid thread specified.';
}
else
{
    // Load the board info.
    $board = new Board($db);
    $board = $board->findOneByBoardId($thread->getBoardId());

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
} // end thread is valid
    
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
    
    $THREAD_DATA = array(
        'id' => $thread->getBoardThreadId(),
        'name' => $thread->getThreadName(),
        'locked' => $thread->getLocked(),
        'sticky' => $thread->getStickied(),
        'can_edit' => (($User->hasPermission('edit_post') == true)) ? true : false,
    );
    
    // Generate the pagination. 
    $pagination = pagination("thread/{$thread->getBoardThreadId()}",$thread->grabPostsSize(),$max_items_per_page,$page_id);
    
    $POST_LIST = array();
    $posts = $thread->grabPosts($start,$end);

    foreach($posts as $post)
    {
        $POST_LIST[] = array(
            'id' => $post->getBoardThreadPostId(),
            'posted_at' => $User->formatDate($post->getPostedDatetime()),
            'text' => $post->getPostText(),
            'user_id' => $post->getUserId(), 
            'username' => $post->getUserName(),
            'user_title' => $post->getUserTitle(),
            'signature' => $post->getSignature(),
            'avatar_url' => $post->getAvatarUrl(),
            'avatar_name' => $post->getAvatarName(),
            'user_post_count' => $post->getPostCount(),
            'page' => $page_id,
            'can_edit' => (($User->hasPermission('edit_post') == true)) ? true : false,
        );
    } // end thread loop

    $ADMIN_ACTIONS = array('' => 'Moderation...');

    if($User->hasPermission('delete_post') == true)
    {
        $ADMIN_ACTIONS['delete_post'] = 'Delete Post';
        $ADMIN_ACTIONS['delete_thread'] = 'Delete Thread';
    }

    if($User->hasPermission('manage_thread') == true)
    {
        if($thread->getLocked() == 'N')
        {
            $ADMIN_ACTIONS['lock'] = 'Lock Thread'; 
        }
        else
        {
            $ADMIN_ACTIONS['lock'] = 'Unock Thread'; 
        }
         
        if($thread->getStickied() == 0)
        {
            $ADMIN_ACTIONS['stick'] = 'Stick Thread';
        }
        else
        {
            $ADMIN_ACTIONS['stick'] = 'Unstick Thread';
        }
    } // end thread management
    
    if(sizeof($ADMIN_ACTIONS) > 1)
    {
        $renderer->assign('actions',$ADMIN_ACTIONS);
    }

    if($_SESSION['board_notice'] != null)
    {
        $renderer->assign('board_notice',$_SESSION['board_notice']);
        unset($_SESSION['board_notice']);
    }
    
    $renderer->assign('board',$BOARD_DATA);    
    $renderer->assign('thread',$THREAD_DATA);    
    $renderer->assign('posts',$POST_LIST);
    $renderer->assign('page',$page_id);
    $renderer->assign('pagination',$pagination);
    $renderer->display('boards/post_list.tpl');
} // end board exists
?>
