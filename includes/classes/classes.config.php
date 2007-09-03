<?php
/**
 *  
 **/

// Abstract classes.
require('getter.class.php');

// User stuff.
require('user/user.class.php');
require('user/notification.class.php');

// Pet stuff.
require('pet/pet_specie.class.php');
require('pet/pet_specie_color.class.php');
require('pet/pet_specie_pet_specie_color.class.php');
require('pet/pet.class.php');

// Item stuff.
require('item/item_type.class.php');
require('item/item.class.php');
require('item/food_item.class.php');
require('item/toy_item.class.php');
require('item/paint_item.class.php');

// Shop stuff.
require('shop/shop.class.php');
require('shop/shop_inventory.class.php');
require('shop/shop_restock.class.php');

// Board stuff.
require('board/board.class.php');
require('board/board_thread.class.php');
require('board/board_post.class.php');
require('news.class.php');

// Ghettocron.
require('cronjob.class.php');

?>
