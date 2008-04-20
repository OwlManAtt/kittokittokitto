<?php
/**
 * Edit group and its permissions. 
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
$Y_N = array('Y' => 'Yes','N' => 'No');
$group_id = (int)stripinput($_REQUEST['group']['id']);

$group = new StaffGroup($db);
$group = $group->findOneByStaffGroupId($group_id);

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
            if($group != null)
            {
                $GROUP = array(
                    'id' => $group->getStaffGroupId(),
                    'name' => $group->getGroupName(),
                    'show' => $group->getShowStaffGroup(),
                    'order_by' => $group->getOrderBy(),
                    'description' => $group->getGroupDescr(),
                );

                $CHECKBOXES = array();
                $CHECKBOX_DEFAULTS = array();
                $permissions = StaffPermission::grabPermissionsForGroup($group,$db);
                foreach($permissions as $permission)
                {
                    $CHECKBOXES[$permission['permission']->getStaffPermissionId()] = $permission['permission']->getPermissionName();

                    if($permission['group_has'] == true)
                    {
                        $CHECKBOX_DEFAULTS[] = $permission['permission']->getStaffPermissionId();
                    }
                } // end permisison formatter
            } // end edit mode
            else
            {
                $permissions = new StaffPermission($db);
                $permissions = $permissions->findBy(array());

                $CHECKBOXES = array();
                foreach($permissions as $permission)
                {
                    $CHECKBOXES[$permission->getStaffPermissionId()] = $permission->getPermissionName();
                } // end permission loop
            } // end new mode
           
            array_unshift($Y_N,'Select one...'); 

            $renderer->assign('show_options',$Y_N);
            $renderer->assign('permissions',$CHECKBOXES);
            $renderer->assign('permission_defaults',$CHECKBOX_DEFAULTS);
            $renderer->assign('group',$GROUP);

            if($group != null)
            {
                $renderer->display('admin/permissions/edit.tpl');
            }
            else
            {
                $renderer->display('admin/permissions/new.tpl');
            }

            break;
        } // end default

        case 'save':
        {
            $GROUP = array(
                'name' => trim(stripinput($_POST['group']['name'])),
                'description' => trim(clean_xhtml($_POST['group']['descr'],false)),
                'order_by' => trim(stripinput($_POST['group']['order_by'])),
                'show' => trim(stripinput($_POST['group']['show'])),
            );
            
            // If the group could not be loaded, start making a new one.
            if($group == null)
            {
                $group = new StaffGroup($db);
            }
            
            if($GROUP['name'] == null)
            {
                $ERRORS[] = 'No name specified.';
            }
            elseif(strlen($GROUP['name']) > 50)
            {
                $ERRORS[] = 'There is a maxlength=50 on that field for a reason.';
            }

            if($GROUP['description'] == null)
            {
                $ERRORS[] = 'No description specified.';
            }

            if(in_array($GROUP['show'],array_keys($Y_N)) == false)
            {
                $ERRORS[] = 'Invalid option for show on staff list specified.';
            }

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $group->setGroupName($GROUP['name']);
                $group->setGroupDescr($GROUP['description']);
                $group->setOrderBy($GROUP['order_by']);
                $group->setShowStaffGroup($GROUP['show']);
                $group->save();
                $group->updatePermissions($_POST['group']['permissions']);

                $_SESSION['permission_notice'] = "You have saved <strong>{$group->getGroupName()}</strong>.";
                redirect('admin-permissions');
            } // end no errors

            break;
        } // end save
    } // end state switch
} // end no errors
?>
