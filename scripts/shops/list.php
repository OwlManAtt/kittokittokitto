<?php
/**
 *  
 **/

$SHOP_LIST = array();

$shops = new Shop($db);
$shops = $shops->findBy(array());

foreach($shops as $shop)
{
    $SHOP_LIST[] = array(
        'id' => $shop->getShopId(),
        'name' => $shop->getShopName(),
    );
} // end shop list

$renderer->assign('shops',$SHOP_LIST);
$renderer->assign('shops_available',(bool)sizeof($SHOP_LIST));
$renderer->display('shops/list.tpl');
?>
