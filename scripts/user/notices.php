<?php
/**
 * Allows a user to delete their notice history. 
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
 * @subpackage Core
 * @version 1.0.0
 **/

switch($_REQUEST['state'])
{
    default:
    {
        $NOTICE_LIST = array();
        $notices = $User->grabNotifications('ORDER BY notification_datetime DESC',null,true);

        foreach($notices as $notice)
        {
            $NOTICE_LIST[] = array(
                'id' => $notice->getUserNotificationId(),
                'text' => $notice->getNotificationText(),
                'date' => $User->formatDate($notice->getNotificationDatetime()),
            );
        } // end notice loop

        if($_SESSION['event_notice'] != null)
        {
            $renderer->assign('event_notice',$_SESSION['event_notice']);
            unset($_SESSION['event_notice']);
        } // end event notice

        $renderer->assign('events',$NOTICE_LIST);
        $renderer->display('user/events.tpl');

        break;
    } // end default

    case 'jump':
    {
        $notice_id = stripinput($_REQUEST['notification_id']);
        
        $notice = new Notification($db);
        $notice = $notice->findOneBy(array(
            'user_notification_id' => $notice_id,
            'user_id' => $User->getUserId(),
        ));

        if($notice == null)
        {
            draw_errors('Invalid notice specified.');
        }
        else
        {
            $raw = $notice->getNotificationUrl();
            $notice->destroy();
            redirect(null,null,"/$raw");
        }

        break;
    } // end jump

    case 'clear':
    {
        $User->clearNotifications();
        $_SESSION['event_notice'] = 'You have cleared your notices.';
        
        redirect('notice');
        
        break;
    } // end clear
    
} // end state switch
?>
