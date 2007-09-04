<?php
/**
 * List a user's inventory. 
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

/**
 * It is strongly advised that you do not change the max items per
 * page without understanding the ramifications. The #grabInventory() call
 * loads items via a fast #findBy() and then calls the (slow) Item::factory()
 * on *every single item*. 131 items on the page takes ~5s on an 833mhz box.
 *
 * Your milage may vary, but don't say I didn't warn you.
 **/
$max_items_per_page = 15;

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{
    $page_id = 1;
}

// Where do we slice the record set? (Note: Don't worry about
// LIMIT X,Y starting from zero - that'll be abstracted away).
$start = (($page_id - 1) * $max_items_per_page);
$end = (($page_id - 1) * $max_items_per_page) + $max_items_per_page;

// Generate the pagination. 
$pagination = pagination('items',$User->grabInventorySize(),$max_items_per_page,$page_id);

$ITEMS = $User->grabInventory($start,$end);
$DISPLAY_ITEMS = array();

foreach($ITEMS as $item)
{
    $DISPLAY_ITEMS[] = array(
        'id' => $item->getUserItemId(),
        'image' => $item->getImageUrl(),
        'name' => $item->getItemName(),
    );
} // end items loop

if($_SESSION['item_notice'] != null)
{
    $renderer->assign('notice',$_SESSION['item_notice']);
    unset($_SESSION['item_notice']);
}

$renderer->assign('inventory',$DISPLAY_ITEMS);
$renderer->assign('pagination',$pagination);

$renderer->display('items/list.tpl');
?>
