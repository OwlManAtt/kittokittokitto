<?php
/**
 *  
 **/

$thread_id = stripinput($_REQUEST['thread_id']);
$page = stripinput($_REQUEST['page']);

$thread = new BoardThread($db);
$thread = $thread->findOneByBoardThreadId($thread_id);

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
            $renderer->display('boards/edit_thread.tpl');

            break;
        } // end default
    
        case 'save':
        {
            $title = trim($_POST['thread']['title']);

            if($title == null)
            {
                draw_errors('You cannot blank the topic out.');
            }   
            else
            {
                $thread->setThreadName($title);
                $thread->save();
                $_SESSION['board_notice'] = 'You have edited the topic.';
                
                redirect(null,null,"thread/{$thread->getBoardThreadId()}/{$page}");
            }

            break;
        } // end save
    } // end state switch
} // end no errors
?>
