<?php
/**
 *  
 **/

$ITEMS = $User->grabInventory();
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
$renderer->display('items/list.tpl');
?>
