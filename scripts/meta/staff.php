<?php
/**
 * The staff list. 
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

$GROUPS = array();
$groups = new StaffGroup($db);
$groups = $groups->findByShowStaffGroup('Y','ORDER BY order_by ASC');

foreach($groups as $group)
{
    $USERS = $group->grabUsers();
    
    $DATA = array(
        'group' => array(
            'id' => $group->getStaffGroupId(),
            'name' => $group->getGroupName(),
            'description' => $group->getGroupDescr(),
        ),
        'users' => array(),
    );
    
    foreach($USERS as $user)
    {
        $DATA['users'][] = array(
            'id' => $user->getUserId(),
            'name' => $user->getUserName(),
        );
    } // end user loop
    
    $GROUPS[] = $DATA;
} // end group loop

// This is a *very good* page to use Smarty's caching abilities with!
$renderer->assign('groups',$GROUPS);
$renderer->display('meta/staff.tpl');
?>
