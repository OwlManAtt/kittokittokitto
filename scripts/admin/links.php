<?php
/**
 * Admin panel homepage.
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

$LINKS = array(
    // Slug => (Permission, Nicename)
    'admin-permissions' => array('manage_permissions','Permission Manager'),
    'admin-pet-colors' => array('manage_pets','Pet Color Manager'),
    'admin-pet-species' => array('manage_pets','Pet Specie Manager'),
    // 'admin-users' => array('manage_users','User Editor'), // Handled via profile.
    'admin-boards' => array('manage_boards','Manage Boards'),
    'admin-shops' => array('manage_shops','Manage Shops'),
    'admin-items' => array('manage_items','Manage Items'),
);

$SHOW = array();
foreach($LINKS as $slug => $details)
{
    if($User->hasPermission($details[0]) == true)
    {
        $SHOW[] = array(
            'slug' => $slug,
            'text' => $details[1],
        );
    }
} // end link loop

$renderer->assign('links',$SHOW);
$renderer->display('admin/links.tpl');

?>
