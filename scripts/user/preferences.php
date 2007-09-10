<?php
/**
 * Allows a user to change their password, e-mail, localization settings,
 * and other preferences. 
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

$GENDER = array('male' => 'Male', 'female' => 'Female');
$AGE = array();
for($i=5;$i<70;$i++)
{
    $AGE[$i] = $i;
}
$EDITORS = array('tinymce' => 'Rich Text Editor','plain' => 'Plain Textbox');

switch($_REQUEST['state'])
{
    default:
    {
        $PREFS = array(
            'gender' => $User->getGender(),
            'age' => $User->getAge(),
            'email' => $User->getEmail(),
            'editor' => $User->getTextareaPreference(),
            'profile' => $User->getProfile(),
            'signature' => $User->getSignature(),
            'avatar_id' => $User->getAvatarImage(),
            'avatar_url' => $User->getAvatarUrl(),
            'timezone_id' => $User->getTimezoneId(),
            'datetime_format_id' => $User->getDatetimeFormatId(),
        );

        // If someone hit here with defaults from the back button,
        // give those values precedence.
        if(is_array($_POST['default']) == true)
        {
            foreach($_POST['default'] as $key => $value)
            {
                // Stripinput on normal fields, xhtml cleaner for the rest.
                if(in_array($key,array('profile','signature')) == false)
                {
                    $PREFS[$key] = stripinput($value);
                }
                else
                {
                    $PREFS[$key] = clean_xhtml($value);
                }
            } // end mog-into-prefs loop
        } // end defaults

        // Get the list of avatars.
        $AVATARS = array('' => 'None...');
        $avatars = new Avatar($db);
        $avatars = $avatars->findByActive('Y');

        foreach($avatars as $avatar)
        {
            $AVATARS[$avatar->getAvatarImage()] = $avatar->getAvatarName();
        } // end avatar mogger

        // Build lists for our temporal friends.
        $TIMEZONES = array();
        $timezones = new Timezone($db);
        $timezones = $timezones->findBy(array(),'ORDER BY order_by, timezone_continent, timezone_short_name ASC');
        foreach($timezones as $timezone)
        {
            $TIMEZONES[$timezone->getTimezoneId()] = $timezone->getTimezoneName();
        } // end timezone loop

        $DATETIME_FORMATS = array();
        $formats = new DatetimeFormat($db);
        $formats = $formats->findBy(array());
        foreach($formats as $format)
        {
            $DATETIME_FORMATS[$format->getDatetimeFormatId()] = $format->getDatetimeFormatName();
        } // end datetime format loop
        
        if($_SESSION['pref_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['pref_notice']);
            unset($_SESSION['pref_notice']);
        }
        
        $renderer->assign('avatars',$AVATARS);
        $renderer->assign('timezones',$TIMEZONES);
        $renderer->assign('datetime_formats',$DATETIME_FORMATS);
        $renderer->assign('genders',$GENDER);
        $renderer->assign('ages',$AGE);
        $renderer->assign('editors',$EDITORS);
        $renderer->assign('prefs',$PREFS);
        $renderer->display('user/preferences/preferences.tpl');

        break;
    } // end default

    case 'save_account':
    {
        $ERRORS = array();
        $PASSWORD = array(
            'old' => $_POST['password']['old'],
            'new' => $_POST['password']['a'],
            'new_confirm' => $_POST['password']['b'],
            'email' => stripinput($_POST['user']['email']),
        );

        if(md5(md5($PASSWORD['old'])) != $User->getPasswordHash())
        {
            $ERRORS[] = 'The old password you specified is incorrect.';
        }
            
        if($PASSWORD['new'] != $PASSWORD['new_confirm'])
        {
            $ERRORS[] = 'The two new passwords you specified did not match.';
        }

        if(preg_match('/^[a-z0-9_+.]{1,64}@([a-z0-9-.]*){1,}\.[a-z]{1,5}$/i',$PASSWORD['email']) == false)
        {
            $ERRORS[] = 'Invalid e-mail address specified.';
        }
        
        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            $User->setPassword($PASSWORD['new']);
            $User->setEmail($PASSWORD['email']);
            $User->save();

            // Refresh the cookie with the new password.
            $User->logout();
            $User->login();

            $_SESSION['pref_notice'] = 'Your settings have been updated.';
            redirect('preferences');
        } // end no errors

        break;
    } // end save account

    case 'save_preferences':
    {
        $ERRORS = array();
        
        $USER = array(
            'gender' => stripinput($_POST['user']['gender']),
            'age' => stripinput($_POST['user']['age']),
            'editor' => stripinput($_POST['user']['editor']),
            'profile' => clean_xhtml($_POST['user']['profile']),
            'signature' => clean_xhtml($_POST['user']['signature']),
            'avatar_image' => stripinput($_POST['user']['avatar']),
            'datetime_format' => stripinput($_POST['user']['datetime_format']),
            'timezone' => stripinput($_POST['user']['timezone']),
        );
        
        if(in_array($USER['gender'],array_keys($GENDER)) == false)
        {
            $ERRORS[] = 'Invalid gender specified.';
        }

        if(in_array($USER['age'],$AGE) == false)
        {
            $ERRORS[] = 'Invalid age specified.';
        }

        if(in_array($USER['editor'],array_keys($EDITORS)) == false)
        {
            $ERRORS[] = 'Invalid editor specified.';
        }

        if($USER['avatar_image'] == null)
        {
            $avatar_id = 0;
        }
        else
        {
            $avatar = new Avatar($db);
            $avatar = $avatar->findOneByAvatarImage($USER['avatar_image']);
            
            if($avatar == null)
            {
                $ERRORS[] = 'Invalid avatar specified.';
            }
            else
            {
                $avatar_id = $avatar->getAvatarId();
            }
        } // end avatar specified

        $datetime_format = new DatetimeFormat($db);
        $datetime_format = $datetime_format->findOneByDatetimeFormatId($USER['datetime_format']);

        if($datetime_format == null)
        {
            $ERRORS[] = 'Invalid date/time format specified.';
        }
         
        $timezone = new Timezone($db);
        $timezone = $timezone->findOneByTimezoneId($USER['timezone']);

        if($timezone == null)
        {
            $ERRORS[] = 'Invalid timezone specified.';
        }
        
        
        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);

            $renderer->assign('info',$USER);
            $renderer->display('user/preferences/pref_errors_back.tpl');
        }
        else
        {
            $User->setAge($USER['age']);
            $User->setGender($USER['gender']);
            $User->setProfile($USER['profile']);
            $User->setSignature($USER['signature']);
            $User->setTextareaPreference($USER['editor']); 
            $User->setAvatarId($avatar_id);
            $User->setDatetimeFormatId($datetime_format->getDatetimeFormatId());
            $User->setTimezoneId($timezone->getTimezoneId());
            $User->save();
            
            $_SESSION['pref_notice'] = 'Your preferences have been updated.';
            redirect('preferences');
        } // end do save
        
        break; 
    } // end save preferences
} // end switch
?>
