<?php
/**
 * Password reset screens. 
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

if(is_object($User) == true)
{
    draw_errors('You are already logged in!');
}
else
{
    switch($_REQUEST['state'])
    {
        default:
        {
            if($_SESSION['reset_notice'] != null)
            {
                $renderer->assign('notice',$_SESSION['reset_notice']);
                unset($_SESSION['reset_notice']);
            } // end notice
            
            $renderer->display('user/reset/request.tpl');
            
            break;
        } // end default

        case 'send':
        {
            $ERRORS = array();
            $FORGOT = array(
                'user_name' => stripinput($_POST['forgot']['user_name']),
                'email' => stripinput($_REQUEST['forgot']['email']),
                'code' => stripinput($_REQUEST['forgot']['code']),
            );

            if(!isset($_SESSION['security_code']))
            {
                $ERRORS[] = 'Internal CAPTCHA error. Please report this if it persists.';
            }
            elseif($FORGOT['code'] != $_SESSION['security_code'])
            {
                $ERRORS[] = "Incorrect security code specified.";
            }

            if($FORGOT['user_name'] == null)
            {
                $ERRORS[] = 'User name must be specified.';
            }

            if($FORGOT['email'] == null)
            {
                $ERRORS[] = 'Email must be specified.';
            }
        
            // Try loading the user if there have been no errors.
            if(sizeof($ERRORS) == 0)
            {
                $user = new User($db);
                $user = $user->findOneBy(array(
                    'user_name' => $FORGOT['user_name'],
                    'email' => $FORGOT['email'],
                ));

                if($user == null)
                {
                    $ERRORS[] = 'Invalid username or email address.';
                }
            } // end load user

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $user->setPasswordResetRequested($user->sysdate());
                $user->setPasswordResetConfirm(md5($user->getUserName().$user->getEmail().$user->getPasswordHash().time()));
                $user->save();

                $body = "Dear {$user->getUserName()},\n\n";
                $body .= "A password reset request for your account was submitted by {$_SERVER['REMOTE_ADDR']}. If you would like to proceed with resetting your password, please follow the below link within six hours.\n\n";
                $body .= "{$APP_CONFIG['public_dir']}/reset-password/?state=change&user_id={$user->getUserId()}&confirm={$user->getPasswordResetConfirm()}\n\n";
                $body .= "If you did not request this, or if you have remembered your password, you may ignore this message. No further action will be taken.\n\n";
                $body .= "Sincerely,\n";
                $body .= "The {$APP_CONFIG['site_name']} Team";

                send_email($user->getEmail(),"[{$APP_CONFIG['site_name']}] Password Reset",$body);
                
                $_SESSION['reset_notice'] = 'An email has been sent with a confirmation link. Please follow its directions within six hours to reset your password.';
                redirect('reset-password');
            } // end set stuff and send email

            break;
        } // end send

        case 'change':
        {
            $ERRORS = array();
            $user_id = stripinput($_REQUEST['user_id']);
            $confirm = stripinput($_REQUEST['confirm']);

            if($user_id == null || $confirm == null)
            {
                $ERRORS[] = 'An unknown error occured.';
            }
            else
            {
                $user = new User($db);
                $user = $user->findOneBy(array(
                    'user_id' => $user_id,
                    'password_reset_confirm' => $confirm,
                ));

                if($user == null)
                {
                    $ERRORS[] = 'Invalid user or confirmation specified.';
                }
                else
                {
                    $six_hours = 60 * 60 * 6;
                    if((strtotime($user->getPasswordResetRequested()) + $six_hours) < time()  )
                    {
                        $user->setPasswordResetConfirm('');
                        $user->setPasswordResetRequested(0);
                        $user->save();
                        
                        $ERRORS[] = 'Password reset confirmation code expired.';
                        $_SESSION['reset_notice'] = 'Your reset confirmation code has expired. You must restart the process if you still wish to change your password.'; 
                        redirect('reset-password');
                    } // end failure
                } // end combo exists
            } // end try loading usar
            
            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                switch($_REQUEST['substate'])
                {
                    default:
                    {
                        $renderer->assign('user_id',$user->getUserId());
                        $renderer->assign('confirm',$user->getPasswordResetConfirm());
                        $renderer->display('user/reset/password.tpl');

                        break;
                    } // end default

                    case 'process':
                    {
                        if($_REQUEST['password']['a'] != $_REQUEST['password']['b'])
                        {
                            $ERRORS[] = 'The passwords do not match.';
                        }

                        if(sizeof($ERRORS) > 0)
                        {
                            draw_errors($ERRORS);
                        } 
                        else
                        {
                            $user->setPassword($_REQUEST['password']['a']);
                            $user->setPasswordResetConfirm('');
                            $user->setPasswordResetRequested(0);
                            $user->save();
                            
                            $user->login();
                            
                            $_SESSION['pref_notice'] = 'You have successfully updated your password.';
                            redirect('preferences');
                        } // end do it
        
                        break;
                    } // end process case
                } // end substate switch
            } // end no errors
            
            break;
        } // end change
    } // end state switch
} // end user is not logged in
?>
