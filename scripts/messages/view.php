<?php
/**
 * View a message. 
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
    // Mark this as read.
    if($message->getMessageRead() == 'N')
    {
        $message->setMessageRead('Y');
        $message->save();
    } // end mark as read
    
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
            'signature' => $message->getSenderSignature(),
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
