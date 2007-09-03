<?php
/**
 *  
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
            }
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
