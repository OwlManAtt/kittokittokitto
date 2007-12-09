<?php
/**
 * Board category classfile. 
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

/**
 * Board Category
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class BoardCategory extends ActiveTable
{
    protected $table_name = 'board_category';
    protected $primary_key = 'board_category_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'required_permission_id',
            'foreign_table' => 'staff_permission',
            'foreign_key' => 'staff_permission_id',
            'join_type' => 'left',
        ),
    );
    protected $RELATED = array(
        'boards' => array(
            'class' => 'Board',
            'local_key' => 'board_category_id',
            'foreign_key' => 'board_category_id',
        ),
    );
   
    /**
     * Determine whether or not a user has access to this board.
     * 
     * @param User $user 
     * @return bool 
     **/
    public function hasAccess(User $user)
    {
        // If there is a required permission on this this, check it. 
        if($this->getApiName() == null)
        {
            return true;
        }
        elseif($user->hasPermission($this->getApiName()) == true)
        {
            return true; 
        }

        return false;
    } // end hasAccess
} // end BoardCategory
?>
