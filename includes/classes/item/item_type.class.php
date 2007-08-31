<?php
/**
 *  
 **/

/**
 * An item type (the lookup data for an instance of an item).
 *
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class ItemType extends ActiveTable
{
    protected $table_name = 'item_type';
    protected $primary_key = 'item_type_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'item_class_id',
            'foreign_table' => 'item_class',
            'foreign_key' => 'item_class_id',
            'join_type' => 'inner',
        ),
    );

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
} // end ItemType

?>
