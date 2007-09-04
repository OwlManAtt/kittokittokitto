<?php
/**
 * Lists a shop's inventory and lets the user purchanse items. 
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

$ERRORS = array();
$id = stripinput($_REQUEST['shop_id']);

$shop = new Shop($db);
$shop = $shop->findOneByShopId($id);

if($shop == null)
{
    draw_errors('Invalid shop ID specified.');
}
else
{
    switch($_REQUEST['state'])
    {
        default:
        {
            $ITEMS = array();

            $stock = $shop->grabStock();
            foreach($stock as $item)
            {
                $ITEMS[] = array(
                    'id' => $item->getShopInventoryId(),
                    'name' => $item->getItemName(),
                    'image' => $item->getImageUrl(),
                    'price' => $item->getPrice(),
                    'quantity' => $item->getQuantity(),
                );
            } // end stock loop

            $SHOP = array(
                'id' => $shop->getShopId(),
                'name' => $shop->getShopName(),
                'image' => $shop->getImageUrl(),
                'welcome' => $shop->getWelcomeText(), 
            );

            if($_SESSION['shop_notice'] != null)
            {
                $renderer->assign('notice',$_SESSION['shop_notice']);
                unset($_SESSION['shop_notice']);
            } // end shop notice

            $renderer->assign('items',$ITEMS);
            $renderer->assign('shop',$SHOP);
            $renderer->display('shops/shop.tpl');

            break;
        } // end default

        case 'buy':
        {
            $ERRORS = array();
            $stock_id = stripinput($_REQUEST['stock_id']);
            $quantity = trim(stripinput($_REQUEST['item']['quantity']));
            
            /*
            * For the regexp-inept: ^ and $ are anchors to the beginning
            * and end of the line. [0-9] specifies a range of characters,
            * zero through nine. + is a quantifier requiring one or more
            * of the previous character (our 0-9 range) to be present one
            * or more times.
            *
            * This assures us that the field *only* contains an unsigned
            * integer (positive number, kids - don't let that highschool
            * edumacation be forgotten so quickly!).
            */
            if(preg_match('/^[0-9]+$/',$quantity)== false)
            {
                $ERRORS[] = 'You must enter a number of items to buy.';
            }  // end number check
            elseif($quantity == 0)
            {
                $ERRORS[] = 'No quantity specified.';
            }
            
            $stock = new ShopInventory($db); 
            $stock = $stock->findOneByShopInventoryId($stock_id);

            if($stock == null)
            {
                $ERRORS[] = 'That item is not in stock.';
            }
            else
            {
                // Eh? This shouldn't ever happen...clean the records
                // up and show an error.
                if($stock->getQuantity() == 0)
                {
                    $stock->destroy();
                    $ERRORS[] = 'That item is not in stock.';
                } // end odd case
            } // end stock entry exists

            if($stock->getQuantity() < $quantity)
            {
                $ERRORS[] = "The shop does not have that many <strong>{$stock->getItemName()}</strong> in stock.";
            }
            elseif(($stock->getPrice() * $quantity) > $User->getCurrency())
            {
                $ERRORS[] = 'You cannot afford that purchase.';
            }

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                // Take away their money.
                $total = $stock->getPrice() * $quantity;
                $User->subtractCurrency($total);
                
                // Add the items to the user. 
                for($i=0;$i<$quantity;$i++)
                {
                    $item = new Item($db);
                    $item->create(array(
                        'user_id' => $User->getUserId(),
                        'item_type_id' => $stock->getItemTypeId(),
                    ));
                } // end add item loop
                
                // Remove the stock from the shope.
                $item_name = $stock->getItemName(); // store this for later.
                $stock->sell($quantity);
                
                // Stock could #destroy() itself during the #sell(), so don't 
                // use it anymore.
                unset($stock); 
               
                $_SESSION['shop_notice'] = "You have purchased ".number_format($quantity)." <strong>{$item_name}</strong> for ".format_currency($total)."!";

                redirect("shop/{$shop->getShopId()}");
            } // end do purchase

            break;
        } // end purchase
    } // end switch
} // end shop exists
?>
