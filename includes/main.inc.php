<?php
/******************************************************************
* 	  _____ _______       _____   _____ _    _          _   _ 	  *
* 	 / ____|__   __|/\   |  __ \ / ____| |  | |   /\   | \ | |	  *
* 	| (___    | |  /  \  | |__) | |    | |__| |  /  \  |  \| |	  *
* 	 \___ \   | | / /\ \ |  _  /| |    |  __  | / /\ \ | . ` |	  *
* 	 ____) |  | |/ ____ \| | \ \| |____| |  | |/ ____ \| |\  |	  *
* 	|_____/   |_/_/    \_\_|  \_\\_____|_|  |_/_/    \_\_| \_|	  *
* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ *                              
*	              _             _                            	  *
* 	   |\ | __|_ / \   o_|_ _  |_) _._|__|_| __|_ _  _. _| _ 	  *
* 	   | \|(_)|_ \_X|_|| |_(/_ |_)(_| |_ |_|(/_|_(_)(_|(_|_> 	  *
* =============================================================== *
* Base include file.											  *
******************************************************************/
require('includes/config.inc.php');

// Non-critical third-party libraries (Rendering-related, pretty much)
require_once('Smarty/Smarty.class.php');
require_once('Smarty/SmartyPaginate.class.php');

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

// $renderer->debugging = true;

$logged_in = false;
$access_level = 'public';
if(isset($_COOKIE[$APP_CONFIG['cookie_prefix'].'username']) && isset($_COOKIE[$APP_CONFIG['cookie_prefix'].'hash']))
{
	$username = stripinput($_COOKIE[$APP_CONFIG['cookie_prefix'].'username']);
	$password_hash = stripinput($_COOKIE[$APP_CONFIG['cookie_prefix'].'hash']);
	
	// Try logging in.
	$User = new User($db);
	$User = $User->findOneBy(
		array(
			'user_name' => $username,
            'password_hash' => $password_hash,
		)
	);
	
	if(is_a($User,'User') == true)
	{
		$logged_in = true;
		$access_level = $User->getAccessLevel();
		
		$User->setLastIpAddr($_SERVER['REMOTE_ADDR']);
		$User->setLastActivity($User->sysdate());
		$User->save();
        
        // Load the active pet (if any!)
        $Pet = $User->grabActivePet();
	}
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
