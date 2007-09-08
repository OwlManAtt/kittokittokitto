<?php
/**
 * Group <=> permission mapping, group AED.
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

switch($_REQUEST['state'])
{
    default:
    {
        $GROUPS = array();
        $groups = new StaffGroup($db);
        $groups = $groups->findBy(array(),'ORDER BY staff_group.group_name ASC');

        foreach($groups as $group)
        {
            $GROUPS[] = array(
                'id' => $group->getStaffGroupId(),
                'name' => $group->getGroupName(),
            );
        } // end group reformatting loop

        if($_SESSION['permission_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['permission_notice']);
            unset($_SESSION['notice']);
        } 

        // We can presume that there's never a case where there are 0 groups - 
        // if that is so, the user cannot logically get to this page.
        $renderer->assign('groups',$GROUPS);
        $renderer->display('admin/permissions/list.tpl');

        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        $group_id = stripinput($_POST['group']['id']);
        
        $group = new StaffGroup($db);
        $group = $group->findOneByStaffGroupId($group_id);

        if($group == null)
        {
            $ERRORS[] = 'Invalid group specified.';
        }

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            // Hold this for the delete message.
            $name = $group->getGroupName();
            $group->destroy();
            
            $_SESSION['permission_notice'] = "You have deleted the <strong>$name</strong> group.";
            redirect('admin-permissions');
        } // end no errors

        break;
    } // end delete
} // end state switch
?>
