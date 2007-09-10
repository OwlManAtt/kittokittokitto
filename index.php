<?php
/**
 * Routing file for all pages.
 *
 * This file is part of 'KittoKittoKitto'.
 *
 * 'KittoKittoKitto' is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 3 of the License,
 * or (at your option) any later version.
 * 
 * 'KittoKittoKitto' is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.  See the GNU General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU General
 * Public License along with 'KittoKittoKitto'; if not,
 * write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author Nicholas 'Owl' Evans <owlmanatt@gmail.com>
 * @copyright Nicolas Evans, 2007
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @package KittoKittoKitto
 * @subpackage Core
 * @version 1.0.0
 **/

session_start();
ob_start();

/**
 * Provides $User, $logged_in, $access_level, etc.
 **/
require('includes/main.inc.php');

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
	header("HTTP/1.1 404 Not Found");
    $renderer->display('http/404.tpl');

    die();
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

        if($User->hasPermission('admin_panel') == true)
        {
            $renderer->assign('show_admin_panel',true);
        }
    } // end user exists
   
    // The list of Spry widgets to load.
    $spry = array();
    $spry['js'] = array(
        'textfieldvalidation/SpryValidationTextField.js',
        'selectvalidation/SpryValidationSelect.js',
        'textareavalidation/SpryValidationTextarea.js',
        'checkboxvalidation/SpryValidationCheckbox.js',
    );
    $spry['css'] = array(
        'textfieldvalidation/SpryValidationTextField.css',
        'selectvalidation/SpryValidationSelect.css',
        'textareavalidation/SpryValidationTextarea.css',
        'checkboxvalidation/SpryValidationCheckbox.css',
    );
    $renderer->assign('spry',$spry);
    
	$renderer->display("layout/{$jump_page->getLayoutType()}/header.tpl");

	if($jump_page->hasAccess($User) == false)
	{
		if($access_level == 'banned')
		{
            header("HTTP/1.1 403 Forbidden");
            $renderer->display('http/403_banned.tpl');
		}
		elseif($access_level == 'public' && $jump_page->getAccessLevel() == 'user')
		{
			$renderer->display('user/login.tpl');
		} // end unregister'd trying to hit page needing registration.
		else
		{
            header("HTTP/1.1 403 Forbidden");
            $renderer->display('http/403.tpl');
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
