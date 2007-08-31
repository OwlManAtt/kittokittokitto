<?php
/**
 *  
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
if($page_id == null)
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
