<?php

$ERRORS = array();
$TO = array(); // id => name
$MESSAGE = array(
    'title' => null,
    'body' => null,
);

// This file has several slugs mapped to it, and they pass 
// different arguments. Furthermore, this page can be POST'd back to
// if there were errors sending a previous message. Handle these.
if($_POST['error'] == 'true')
{
    foreach($_POST['to'] as $user) 
    {
        if($user != '')
        {
            $TO[] = stripinput($user);
        }
    } // end strip to
    
    $MESSAGE = array(
        'title' => stripinput($_POST['message']['title']),
        'body' => clean_xhtml($_POST['message']['body']),
    );
} // end error postback
elseif(isset($_REQUEST['to_user_id']) && $_REQUEST['to_user_id'] != '')
{
    $to_user_id = stripinput($_REQUEST['to_user_id']);
    
    $to = new User($db);
    $to = $to->findOneByUserId($to_user_id);
    
    if($to == null)
    {
        $ERRORS[] = 'Invalid user specified.';
    }
    else
    {
        $TO[] = $to->getUserName(); 
    }
} // end to_user_id - new message w/ user specified
elseif(isset($_REQUEST['reply_to_id']))
{
    $reply_to_id = stripinput($_REQUEST['reply_to_id']);
    
    $original = new Message($db);
    $original = $original->findOneByUserMessageId($reply_to_id);

    if($original == null)
    {
        $ERRORS[] = 'Invalid message specifid.';
    }
    else
    {
        if($original->getRecipientUserId() != $User->getUserId())
        {
            $ERRORS[] = 'That is not your message.';
        }
        else
        {
            $MESSAGE['title'] = 'RE: '.$original->getMessageTitle();
            $MESSAGE['body'] = "\n\n\n<blockquote><p style='font-weight: bold;'>{$original->getSenderUserName()} wrote:</p>\n{$original->getMessageBody()}</blockquote>\n\n";

            if(strtolower($_REQUEST['reply_to_all']) == 'all')
            {
                $list = $original->getRecipientList();
                unset($list[$User->getUserId()]);
            
                $list = array_values($list);

                // Unshift pops the sender on to the beginning of the list.
                array_unshift($list,$original->getSenderUserName());
                $TO = $list;
            } // end all
            else
            {
                $TO[] = $original->getSenderUserName();
            }
        } // end message belongs to user
    } // message valid
} // end reply to existing message

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{
    // If, for some reason, more than the max number of recipients is 
    // specified, break the array into chunks of five and take the first 
    // five.
    if(sizeof($TO) > $APP_CONFIG['max_mail_recipients'])
    {
        $TO = array_chunk($TO,$APP_CONFIG['max_mail_recipients']);
        $TO = $TO[0];
    } // end trim array
    
    $renderer->assign('max_to',$APP_CONFIG['max_mail_recipients']);
    $renderer->assign('to_total',sizeof($TO));
    $renderer->assign('to',$TO);
    $renderer->assign('message',$MESSAGE);

    $renderer->display('messages/write.tpl');
} // end no errors
?>
