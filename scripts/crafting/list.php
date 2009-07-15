<?php
/**
 * Recipe list. 
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
 * @copyright Nicolas Evans, 2009
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @package Kitto_Kitto_Kitto
 * @subpackage Items
 * @version 1.0.0
 **/

// Rows on this page do far more queries than the inventory page.
// Thus, a substantially lower limit.
$max_items_per_page = 5;

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{   
    $page_id = 1;
}

// Where do we slice the record set?
$start = (($page_id - 1) * $max_items_per_page);
$end = (($page_id - 1) * $max_items_per_page) + $max_items_per_page;

$pagination = pagination('crafting',$User->grabRecipeSize(),$max_items_per_page,$page_id);

$items = $User->grabRecipes($start,$end);

$ITEMS = array();
foreach($items as $item)
{
    $batches = $item->canCraft();
    $product = $item->grabProduct();
    $craftable = true;
    if($product->getUniqueItem() == 'Y')
    {
        $batches = ($batches > 1 ? 1 : 0);

        if($User->hasItem($product->getItemTypeId()) == true)
        {
            $craftable = false;
        }
    } // end unqiue check

    $ITEMS[] = array(
        'id' => $item->getUserItemId(),
        'type' => $item->getRecipeTypeDescription(),
        'name' => $item->getItemName(),
        'description' => $item->getItemDescr(),
        'image' => $item->getImageUrl(),
        'can_make_batch' => ($batches > 0 ? true : false),
        'max_batch' => $batches,
        'craftable' => $craftable, // locked due to uniqueness.
    );
} // end loop

if($_SESSION['craft_notice'] != null)
{
    $renderer->assign('notice',$_SESSION['craft_notice']);
    unset($_SESSION['craft_notice']);
}

$renderer->assign('inventory',$ITEMS);
$renderer->assign('pagination',$pagination);

$renderer->display('crafting/list.tpl');
?>
