<?php
/*
* Macros - These functions are intended to only be shorthand for doing common
* operations. These should be only that - IF THERE IS SQL IN HERE, YOU ARE 
* PROBABLY DOIN IT RONG. 
*/

/**
 * Perform an HTTP redirect.
 *
 * Depending on which inputs are specified, this will behave
 * differently.
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
	if (QUOTES_GPC) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);

	return $text;
} // end stripinput

/** 
 * Wrap print_r in <pre> tags so it can be read.
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
?>
