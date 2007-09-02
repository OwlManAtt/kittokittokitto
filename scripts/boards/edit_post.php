<?php
/**
 *  
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
