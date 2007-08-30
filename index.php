<?php
if($_GET["xml"]=="yes")
{
header('Content-Type: application/xhtml');
}


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
$jump_page = $jump_page->findByPageSlug($slug);
$jump_page = $jump_page[0];
// Done loading page info.

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
	$renderer->display("layout/{$jump_page->getLayoutType()}/header.tpl");

	if($jump_page->hasAccess($access_level) == false)
	{
		if($access_level == 'b&')
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

?>