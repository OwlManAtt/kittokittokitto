<?php
/**
 * Permission.
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

/**
 * StaffPermission 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPL v3
 **/
class StaffPermission extends ActiveTable
{
    protected $table_name = 'staff_permission';
    protected $primary_key = 'staff_permission_id';

    /**
     * Grabs the whole list of permissions plus whether or not the group has it.
     *
     * This method is slow.
     * 
     * @rdbms-specific MySQL 4/5-specific due to IF().
     * @param StaffGroup $group_id The group.
     * @param object $db PEAR::DB connector.
     * @return array array(array('permission' => StaffPermission, 
     *                           'group_has' => bool
     *                          )
     *                    )
     **/
    static public function grabPermissionsForGroup(StaffGroup &$group,$db)
    {
        $permissions = new StaffPermission($db);
        $permissions = $permissions->findBy(array());

        $RETURN = array();
        foreach($permissions as $permission)
        {
            $RETURN[] = array(
                'permission' => $permission,
                'group_has' =>  $group->hasPermission($permission->getApiName()),
            );
        } // end permission loop

        return $RETURN;
    } // end grabPermissionsForGroup
    
} // end StaffPermission

?>
