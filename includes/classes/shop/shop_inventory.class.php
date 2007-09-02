<?php
/**
 *  
 **/

class ShopInventory extends ActiveTable
{
    protected $table_name = 'shop_inventory';
    protected $primary_key = 'shop_inventory_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'item_type_id',
            'foreign_table' => 'item_type',
            'foreign_key' => 'item_type_id',
            'join_type' => 'inner',
        ),
        array(
            'local_table' => 'item_type',
            'local_key' => 'item_class_id',
            'foreign_table' => 'item_class',
            'foreign_key' => 'item_class_id',
            'join_type' => 'inner',
        ),
    );

    /**
     * Update/delete the stock row for a purchase. 
     * 
     * @param integer $quantity The quantity of the stock that has been bought.
     * @return bool 
     **/
    public function sell($quantity)
    {
        // Subtract from quantity if this isn't the whole supply.
        if($quantity < $this->getQuantity())
        {
            $this->setQuantity(($this->getQuantity() - $quantity));
            $this->save();
        }
        else // Otherwise, delete the row (0 supply = don't show it)
        {
            $this->destroy();
        }
        
        return true;
    } // end sell

   /**
     * Return the full URL to the item's image.
     * 
     * @return string URL 
     **/
    public function getImageUrl()
    {
        global $APP_CONFIG;
        
        return "{$APP_CONFIG['public_dir']}/resources/items/{$this->getRelativeImageDir()}/{$this->getItemImage()}";
    } // end getImageUrl

} // end ShopInventory

?>
