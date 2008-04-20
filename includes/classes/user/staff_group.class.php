<?php
/**
 * Staff groups. 
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
 * Staff groups. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPL v3
 **/
class StaffGroup extends ActiveTable
{
    protected $table_name = 'staff_group';
    protected $primary_key = 'staff_group_id';
    protected $RELATED = array(
        'users' => array(
            'class' => 'User_StaffGroup',
            'local_key' => 'staff_group_id',
            'foreign_table' => 'user_staff_group',
            'foreign_key' => 'staff_group_id',
            'foreign_primary_key' => 'user_staff_group_id',
        ),
    );

    /**
     * Updates a groups permissions. 
     * 
     * This works by deleting rows from the mapping table where the
     * permission ID is *not* one of the IDs passed, then selecting
     * the permission ID rows into the table where the ID is in the list.
     * The IGNORE on the insert prevents it from throwing an error
     * if the uniqueness constraint fails (ie, it handles mappings that
     * exist already).
     * 
     * @rdbms-specific Very MySQL 4/5-specific.
     * @param array $permission_ids An array of integers.
     * @return bool
     **/
    public function updatePermissions($permission_ids)
    {
        // Delete all is a little different.
        if(sizeof($permission_ids) == 0)
        {
            $result = $this->db->query('DELETE FROM staff_group_staff_permission WHERE staff_group_id = ?',array($this->getStaffGroupId()));
                  
            if(PEAR::isError($result))
            {
                throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
            }

            return true;
        } // end handle delete all
        
        // Handle the delete or add *some*.
        $holders = array_fill(0,(sizeof($permission_ids)),'?');
        $holders = implode(',',$holders);
        
        $result = $this->db->query("
            DELETE FROM staff_group_staff_permission 
            WHERE staff_permission_id NOT IN ($holders) 
            AND staff_group_id = ?
        ",array_merge($permission_ids,array($this->getStaffGroupId())));
        
        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
     
        /*
        * There doesn't seem to be an efficient way to do this in Postgres without
        * using some horrible nested transaction thing that ties us to Postgres 8.x.
        * 
        * So, MySQL does a fast INSERT IGNORE, but for Pgsql I'll do it group by group.
        */
        switch($this->db->phptype)
        {
            case 'mysql':
            case 'mysqli':
            {
                $result = $this->db->query("
                    INSERT IGNORE INTO staff_group_staff_permission 
                    (staff_permission_id,staff_group_id) 
                    SELECT 
                        staff_permission_id, 
                        ? AS group_id 
                    FROM staff_permission 
                    WHERE staff_permission_id IN ($holders)
                ",array_merge(array($this->getStaffGroupId()),$permission_ids));

                if(PEAR::isError($result))
                {
                    throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
                }

                break;
            } // end mysql

            case 'pgsql':
            {
                foreach($permission_ids AS $permission_id)
                {
                    $ARGS = array(
                        'staff_group_id' => $this->getStaffGroupId(),
                        'staff_permission_id' => $permission_id,
                    );

                    $mapping = new StaffGroup_StaffPermission($this->db);
                    $mapping = $mapping->findOneBy($ARGS); 

                    if($mapping == null)
                    {
                        $mapping = new StaffGroup_StaffPermission($this->db);
                        $mapping->create($ARGS); 
                    }
                } // end loop
                
                break;
            } // end pgsql

            default:
            case 'oci8':
            {
                throw new ArgumentError('Query not implemented for RDBMS.');

                break;
            } // end default
         } // end rdbms switch

        return true;
    } // end updatePermissions

    public function hasPermission($name)
    {
        $result = $this->db->getOne('
            SELECT 
                count(*) 
            FROM staff_group_staff_permission 
            INNER JOIN staff_permission ON staff_group_staff_permission.staff_permission_id = staff_permission.staff_permission_id 
            WHERE staff_permission.api_name = ?
            AND staff_group_staff_permission.staff_group_id = ?
        ',array($name,$this->getStaffGroupId()));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
        
        return (bool)$result; 
    } // end hasPermission
    
    /**
     * Delete a staff group and its permission mapping. 
     * 
     * @return bool
     **/
    public function destroy()
    {
        $result = $this->db->query('DELETE FROM staff_group_staff_permission WHERE staff_group_id = ?',array($this->getStaffGroupId()));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
        
        return parent::destroy();
    } // end destroy

    /**
     * Grab an array of users that belong in this group. 
     * 
     * Note that this is done inefficiently so that it can remain 
     * rdbms-agnostic. As per the usual, optimizing it is an excercise
     * left to you. 
     * 
     * @return array An array of User instances. 
     **/
    public function grabUsers()
    {
        $USERS = array();
        
        $mappings = $this->grab('users');
        foreach($mappings as $mapping)
        {
            $user = new User($this->db);
            $user = $user->findOneByUserId($mapping->getUserId());

            if($user != null)
            {
                $USERS[] = $user;
            }
        } // end mapping loop

        return $USERS;
    } // end grabUsers
} // end StaffGroup

?>
