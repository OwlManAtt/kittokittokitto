<?php
session_start();
ob_start();
require('includes/main.inc.php'); // <-- Provides $User, $logged_in, $access_level, etc.

// Load page info.
if($_REQUEST['page_slug'] == null)
{
	$_REQUEST['page_slug'] = 'home';
}
$slug = stripinput($_REQUEST['page_slug']);

$jump_page = new JumpPage($db);
$jump_page = $jump_page->findOneByPageSlug($slug);
// Done loading page info.

/*
* =========================
* ==== Ghettocron v3.0 ====
* =========================
* (Red, if you're still out there, I hope 
* you irlol'd hard when you see this. GCv3 is
* dedicated to you.)
*
* Ghettocron is the name of crontab emulation
* from (I *think!*) the original OPG sourcecode 
* (I could be mistaken).
*
* Entries in the cron_tab table will be run if
* it is their due time. Obviously, ghettocron is
* less accurate then proper cron, but I am designing
* for the largest possible market, and everyone may
* not have/know how to use cron.
*/
foreach(Cronjob::listPendingJobs($db) as $job)
{
    $job->run();
} // end cronjob loop

// Display page.
if(is_a($jump_page,'JumpPage') == false)
{
	// TODO - This should be a 404 page.
	die('lol how do i mined for fish? -\_(O-o)_/-');
}
else
{
	$SELF = array(
		'page' => $jump_page,
		'php_self' => $_SERVER['PHP_SELF'],
		'slug' => $jump_page->getPageSlug(),
	);
	$renderer->assign('self',$SELF);
	$renderer->assign('fat','fade-EEAA88');
	
	$renderer->assign('page_title',$jump_page->getPageTitle());
	$renderer->assign('page_html_title',$jump_page->getPageHtmlTitle());
    
    if($jump_page->getIncludeTinymce() == 'Y')
    {
        if($User->getTextareaPreference() == 'tinymce')
        {
            $renderer->assign('include_tinymce',true);
            $renderer->assign('tinymce_theme','advanced');
        }
    } // end include tinyMCE
    
    if(is_object($User) == true)
    {
        $notice = $User->grabNotifications('ORDER BY notification_datetime DESC','LIMIT 1');
        $notice = $notice[0];

        if($notice != null)
        {
            $NOTICE = array(
                'id' => $notice->getUserNotificationId(),
                'url' => $notice->getNotificationUrl(),
                'text' => $notice->getNotificationText(),
            );
            
            $renderer->assign('site_notice',$NOTICE);
        } // end notice exists
    } // end user exists
    
	$renderer->display("layout/{$jump_page->getLayoutType()}/header.tpl");

	if($jump_page->hasAccess($access_level) == false)
	{
		if($access_level == 'banned')
		{
			// 403 B&'d
			// TODO
			print "<p>B&, GTFO FAGGOT.</p>";
		}
		elseif($access_level == 'public' && $jump_page->getAccessLevel() == 'user')
		{
			$renderer->display('user/login.tpl');
		} // end unregister'd trying to hit page needing registration.
		else
		{
			// Show 403
			// TODO
			print "<p>excuse me WTF R U DOIN?</p>";
		} // end user trying to hit mod page
	} // end no access
	else
	{
		include('scripts/'.$jump_page->getPhpScript());
	} // end include script
	
	$renderer->display("layout/{$jump_page->getLayoutType()}/footer.tpl");
} // end else-page found

$db->disconnect();
?>
