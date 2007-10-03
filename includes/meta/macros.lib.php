<?php
/**
 * Library of functions that serve as macros. 
 *
 * These functions are intended to only be shorthand for doing common
 * operations. These should be only that - IF THERE IS SQL IN HERE, YOU ARE 
 * PROBABLY DOING SOMETHING WRONG. 
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
 * Perform an HTTP redirect.
 *
 * Depending on which inputs are specified, this will behave
 * differently.
 *
 * Note that script execution is *NOT* stopped by redirecting.
 *
 * @author Nick 'Owl' Evans <owlmanatt@gmail.com>
 * @param string The page slug (from jump pages) to redirect to.
 * @param string Additional parameters to append to the redirect.
 * @param string If raw is given, this is appended to the public_dir 
 *               and used directly.
 * @return void
 **/
function redirect($slug,$params,$raw=null)
{
	global $APP_CONFIG;
	
	$header = "Location: {$APP_CONFIG['public_dir']}/";
	
	if($raw == null)
	{	
		$header .= "$slug/";
		
		if($params != null)
		{
			$header .= "&$params";
		}
	}
	else
	{
		$header .= $raw;
	}
	
	// 301 forces the browser to use the new URL if the user hits refresh.
	// By default, 'Location' sends a 302 - RFC 2616 indicates browsers should
	// use the OLD url (the original one) when refreshing the page.
	header("HTTP/1.1 301 Moved Permanently");
	header($header);
	die;
	
	return null;
} // end redirect

/**
 * Display a standard error template.
 * 
 * The errors should be an array, one per item,
 * and will be shown back through the error.tpl
 * template.
 *
 * @author Nick 'Owl' Evans <owlmanatt@gmail.com>
 * @param mixed List of errors (Usually array, strings will be converted)
 */
function draw_errors($ERRORS)
{
	global $renderer;
    
    // Make the API friendly~desu!
    if(is_array($ERRORS) == false)
    {
        $ERRORS = array($ERRORS);
    }
	
	$renderer->assign('errors',$ERRORS);
	$renderer->display('error.tpl');
} // end draw_errors

/**
 * Strip Input Function, prevents HTML and SQL injection chars in unwanted places.
 *
 * @param string (Potentially) unclean input.
 * @return string Cleaned output
 * @copyright PHP Fusion guys
 **/
function stripinput($text) 
{
    if(get_magic_quotes_gpc() == 1) { stripslashes($text); }
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);

	return $text;
} // end stripinput

/** 
 * Wrap print_r in pre tags so it can be read.
 * 
 * @bestfuckingfunctionever
 * @author Nick 'Owl' Evans <owlmanatt@gmail.com>
 * @param mixed Variable to print_r.
 * @param boolean Return content on true, print it straight out on false.
 * @param string Returns the string if second paramater was true.
 **/
function pprint_r($var,$return=false)
{
	$content = "<pre>\n";
		$content .= print_r($var,true);
	$content .= "</pre>\n";
	
	if($return == true)
	{
		return $content;
	}
	
	print $content;
} // end pprint_r

/**
 * Formats the site's currency into a nice string.
 *
 * This will add commas to the number and append the
 * appropriate form of the site's currency name. 
 * 
 * @author Nick 'Owl' Evans <owlmanatt@gmail.com>
 * @param integer $number 5000 
 * @return string '5,000 golds'
 **/
function format_currency($number)
{
    global $APP_CONFIG;
    
    $word = $APP_CONFIG['currency_name_plural'];
    if($number == 1)
    {
        $word = $APP_CONFIG['currenct_name_singular'];
    }
    
    return number_format($number).' '.strtolower($word);
} // end format_currency

/**
 * Macro to include HTMLPurifier and use it.
 *
 * This does require_once because HTMLPurifier is *large*
 * (like, 100+ files), so it's not really a good use of
 * system resources to parse all of that PHP when it's only
 * going to be used in ten scripts, tops. 
 * 
 * @param string $raw_xhtml Potentially evil HTML.
 * @param bool Automatically turn newlines into paragraphs.
 * @return string Very nice, happy HTML.
 **/
function clean_xhtml($raw_xhtml,$newline_to_p=true)
{
    global $APP_CONFIG;

    if(get_magic_quotes_gpc() == 1) { $raw_xhtml = stripslashes($raw_xhtml); }
    
    require_once('external_lib/HTMLPurifier/HTMLPurifier.auto.php');
    
    $config = HTMLPurifier_Config::createDefault();
    if($APP_CONFIG['htmlpurifier_cachedir'] == null)
    {
        $config->set('Core','DefinitionCache',null);
    }
    else
    {
        $config->set('Cache','SerializerPath',$APP_CONFIG['htmlpurifier_cachedir']);
    }
    
    if($newline_to_p == true)
    {
        $config->set('AutoFormat','AutoParagraph',true);
    }
    
    $config->set('AutoFormat','Linkify',true);

    // This will fail silently if Tidy is not installed and
    // configured correctly. It's a very nice thing to have, though,
    // since people who turn off the rich text editor will look at
    // very ugly HTML without many newlines or tabs.
    $config->set('Output','TidyFormat',true);

    
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($raw_xhtml);
} // end clean_xhtml

function secondsToMinutes($seconds)
{
    if($seconds < 60)
    {
        $text = "$seconds second";
        if($seconds > 1)
        {
            $text .= 's';
        }
    } // end seconds
    else
    {
        $minutes = round(($seconds / 60),1);
        if($minutes <= 1)
        {
            $text = 'minute';
        }
        else
        {
            $text = "$minutes minutes";
        }
    } // end minutes
    
    return $text;
} // end seconds

/**
 * Truncate a string to avoid screen stretching. 
 * 
 * @param sting $word The text to truncate.
 * @param int $length The maxlength allowed before truncation.
 * @param string $end Characters to append indicating truncation.
 * @return string
 **/
function truncate($word,$length=50,$end='. . .')
{
    if(strlen($word) > $length)
    {
        return substr($word,0,$length).$end;
    }

    return $word;
} // end truncate

/**
 * A wrapper for sending emails.
 *
 * This uses PHP's built-in mail(), which calls sendmail. If
 * you want to use another smtp server, check out replacing
 * this function with some PEAR::Mail calls. 
 * 
 * @param string $to The e-mail address to send to.
 * @param string $subject The e-mails subject line.
 * @param string $body The message text.
 * @return bool
 **/
function send_email($to,$subject,$body)
{
    global $APP_CONFIG;
    
    $headers = "From: {$APP_CONFIG['administrator_email']}\r\n";
    $headers .= "Reply-To: {$APP_CONFIG['administrator_email']}\r\n";
    $headers .= "X-Mailer: KittoKittoKitto/PHP/".phpversion();

    return mail($to,$subject,$body,$headers);
} // end send_email

?>
