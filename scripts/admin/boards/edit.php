<?php
/**
 * Edit a board.
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
 * @subpackage Board 
 * @version 1.0.0
 **/

$ERRORS = array();
$board_id = stripinput($_REQUEST['board']['id']);

$Y_N = array('N' => 'No','Y' => 'Yes');

$board = new Board($db);
$board = $board->findOneByBoardId($board_id);

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
            if($board != null)
            {
                $BOARD = array(
                    'id' => $board->getBoardId(),
                    'name' => $board->getBoardName(),
                    'description' => $board->getBoardDescr(),
                    'locked' => $board->get('board_locked'), // see getBoardLocked for why
                    'news_source' => $board->getNewsSource(),
                    'required_permission_id' => $board->getRequiredPermissionId(),
                    'order_by' => $board->getOrderBy(),
                );
            } // end edit mode
           
            $PERMISSIONS = array('' => 'None (Public Board)',);
            $permissions = new StaffPermission($db);
            $permissions = $permissions->findBy(array(),'ORDER BY staff_permission.permission_name');
            foreach($permissions as $permission)
            {
                $PERMISSIONS[$permission->getStaffPermissionId()] = $permission->getPermissionName();
            } // end permission reformat
            
            array_unshift($Y_N,'Select one...');
            $renderer->assign('y_n',$Y_N);
            $renderer->assign('permissions',$PERMISSIONS);
            $renderer->assign('board',$BOARD);

            if($board != null)
            {
                $renderer->display('admin/boards/edit.tpl');
            }
            else
            {
                $renderer->display('admin/boards/new.tpl');
            }

            break;
        } // end default

        case 'save':
        {
            $BOARD = array(
                'name' => trim(stripinput($_POST['board']['name'])),
                'description' => trim(stripinput($_POST['board']['description'])),
                'locked' => trim(stripinput($_POST['board']['locked'])),
                'news_source' => trim(stripinput($_POST['board']['news_source'])),
                'order_by' => trim(stripinput($_POST['board']['order_by'])),
                'required_permission_id' => trim(stripinput($_POST['board']['required_permission_id'])),
            );
            
            // If the group could not be loaded, start making a new one.
            if($board == null)
            {
                $board = new Board($db);
            }
            
            if($BOARD['name'] == null)
            {
                $ERRORS[] = 'No name specified.';
            }
            elseif(strlen($BOARD['name']) > 100)
            {
                $ERRORS[] = 'There is a maxlength=100 on that field for a reason.';
            }

            if($BOARD['description'] == null)
            {
                $ERRORS[] = 'You must specify a description.';
            }
            elseif(strlen($BOARD['description']) > 255)
            {
                $ERRORS[] = 'There is a maxlength=255 on that field for a reason.';
            }

            if($BOARD['locked'] == null)
            {
                $ERRORS[] = 'You must specify whether or not this board is locked.';
            }
            elseif(in_array($BOARD['locked'],array_keys($Y_N)) == false)
            {
                $ERRORS[] = 'Invalid locked status specified.';
            }
            
            if($BOARD['news_source'] == null)
            {
                $ERRORS[] = 'You must specify whether or not this board is a news source.';
            }
            elseif(in_array($BOARD['news_source'],array_keys($Y_N)) == false)
            {
                $ERRORS[] = 'Invalid news source status specified.';
            }

            $permission_id = 0; // for saving
            if($BOARD['required_permission_id'] != null)
            {
                $permission = new StaffPermission($db);
                $permission = $permission->findOneByStaffPermissionId($BOARD['required_permission_id']);

                if($permission == null)
                {
                    $ERRORS[] = 'Invalid permission specified.';
                }
                else
                {
                    $permission_id = $permission->getStaffPermissionId();
                }
            } // end perm specified; check it
           
            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $board->setBoardName($BOARD['name']);
                $board->setBoardDescr($BOARD['description']);
                $board->setBoardLocked($BOARD['locked']);
                $board->setNewsSource($BOARD['news_source']);
                $board->setOrderBy($BOARD['order_by']);
                $board->setRequiredPermissionId($permission_id);
                $board->save();

                $_SESSION['board_notice'] = "You have saved <strong>{$board->getBoardName()}</strong>.";
                redirect('admin-boards');
            } // end no errors

            break;
        } // end save
    } // end state switch
} // end no errors
?>
