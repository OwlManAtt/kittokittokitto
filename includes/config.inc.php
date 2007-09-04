<?php
/**
 * Config files; includes all critical libraries & sets paths.
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

switch($_SERVER['RELEASE_MODE'])
{
	case 'DEV':
	{
		$APP_CONFIG = array(
            /**
             * The datasource name for your database.
             **/
			'db_dsn' => array(
				'phptype' => 'mysql', // This can also be run on Oracle (oci).
				'username' => 'kkk',
				'password' => 'lolkkk',
				'hostspec' => 'localhost',
				'database' => 'kkk',
			),
            
            /**
             * The administator's e-mail address. Password recovery notices
             * come from this address, too!
             **/
            'administrator_email' => 'owlmanatt@gmail.com',
            
            /**
             * The absolute path (on the filesystem) to your app. On UNIX,
             * this should look like /something/something/something.
             *
             * If you don't know what this should be, put a file calling
             * phpinfo() into the folder you want KKK to live in and visit
             * it in your browser. Look for the 'SCRIPT_FILENAME' field.
             * The base path is everything *except* for the filename.
             **/
			'base_path' => '/var/www/kittokittokitto',
            
            /**
             * The path to the root of your Smarty template directory.
             * The templates/, templates_c/, cache/, and configs/ folders
             * live in here.
             **/
			'template_path' => '/var/www/kittokittokitto/template',
            
            /*
             * The full URL (no trailing slash) to your site.
             * ie, 'http://demo.kittokittokitto.yasashiisyndicate.org'
             **/
			'public_dir' => 'http://bell.owl.ys/kittokittokitto',
            
            /**
             * If you have many sites at this domain, a cookie prefix
             * is good to ensure there's no overlap between your various
             * apps' cookies.
             **/
			'cookie_prefix' => 'kkk_',

            /**
             * The name of your site.
             **/
            'site_name' => 'KittoKittoKitto',

            /**
             * The name of your site's currency.
             **/
            'currency_name_singular' => 'Gold',
            'currency_name_plural' => 'Golds',

            /**
             * How much money should the user start out with?
             **/
            'starting_funds' => 500,

            /**
             * The maximum number of pets a single user may have.
             **/
            'max_pets' => 2,

            /**
             * The number of seconds a user must wait between creating posts
             * on the forums.
             **/
            'post_interval' => 30,
            
            /**
             * How many seconds does it take for a pet to lose hunger/
             * happiness levels?
             **/
            'hunger_interval' => (3600 * 6), // 6 hours
            
            /**
             * The total number of people that may be specified in a single
             * message's 'To' field.
             *
             * WARNING: If you want to change this, you must also update the
             * variable 'maxTo' in resources/script/yasashii.js to match, lest
             * your compose page be inconsistant with reality!
             **/
            'max_mail_recipients' => 5,
            
            /**
             * The HTMLPurifier cache must be writable by the webserver's user.
             * Set this to null to disable the cache (but you *want* the cache
             * for performance reasons!). Oh, and no trailing slash.
             **/
            'htmlpurifier_cachedir' => '/var/www/kittokittokitto/cache',
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

/**
 * These are mission-critical libraries. Nothing else will function 
 * correctly without these. APHP needs to come before any other classes,
 * otherwise they will cause a fatal error because their parent class is
 * undefined.
 **/
require_once('DB.php');
require_once('aphp/aphp.php');

/**
 * KKK library files.
 **/
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
