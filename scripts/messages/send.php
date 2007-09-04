<?php
/**
 * Process a new message.
 *
 * I would not normally break the processing for write.php into another file
 * (since, after all, they are married together), but in this case, I have
 * elected to do so in order to reduce confusion.
 *
 * Write.php has a number of aliases provided by mod_rewrite. I don't want
 * this to have any aliases. It's that simple. 
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
$TO = $_POST['to'];
$MESSAGE = array(
    'title' => stripinput($_POST['message']['title']),
    'body' => clean_xhtml($_POST['message']['body']),
);

// Clean TO up.
$CLEAN_TO = array();
$USERS = array(); // for doing notifies with
foreach($TO as $key => $recipient)
{
    $recipient = stripinput(trim($recipient));
    if($recipient != null)
    {
        $user = new User($db);
        $user = $user->findOneByUserName($recipient);
        
        if($user != null)
        {
            $USERS[$user->getUserId()] = $user;
            $CLEAN_TO[$user->getUserId()] = $user->getUserName(); 
        }
        else
        {
            $ERRORS[] = "The user <strong>$recipient</strong> does not exist.";
        }
    } // end recipient not blank
} // end To loop

if(sizeof($CLEAN_TO) > $APP_CONFIG['max_mail_recipients'])
{
    $ERRORS[] = 'You have specified too many recipients.';
}
elseif(sizeof($CLEAN_TO) == 0)
{
    $ERRORS[] = 'No recipients were specified.';
}

if($MESSAGE['title'] == null)
{
    $ERRORS[] = 'You must specify a title.';
}
elseif(strlen($MESSAGE['title']) > 255)
{
    $ERRORS[] = 'There is a maxlength=255 on that field for a reason.';
}

if($MESSAGE['body'] == null)
{
    $ERRORS[] = 'You must specify a body.';
}

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);

    // Prevent XSS - CLEAN_TO has been cleaned, not raw TO.
    foreach($TO as $index => $value)
    {
        $TO[$index] = stripinput($value);
    }
    
    $renderer->assign('to',$TO);
    $renderer->assign('message',$MESSAGE);
    $renderer->display('messages/write_error.tpl');
} // end errors
else
{
    
    foreach($CLEAN_TO as $user_id => $user_name)
    {
        $mail = new Message($db);
        $mail->setSenderUserId($User->getUserId());
        $mail->setRecipientUserId($user_id);
        $mail->setRecipientList($CLEAN_TO);
        $mail->setMessageTitle($MESSAGE['title']);
        $mail->setMessageBody($MESSAGE['body']);
        $mail->setSentAt($mail->sysdate());
        $mail->setMessageRead('N');
        $mail->save();
        
        // Don't notify the sending user if they CC'd themselves.
        if($user_id != $User->getUserId())
        { 
            $USERS[$user_id]->notify("{$User->getUserName()} has sent you a message.","message/{$mail->getUserMessageId()}");
        }
    } // end add messages

    $suffix = '';
    if(sizeof($CLEAN_TO) > 1)
    {
        $suffix = 's';
    }

    $_SESSION['messages_notice'] = "You have sent the message{$suffix}.";
    redirect('messages');
} // no errors
?>
