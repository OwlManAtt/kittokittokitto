<?php
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
