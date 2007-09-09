<?php
/**
 * User group/status manager. 
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

$ERRORS = array();
$STATUSES = array('user' => 'User','banned' => 'Banned');

$user_id = stripinput($_REQUEST['user_id']);
$user = new User($db);
$user = $user->findOneByUserId($user_id);

if($user == null)
{
    $ERRORS[] = 'Invalid user.';
} // end user

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
} 
else
{
    switch($_REQUEST['state'])
    {
        default:
        {
            $USER = array(
                'id' => $user->getUserId(),
                'name' => $user->getUserName(),
                'status' => $user->getAccessLevel(),
                'profile' => $user->getProfile(),
                'signature' => $user->getSignature(),
                'title' => $user->getUserTitle(),
                'groups' => array(),
            );

            $groups = $user->grabStaffGroups();
            foreach($groups as $group)
            {
               $USER['groups'][] = $group->getStaffGroupId();
            }

            $ALL_GROUPS = array();
            $groups = new StaffGroup($db);
            $groups = $groups->findBy(array(),'ORDER BY staff_group.group_name ASC');
            foreach($groups as $group)
            {
                $ALL_GROUPS[$group->getStaffGroupId()] = $group->getGroupName();
            } // end group reformatter

            array_unshift($STATUSES,'Select one...');
            
            if($_SESSION['user_notice'] != null)
            {
                $renderer->assign('notice',$_SESSION['user_notice']);
                unset($_SESSION['user_notice']);
            }

            $renderer->assign('user_info',$USER);
            $renderer->assign('groups',$ALL_GROUPS);
            $renderer->assign('statuses',$STATUSES);
            $renderer->display('admin/user/edit.tpl');

            break;
        } // end default

        case 'save':
        {
            $USER = array(
                'id' => $user->getUserId(), 
                'status' => stripinput(trim($_POST['user']['status'])),
                'title' => stripinput(trim($_POST['user']['title'])),
                'profile' => clean_xhtml(trim($_POST['user']['profile'])),
                'signature' => clean_xhtml(trim($_POST['user']['signature'])),
                'groups' => $_POST['user']['groups'],
            );

            
            if(in_array($USER['status'],$STATUSES) == false)
            {
                $ERRORS[] = 'Invalid user status.';
            }

            if($USER['title'] == null)
            {
                $ERRORS[] = 'Title must be specified.';
            }
            elseif(strlen($USER['title']) > 20)
            {
                $ERRORS[] = 'There is a maxlength=20 on that field for a reason.';
            }

            $user->setAccessLevel($USER['status']);
            $user->setUserTitle($USER['title']);
            $user->setProfile($USER['profile']);
            $user->setSignature($USER['signature']);
            $user->save();
            $user->updateGroups($USER['groups']);

            $_SESSION['user_notice'] = 'Your changes have been saved.';
            redirect(null,null,"admin-users/?user_id={$user->getUserId()}");

            break;
        } // end save
    } // end state switch
} // end no errors
?>
