<?php
/**
 *  
 **/

$ERRORS = array();
$message_id = stripinput($_REQUEST['message_id']);
$message = new Message($db);
$message = $message->findOneByUserMessageId($message_id);

if($message == null)
{
    $ERRORS[] = 'Invalid message specified.';
}
else
{
    if($message->getRecipientUserId() != $User->getUserId())
    {
        $ERRORS[] = 'This is not your message.';
    }
} // end message exists

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{
    // === Note:
    // The HTML for the recipient list is generated here because Smarty has
    // no facilities to do an implode() on an array itself.
    $LIST = array();
    $recipients = $message->getRecipientList();
    foreach($recipients as $user_id => $user_name)
    {
        $LIST[] = "<a href='{$APP_CONFIG['public_dir']}/profile/$user_id'>$user_name</a>";
    } // end recipient-html-maker
    
    $MESSAGE = array(
        'id' => $message->getUserMessageId(),
        'from' => array(
            'id' => $message->getSenderUserId(),
            'name' => $message->getSenderUserName(),
        ),  
        'recipients' => implode(', ',$LIST),
        'title' => $message->getMessageTitle(),
        'body' => $message->getMessageBody(),
        'sent_at' => $User->formatDate($message->getSentAt()),
    );

    $renderer->assign('message',$MESSAGE);
    $renderer->display('messages/view.tpl');
} // end no errors

?>
