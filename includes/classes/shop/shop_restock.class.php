<?php
/**
 * Shop <=> Item Type mapping.
 *
 * This contains data on how often this item type should be added to
 * the shop's inventory (the price range, etc.).
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
 * An item <=> store restock mapping. Ghettocron deals with the restocking. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Items 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class ShopRestock extends ActiveTable
{
    protected $table_name = 'shop_restock';
    protected $primary_key = 'shop_restock_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'shop_id',
            'foreign_table' => 'shop',
            'foreign_key' => 'shop_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'item_type_id',
            'foreign_table' => 'item_type',
            'foreign_key' => 'item_type_id',
            'join_type' => 'inner',
        ),
    );

    /**
     * Find all pending restocks and call #stock() on them. 
     * 
     * @param mixed $db 
     * @return void
     **/
    static public function processPendingRestocks($db)
    {
        $restocks = new ShopRestock($db);
        $restocks = $restocks->findBy(array(
            array(
                'table' => 'shop_restock',
                'column' => 'unixtime_next_restock',
                'value' => time(),
                'search_type' => '<=',
            ),
        ));
        
        foreach($restocks as $restock)
        {
            $restock->stock();
        } // end restock loop
        
        return true; 
    } // end listPendingRestocks

    public function stock()
    {
        $existing_stock = new ShopInventory($this->db);
        $existing_stock = $existing_stock->findOneBy(array(
            'shop_id' => $this->getShopId(),
            'item_type_id' => $this->getItemTypeId(),
        ));

        if($existing_stock != null)
        {
            // Let's see if we want to add to that stock...
            $new_quantity = rand($this->getMinQuantity(),$this->getMaxQuantity());

            // Prevent an item from stocking infinitely.
            if($new_quantity > $this->getStoreQuantityCap())
            {
                $new_quantity = $this->getStoreQuantityCap();
            } // end cap it off at some quantity

            if($new_quantity > $existing_stock->getQuantity())
            {
                $existing_stock->setQuantity($new_quantity);
                $existing_stock->setPrice(rand($this->getMinPrice(),$this->getMaxPrice()));
                $existing_stock->save();
            } // end add moar
        } // end this item is stocked already
        else
        {
            $new_stock = new ShopInventory($this->db);
            $new_stock->create(array(
                'item_type_id' => $this->getItemTypeId(),
                'shop_id' => $this->getShopId(),
                'quantity' => rand($this->getMinQuantity(),$this->getMaxQuantity()),
                'price' => rand($this->getMinPrice(),$this->getMaxPrice()),
            ));

        } // end NOT existing stock

        // Update the next restock time.
        $this->setUnixtimeNextRestock(($this->getRestockFrequencySeconds() + time()));
        $this->save();
    } // end stock
} // end ShopRestock

?>
