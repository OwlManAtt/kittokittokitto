<?php
/**
 * Display the user's inbox. 
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
 * @subpackage Messages
 * @version 1.0.0
 **/

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
