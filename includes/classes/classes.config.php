<?php
/**
 * Config file that includes all other classes. This is to keep main clean. 
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
 * Abstract classes come first, lest their children cause fatal errors.
 **/
require('getter.class.php');

/**
 * Inflectors.
 **/
require('inflectors/english.class.php');

/**
 * User-related classes.
 **/
require('user/user.class.php');
require('user/notification.class.php');
require('user/message.class.php');
require('user/avatar.class.php');
require('user/datetime_format.class.php');
require('user/timezone.class.php');
require('user/user_staff_group.class.php');
require('user/staff_group.class.php');
require('user/staff_permission.class.php');
require('user/user_online.class.php');

/**
 * Pet-related classes.
 **/
require('pet/pet_specie.class.php');
require('pet/pet_specie_color.class.php');
require('pet/pet_specie_pet_specie_color.class.php');
require('pet/pet.class.php');

/**
 * Item-related classes.
 **/
require('item/item_type.class.php');
require('item/item_class.class.php');
require('item/item.class.php');
require('item/food_item.class.php');
require('item/toy_item.class.php');
require('item/paint_item.class.php');

/**
 * Shop-related classes.
 **/
require('shop/shop.class.php');
require('shop/shop_inventory.class.php');
require('shop/shop_restock.class.php');

/**
 * Board-related classes.
 **/
require('board/board_category.class.php');
require('board/board.class.php');
require('board/board_thread.class.php');
require('board/board_post.class.php');
require('news.class.php');

/**
 * The ghettocron subsystem.
 **/
require('cronjob.class.php');

?>
