<?php
/**
 * Map items <=> shops so that they stock. 
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
$restock_id = stripinput($_REQUEST['restock']['id']);

// The item is required.
$item = new ItemType($db);
$item = $item->findOneByItemTypeId($item_type_id);

if($item == null)
{
    $ERRORS[] = 'Invalid item specified.';
}

// The restock is optional.
$restock = new ShopRestock($db);
$restock = $restock->findOneByShopRestockId($restock_id);

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
            if($restock != null)
            {
                $RESTOCK = array(
                    'id' => $restock->getShopRestockId(),
                    'frequency' => $restock->getRestockFrequencySeconds(),
                    'shop_id' => $restock->getShopId(),
                    'minimum_price' => $restock->getMinPrice(),
                    'maximum_price' => $restock->getMaxPrice(),
                    'minimum_quantity' => $restock->getMinQuantity(),
                    'maximum_quantity' => $restock->getMaxQuantity(),
                    'quantity_cap' => $restock->getStoreQuantityCap(),
                );
            } // end edit mode
          
            $ITEM = array(
                'id' => $item->getItemTypeId(),
                'name' => $item->getItemName(),
            );

            $SHOPS = array('' => 'Select one...');
            $shops = new Shop($db);
            $shops = $shops->findBy(array(),'ORDER BY shop.shop_name ASC');
            foreach($shops as $shop)
            {
                $SHOPS[$shop->getShopId()] = $shop->getShopName();
            } // end shop reformat
           
            $renderer->assign('item',$ITEM);
            $renderer->assign('shops',$SHOPS);
            $renderer->assign('restock',$RESTOCK);

            if($restock != null)
            {
                $renderer->display('admin/items/restocks/edit.tpl');
            }
            else
            {
                $renderer->display('admin/items/restocks/new.tpl');
            }

            break;
        } // end default

        case 'save':
        {
            $RESTOCK = array(
                'shop_id' => trim(stripinput($_POST['restock']['shop_id'])),
                'frequency' => trim(stripinput($_POST['restock']['frequency'])),
                'min_price' => trim(stripinput($_POST['restock']['minimum_price'])),
                'max_price' => trim(stripinput($_POST['restock']['maximum_price'])),
                'min_quantity' => trim(stripinput($_POST['restock']['minimum_quantity'])),
                'max_quantity' => trim(stripinput($_POST['restock']['maximum_quantity'])),
                'quantity_cap' => trim(stripinput($_POST['restock']['quantity_cap'])),
            );
            
            // If the group could not be loaded, start making a new one.
            if($restock == null)
            {
                $restock = new ShopRestock($db);
            }
            
            if($RESTOCK['shop_id'] == null)
            {
                $ERRORS[] = 'No shop specified.';
            }
            else
            {
                $shop = new Shop($db);
                $shop = $shop->findOneByShopId($RESTOCK['shop_id']);

                if($shop == null)
                {
                    $ERRORS[] = 'Invalid shop specified.';
                }
            } // end shop id > 0
            
            if($RESTOCK['frequency'] == null)
            {
                $ERRORS[] = 'No frequency specified.';
            }

            if($RESTOCK['min_price'] == null)
            {
                $ERRORS[] = 'No minimum price specified.';
            }

            if($RESTOCK['max_price'] == null)
            {
                $ERRORS[] = 'No maximum price specified.';
            }

            if($RESTOCK['min_quantity'] == null)
            {
                $ERRORS[] = 'No minimum quantity specified.';
            }

            if($RESTOCK['max_quantity'] == null)
            {
                $ERRORS[] = 'No maximum quantity specified.';
            }

            if($RESTOCK['quantity_cap'] == null)
            {
                $ERRORS[] = 'No quantity cap specified.';
            }
         
            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $restock->setItemTypeId($item->getItemTypeId());
                $restock->setShopId($RESTOCK['shop_id']);
                $restock->setRestockFrequencySeconds($RESTOCK['frequency']);
                $restock->setMinPrice($RESTOCK['min_price']);
                $restock->setMaxPrice($RESTOCK['max_price']);
                $restock->setMinQuantity($RESTOCK['min_quantity']);
                $restock->setMaxQuantity($RESTOCK['max_quantity']);
                $restock->setStoreQuantityCap($RESTOCK['quantity_cap']);
                $restock->save();

                $_SESSION['restock_notice'] = "You have saved the restock.";
                redirect(null,null,"admin-restock/?item[id]={$item->getItemTypeId()}");
            } // end no errors

            break;
        } // end save
    } // end state switch
} // end no errors
?>
