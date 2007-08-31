<?php
/**
 *  
 **/

class Shop extends ActiveTable
{
    protected $table_name = 'shop';
    protected $primary_key = 'shop_id';
    protected $RELATED = array(
        'stock' => array(
            'class' => 'ShopInventory',
            'local_key' => 'shop_id',
            'foreign_key' => 'shop_id',
        ),
    );

    /**
     * Get the full URL to the shopkeeper image.
     * 
     * @return string URL 
     **/
    public function getImageUrl()
    {
        global $APP_CONFIG;
        
        return "{$APP_CONFIG['public_dir']}/resources/shopkeepers/{$this->getShopImage()}";
    } // end getImageUrl
} // end Shop

?>
