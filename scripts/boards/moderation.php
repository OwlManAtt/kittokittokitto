<?php
/**
 *  
 **/

$post_id = stripinput($_POST['post']['id']);
$page = stripinput($_POST['post']['page']);

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
} // end post exists

$MAP = array(
    // action => permission
    'delete_post' => 'delete_post',
    'delete_thread' => 'delete_post',
    'stick' => 'manage_thread',
    'lock' => 'manage_thread',
);

if($User->hasPermission($MAP[$_POST['action']]) == false)
{
    $ERRORS[] = 'You do not have permission to do that.';
}

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{   
    switch($_POST['action'])
    {
        case 'delete_post':
        {

            if($thread->grabPostsSize() == 1)
            {
                $_SESSION['board_notice'] = 'You have deleted the thread.';

                // Last post, kill the thread since
                // there's nothing left in here.
                $board_id = $thread->getBoardId();
                $thread->destroy();

                redirect(null,null,"threads/$board_id");
            }
            else
            {
                $_SESSION['board_notice'] = 'You have deleted the post.';

                $post->destroy();
                redirect(null,null,"thread/{$thread->getBoardThreadId()}");
            }
            break;
        } // end delete_post
        
        case 'delete_thread':
        {
            $_SESSION['board_notice'] = 'You have deleted the thread.';

            $board_id = $thread->getBoardId();
            $thread->destroy();

            redirect(null,null,"threads/$board_id");

            break;
        } // end delete_thread
        
        case 'lock':
        {
            if($thread->getLocked() == 'Y')
            {
                $thread->setLocked('N');
                $_SESSION['board_notice'] = 'You have unlocked the thread.';
            }
            else
            {
                $thread->setLocked('Y');
                $_SESSION['board_notice'] = 'You have locked the thread.';
            }
            
            $thread->save();
            redirect(null,null,"thread/{$thread->getBoardThreadId()}/$page");
            
            break;
        } // end lock 
        
        case 'stick':
        {
            if($thread->getStickied() == true)
            {
                $_SESSION['board_notice'] = 'You have unstuck the thread.';
                $thread->setStickied(false);
            }
            else
            {
                $_SESSION['board_notice'] = 'You have stuck the thread.';
                $thread->setStickied(true);
            }

            $thread->save();
            redirect(null,null,"thread/{$thread->getBoardThreadId()}/$page");

            break;
        } // end stick
        
        default:
        {
            draw_errors('Invalid action.');

            break;
        } // end default
    } // end action switch
} // end permission wrapping
?>
