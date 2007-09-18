<?php
/**
 * Base include file. This aggregates config and does some set-up work. 
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

/**
 * This gives us most of the other libraries.
 **/
require('includes/config.inc.php');

/**
 * Non-critical third-party libraries (Rendering-related, pretty much).
 **/
require_once('Smarty/Smarty.class.php');

/*
* == ADDITIONAL INCLUDE ==
* HTMLPurified is require_once()'d, but not in main - see the clean_xhtml
* macro for details on why..
*/

// Deploy the renderer.
$renderer = new Smarty();

$renderer->template_dir = $APP_CONFIG['template_path'].'/templates/';
$renderer->compile_dir = $APP_CONFIG['template_path'].'/templates_c/';
$renderer->config_dir = $APP_CONFIG['template_path'].'/configs/';
$renderer->cache_dir = $APP_CONFIG['template_path'].'/cache/';

$DISPLAY_SETTINGS = array(
	'public_dir' => $APP_CONFIG['public_dir'],
);
$renderer->assign('display_settings',$DISPLAY_SETTINGS);

$renderer->assign('site_name',$APP_CONFIG['site_name']);
$renderer->assign('currency_singular',$APP_CONFIG['currency_name_singular']);
$renderer->assign('currency_plural',$APP_CONFIG['currency_name_plural']);
$renderer->assign('admin_email',$APP_CONFIG['administrator_email']);

// $renderer->debugging = true;

$logged_in = false;
$access_level = 'public';
if(isset($_COOKIE[$APP_CONFIG['cookie_prefix'].'username']) && isset($_COOKIE[$APP_CONFIG['cookie_prefix'].'hash']))
{
	$username = stripinput($_COOKIE[$APP_CONFIG['cookie_prefix'].'username']);
	$password_hash = stripinput($_COOKIE[$APP_CONFIG['cookie_prefix'].'hash']);
	
	// Try logging in.
	$User = new User($db);
	$User = $User->findOneByUserName($username);
	
	if(is_a($User,'User') == true)
	{
        if($User->checkSessionPassword($password_hash) == true)
        {
            $logged_in = true;
            $access_level = $User->getAccessLevel();
            
            $User->setLastIpAddr($_SERVER['REMOTE_ADDR']);
            $User->setLastActivity($User->sysdate());
            $User->save();
            
            // Load the active pet (if any!)
            $Pet = $User->grabActivePet();

            if($Pet != null)
            {
                $Pet->doDecrement(); // Make it hungry.
            }
        
            $renderer->assign('editor',$User->getTextareaPreference());
        } // end password is right
        else
        {
            $User->logout();
            unset($User);
        }
	} // user exists
	else
	{
		$User = null;
		$access_level = 'public';
	}
	
    // Give these to Smarty.
	$renderer->assign('user',$User);
	$renderer->assign('active_pet',$Pet);
} // end if cookies are set

$renderer->assign('logged_in',$logged_in);
?>
