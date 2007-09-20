<?php
/**
 * Restock admin. 
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

$ERRORS = array();
$item_type_id = stripinput($_REQUEST['item']['id']);
$item = new ItemType($db);
$item = $item->findOneByItemTypeId($item_type_id);

if($item == null)
{
    $ERRORS[] = 'Invalid item specified.';
}

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
            $restocks = new ShopRestock($db);
            $restocks = $restocks->findByItemTypeId($item->getItemTypeId());

            $RESTOCKS = array();
            foreach($restocks as $restock)
            {
                $RESTOCKS[] = array(
                    'id' => $restock->getShopRestockId(),
                    'item_name' => $restock->getItemName(),
                    'shop_name' => $restock->getShopName(),
                    'frequency' => $restock->getRestockFrequencySeconds(),
                );
            } // end restock reformatter

            if($_SESSION['restock_notice'] != null)
            {
                $renderer->assign('notice',$_SESSION['restock_notice']);
                unset($_SESSION['restock_notice']);
            } 
            
            $ITEM = array(
                'id' => $item->getItemTypeId(),
                'name' => $item->getItemName(),
            );

            $renderer->assign('item',$ITEM);
            $renderer->assign('restocks',$RESTOCKS);
            $renderer->display('admin/items/restocks/list.tpl');

            break;
        } // end default

        case 'delete':
        {
            $ERRORS = array();
            $restock_id = stripinput($_POST['restock']['id']);
            
            $restock = new ShopRestock($db);
            $restock = $restock->findOneByShopRestockId($restock_id);

            if($restock == null)
            {
                $ERRORS[] = 'Invalid restock specified.';
            }

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                // Hold this for the delete message.
                $name = $restock->getShopRestockName();
                $restock->destroy();
                
                $_SESSION['restock_notice'] = "You have deleted the restock.";
                redirect(null,null,"admin-restock/?item[id]={$item->getItemTypeId()}");
            } // end no errors

            break;
        } // end delete

    } // end state switch
} // end item valid
?>
