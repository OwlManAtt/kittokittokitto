<?php
/**
 * Edit a post (just the message). 
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

$post_id = stripinput($_REQUEST['post_id']);
$page = stripinput($_REQUEST['page']);

$post = new BoardPost($db);
$post = $post->findOneByBoardThreadPostId($post_id);

if($post == null)
{
    $ERRORS[] = 'Invalid post specified.';
}
else
{
    $thread = new BoardThread($db);
    $thread = $thread->findOneByBoardThreadId($post->getBoardThreadId());
    
    if($thread == null)
    {
        $ERRORS[] = 'Invalid thread.';
    }
    else
    {
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
    
    if($User->hasPermission('edit_post') == false)
    {
        $ERRORS[] = 'You do not have permission to edit this post.';
    }
} // end post exists

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
            $BOARD = array(
                'id' => $board->getBoardId(),
                'name' => $board->getBoardName(),
            );

            $THREAD = array(
                'id' => $thread->getBoardThreadId(),
                'name' => $thread->getThreadName(),
                'sticky' => $thread->getStickied(),
            );
           
            $renderer->assign('board',$BOARD); 
            $renderer->assign('thread',$THREAD); 
            $renderer->assign('page',$page);
            $renderer->assign('post_id',$post->getBoardThreadPostId());
            $renderer->assign('text',$post->getPostText());
            $renderer->display('boards/edit_post.tpl');

            break;
        } // end default
    
        case 'save':
        {
            $html = trim(clean_xhtml($_POST['post_text']));

            if($html == null)
            {
                draw_errors('You cannot blank the message out.');
            }   
            else
            {
                $post->setPostText($html);
                $post->save();
                $_SESSION['board_notice'] = 'You have edited the post.';
                
                redirect(null,null,"thread/{$thread->getBoardThreadId()}/{$page}#{$post->getBoardThreadPostId()}");
            }

            break;
        } // end save
    } // end state switch
} // end no errors
?>
