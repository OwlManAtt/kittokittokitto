<?php
/**
 * Edit a shop.
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
$shop_id = stripinput($_REQUEST['shop']['id']);

$shop = new Shop($db);
$shop = $shop->findOneByShopId($shop_id);

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
            if($shop != null)
            {
                $SHOP = array(
                    'id' => $shop->getShopId(),
                    'name' => $shop->getShopName(),
                    'image' => $shop->getShopImage(),
                    'welcome_text' => $shop->getWelcomeText(),
                );
            } // end edit mode
            
            $renderer->assign('shop',$SHOP);

            if($shop != null)
            {
                $renderer->display('admin/shops/edit.tpl');
            }
            else
            {
                $renderer->display('admin/shops/new.tpl');
            }

            break;
        } // end default

        case 'save':
        {
            $SHOP = array(
                'name' => trim(stripinput($_POST['shop']['name'])),
                'image' => trim(stripinput($_POST['shop']['image'])),
                'welcome_text' => trim(stripinput($_POST['shop']['welcome_text'])),
            );
            
            // If the group could not be loaded, start making a new one.
            if($shop == null)
            {
                $shop = new Shop($db);
            }
            
            if($SHOP['name'] == null)
            {
                $ERRORS[] = 'No name specified.';
            }
            elseif(strlen($SHOP['name']) > 30)
            {
                $ERRORS[] = 'There is a maxlength=30 on that field for a reason.';
            }

            if($SHOP['image'] == null)
            {
                $ERRORS[] = 'No image specified.';
            }
            elseif(strlen($SHOP['image']) > 200)
            {
                $ERRORS[] = 'There is a maxlength=200 on that field for a reason.';
            }

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $shop->setShopName($SHOP['name']);
                $shop->setShopImage($SHOP['image']);
                $shop->setWelcomeText($SHOP['welcome_text']);
                $shop->save();

                $_SESSION['shop_notice'] = "You have saved <strong>{$shop->getShopName()}</strong>.";
                redirect('admin-shops');
            } // end no errors

            break;
        } // end save
    } // end state switch
} // end no errors
?>
