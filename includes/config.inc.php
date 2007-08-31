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
* Configuration file; include critical libraries (non-rendering   *
* stuff), pick the appropriate paths based on environment, etc.	  *
******************************************************************/

switch($_SERVER['RELEASE_MODE'])
{
	case 'DEV':
	{
		$APP_CONFIG = array(
			'db_dsn' => array(
				'phptype' => 'mysql', // This can also be run on Oracle (oci).
				'username' => 'kkk',
				'password' => 'lolkkk',
				'hostspec' => 'localhost',
				'database' => 'kkk',
			),
			'base_path' => '/var/www/kittokittokitto',
			'template_path' => '/var/www/kittokittokitto/template',
			'public_dir' => 'http://bell.owl.ys/kittokittokitto',
			'cookie_prefix' => 'kkk_',
            'site_name' => 'KittoKittoKitto',
            'currency_name_singular' => 'Gold',
            'currency_name_plural' => 'Golds',
            'starting_funds' => 500,
            'max_pets' => 2,
		);
		
		break;
	} // end dev
	
	case 'PROD':
	{
		$APP_CONFIG = array(
			'db_dsn' => array(
				'phptype' => '',
				'username' => '',
				'password' => '',
				'hostspec' => '',
				'database' => '',
			),
			'base_path' => '',
			'template_path' => '',
			'public_dir' => '',
			'cookie_prefix' => 'kkk_',
		);
	
		break;
	} // end prod

	default:
	{
		die("RELEASE_MODE '{$_SERVER['RELEASE_MODE']}' unrecognized; CANNOT PROCEED.");
		
		break;
	} // end default

} // end release mode switch

// These are mission-critical libraries. Nothing else will function correctly without these.
require_once('DB.php');
require_once('aphp/aphp.php');

// These are our libs.
require('includes/meta/macros.lib.php');
require('includes/meta/jump_page.class.php');
require('includes/meta/pagination.php');
require('includes/classes/classes.config.php');
require('includes/cronjobs/cronjobs.config.php');

$DB_OPTIONS = array(
	'debug' => 2,
	'portability' => DB_PORTABILITY_ALL,
);

$db = DB::connect($APP_CONFIG['db_dsn'],$DB_OPTIONS);
if (PEAR::isError($db)) 
{
    die("An error occured when attempting to connect to the database. Oops!");
}
$db->setFetchMode(DB_FETCHMODE_ASSOC);

?>
