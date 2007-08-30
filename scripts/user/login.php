<?php
switch($_REQUEST['state'])
{
	default:
	{
		$renderer->display('user/login.tpl');
		
		break;
	} // end default
	
	case 'process':
	{
		$username = stripinput($_REQUEST['user']['username']);
		$password = stripinput($_REQUEST['user']['password']);
		
		$User = new User($db);
		$User = $User->findOneBy(
			array(
				'user_name' => $username,
                'password_hash' => md5(md5($password)),
			)
		);

		if(is_a($User,'User') == true)
		{
			$User->login();
			redirect('home');
		}
		else
		{
			$ERRORS = array('Incorrect username or password.');
			draw_errors($ERRORS);
		}
		
		break;
	} // end login
} // end state switch

?>
