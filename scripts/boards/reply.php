<?php
/**
 * Process a reply submitted via the reply form. 
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
$thread_id  = ($_REQUEST['thread_id']);
$post_text = trim(clean_xhtml($_REQUEST['post']['text']));

if((strtotime($User->getDatetimeLastPost()) + $APP_CONFIG['post_interval']) > time())
{
    $text = secondsToMinutes($APP_CONFIG['post_interval']);
    $ERRORS[] = "You may only post once every $text.";
} // end user posted too quickly

if($post_text == null) 
{
    $ERRORS[] = 'No message specified. It is possible that your HTML was so badly mal-formed that it was dropped by the HTML filter.';
}   

$thread = new BoardThread($db);
$thread = $thread->findOneByBoardThreadId($thread_id);

if($thread == null)
{
    $ERRORS[] = 'Thread does not exist.';
}
else
{
    if($thread->getLocked() != 'N')
    {
        $ERRORS[] = 'That thread is locked.';
    }

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
} // end thread exists

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
} 
else
{
    $post = new BoardPost($db);
    $post->create(array(
        'board_thread_id' => $thread->getBoardThreadId(),
        'user_id' => $User->getUserId(),
        'post_text' => $post_text, 
        'posted_datetime' => $post->sysdate(),
    ));
    
    $thread->setThreadLastPostedDatetime($thread->sysdate());
    $thread->save();
    
    $_SESSION['board_notice'] = "Your message has been posted successfully in <strong>{$thread->getThreadName()}</strong>.";

    redirect(null,null,"threads/{$thread->getBoardId()}");
} // end create post

?>
