<?php
/**
 * Create a new thread.
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
$board_id = stripinput($_REQUEST['board_id']);
$board = new Board($db);
$board = $board->findOneByBoardId($board_id);

if($board == null)
{
    $ERRORS[] = 'Invalid board specified.';
}
else
{
    if($board->getLocked($User) == true)
    {
        $ERRORS[] = 'This board is locked.';
    }
} // end board found

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{
    switch($_REQUEST['state'])
    {
        default:
        {
            $BOARD_DATA = array(
                'id' => $board->getBoardId(),
                'name' => $board->getBoardName(),
            );        

            // POSTback from a failed attempt to post.
            if(is_array($_REQUEST['error']) == true)
            {
                $POST = array(
                    'title' => stripinput($_REQUEST['error']['title']),
                    'body' => clean_xhtml($_REQUEST['error']['body']),
                );

                $renderer->assign('post',$POST);
            } // end handle postback errors
           
            $renderer->assign('board',$BOARD_DATA);
            $renderer->display('boards/new_thread.tpl');

            break;
        } // end default

        case 'post':
        {
            $title = stripinput(trim($_POST['post']['title']));
            $text = clean_xhtml(trim($_POST['post']['text']));

            if($title == null)
            {
                $ERRORS[] = 'You must specify a title.';
            }
            elseif(strlen($title) > 60)
            {
                $ERRORS[] = 'There is a maxlength=60 on that field for a reason.';
            }

            if($text == null)
            {
                $ERRORS[] = 'No message specified. It is possible that your HTML was so badly mal-formed that it was dropped by the HTML filter.';
            }

            if((strtotime($User->getDatetimeLastPost()) + $APP_CONFIG['post_interval']) > time())
            {
                $text = secondsToMinutes($APP_CONFIG['post_interval']);
                $ERRORS[] = "You may only post once every $text.";
            } // end user posted too quickly

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);

                $POST = array(
                    'board_id' => stripinput($_REQUEST['board_id']),
                    'title' => $title,
                    'message' => $text,
                );
                
                $renderer->assign('post',$POST);
                $renderer->display('boards/new_thread_error.tpl');
            } // end show errors
            else
            {
                $thread = new BoardThread($db);
                $thread->create(array(
                    'board_id' => $board->getBoardId(),
                    'thread_name' => $title,
                    'user_id' => $User->getUserId(),
                    'thread_created_datetime' => $thread->sysdate(),
                    'thread_last_posted_datetime' => $thread->sysdate(),
                    'stickied' => 0,
                    'locked' => 'N',
                ));

                $post = new BoardPost($db);
                $post->create(array(
                    'board_thread_id' => $thread->getBoardThreadId(),
                    'user_id' => $User->getUserId(),
                    'posted_datetime' => $post->sysdate(),
                    'post_text' => $text,
                ));
               
                $_SESSION['board_notice'] = 'Your thread has been created.';
                redirect(null,null,"threads/{$board->getBoardId()}");
            } // end create thread

            break;
        } // end post

    } // end switch
} // end no issues with the board
?>
