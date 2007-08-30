<?php
$ERRORS = array();
$user_id = stripinput($_REQUEST['user_id']);

$user = new User($db);
$user = $user->findOneByUserId($user_id);

if($user == null)
{
    $ERRORS[] = 'Invalid user specified.';
} // end bad user

if(sizeof($ERRORS) > 0)
{
	draw_errors($ERRORS);
}
else
{
	$renderer->assign('profile',$user);
	$renderer->display('user/profile.tpl');
}

?>
