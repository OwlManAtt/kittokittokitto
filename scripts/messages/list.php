<?php

$max_messages_per_page = 15;

switch($_REQUEST['state'])
{
    default:
    {
        // Handle the page ID for slicing and dicing the inventory up.
        $page_id = stripinput($_REQUEST['page']);
        if($page_id == null || $page_id <= 0)
        {
            $page_id = 1;
        }

        // Where do we slice the record set? (Note: Don't worry about
        // LIMIT X,Y starting from zero - that'll be abstracted away).
        $start = (($page_id - 1) * $max_messages_per_page);
        $end = (($page_id - 1) * $max_messages_per_page) + $max_messages_per_page;

        // Generate the pagination. 
        $pagination = pagination('messages',$User->grabMessagesSize(),$max_messages_per_page,$page_id);

        $messages = $User->grabMessages($start,$end);

        $MESSAGES = array();
        foreach($messages as $message)
        {
            $MESSAGES[] = array(
                'id' => $message->getUserMessageId(),
                'sender' => array(
                    'id' => $message->getSenderUserId(),
                    'name' => $message->getSenderUserName(),
                ),
                'sent_at' => $User->formatDate($message->getSentAt()),
                'title' => truncate($message->getMessageTitle()),
                'read' => $message->getMessageRead(),
            );
        } // end messages loop

        if($_SESSION['messages_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['messages_notice']);
            unset($_SESSION['messages_notice']);
        } // end message notice

        $renderer->assign('pages',$pagination);
        $renderer->assign('messages',$MESSAGES);
        $renderer->display('messages/list.tpl');

        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        
        if(is_array($_POST['delete']) == false)
        {
            $ERRORS[] = 'No messages to delete specified.';
        }
        else
        {
            if(sizeof($_POST['delete']) == 0)
            {
                $ERRORS[] = 'No messages to delete specified.';
            }
        } // end is array
        
        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            /*
             * This will perform a DELETE with two conditions: 
             *   1. The message ID is in the set of IDs specified.
             *   2. The recipient_user_id is this user.
             * As such, there's no need to validate these messages belont
             * to this user - if they do not, they simply will not be deleted.
             */
            Message::deleteBulk($User->getUserId(),array_values($_POST['delete']),$db);

            $plural = (sizeof($_POST['delete']) != 1) ? 's' : null;
            $_SESSION['messages_notice'] = "You have deleted the message{$plural}.";
            redirect('messages');
        } // end no errors
        
        break;
    } // end delete
} // end state switch
?>
