<?php
/**
 *  
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
    
    $_SESSION['board_notice'] = "Your message has been posted successfully in <strong>{$thread->getThreadName()}</strong>";

    redirect(null,null,"threads/{$thread->getBoardId()}");
} // end create post

?>
