<?php
/**
 * ItemType admin. 
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
 * @subpackage Items 
 * @version 1.0.0
 **/

switch($_REQUEST['state'])
{
    default:
    {
        $item_types = new ItemType($db);
        $item_types = $item_types->findBy(array(),'ORDER BY item_type.item_name ASC');

        $ITEMS = array();
        foreach($item_types as $item_type)
        {
            $ITEMS[] = array(
                'id' => $item_type->getItemTypeId(),
                'name' => $item_type->getItemName(),
                'type' => $item_type->getClassDescr(),
            );
        } // end item_type reformatter

        if($_SESSION['item_type_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['item_type_notice']);
            unset($_SESSION['item_type_notice']);
        } 

        $renderer->assign('items',$ITEMS);
        $renderer->display('admin/items/list.tpl');

        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        $item_type_id = stripinput($_POST['item']['id']);
        
        $item_type = new ItemType($db);
        $item_type = $item_type->findOneByItemTypeId($item_type_id);

        if($item_type == null)
        {
            $ERRORS[] = 'Invalid item_type specified.';
        }

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            // Hold this for the delete message.
            $name = $item_type->getItemTypeName();
            $item_type->destroy();
            
            $_SESSION['item_notice'] = "You have deleted <strong>$name</strong>.";
            redirect('admin-items');
        } // end no errors

        break;
    } // end delete

} // end state switch
?>
