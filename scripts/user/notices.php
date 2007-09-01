<?php
/**
 *  
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
