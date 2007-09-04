<?php
/**
 * Display a compose new message form.
 *
 * This page has several URLs mapped to it:
 *   1. write-new-message/
 *   2. write-new-message/1
 *   3. write-message-reply/10032
 *   3. write-message-reply/10032/all
 *
 * The write-new-message series of URLs displays a blank form. The '1' at
 * the end of the URL is an optional user ID; if specified, that user will be
 * pre-populated into a To: field.
 *
 * The write-message-reply series of URLs displays a form with RE: + old title, the
 * previous body in a <blockquote>, and the sender in the To: field. If the optional
 * /all is specified, the sender + all recipients minus the user responding will be
 * populated into the To: field.
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
