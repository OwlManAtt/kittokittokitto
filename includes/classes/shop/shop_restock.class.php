<?php
/**
 *  
 **/

/**
 * An item <=> store restock mapping. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class ShopRestock extends ActiveTable
{
    protected $table_name = 'shop_restock';
    protected $primary_key = 'shop_restock_id';

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
