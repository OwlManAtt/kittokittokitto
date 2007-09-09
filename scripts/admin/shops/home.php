<?php
/**
 * Shop admin. 
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
        $shops = new Shop($db);
        $shops = $shops->findBy(array(),'ORDER BY shop.shop_name ASC');

        $SHOPS = array();
        foreach($shops as $shop)
        {
            $SHOPS[] = array(
                'id' => $shop->getShopId(),
                'name' => $shop->getShopName(),
                'image' => $shop->getShopImage(),
                'welcome_text' => $shop->getWelcomeText(),
            );
        } // end shop reformatter

        if($_SESSION['shop_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['shop_notice']);
            unset($_SESSION['shop_notice']);
        } 

        $renderer->assign('shops',$SHOPS);
        $renderer->display('admin/shops/list.tpl');

        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        $shop_id = stripinput($_POST['shop']['id']);
        
        $shop = new Shop($db);
        $shop = $shop->findOneByShopId($shop_id);

        if($shop == null)
        {
            $ERRORS[] = 'Invalid shop specified.';
        }

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            // Hold this for the delete message.
            $name = $shop->getShopName();
            $shop->destroy();
            
            $_SESSION['shop_notice'] = "You have deleted <strong>$name</strong>.";
            redirect('admin-shops');
        } // end no errors

        break;
    } // end delete

} // end state switch
?>
