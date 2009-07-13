<?php
/**
 * Base item type definition. 
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
 * An item type (the lookup data for an instance of an item).
 *
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Items 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
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

    public function hasMaterials()
    {
        if($this->getItemClassId() == 4)
        {
            return true;
        }

        return false;
    } // end hasMaterials

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

    /**
     * List a key => value pair of attributes specific to an item type.
     * 
     * This should be defined in any child classes. The return should
     * be:
     *
     * Array (
     *    array(
     *    'name' => 'db_column_name',
     *    'label' => 'Happiness Level',
     *    'type' => 'text',
     *   ),
     * ) 
     *
     * These attributes should be the columns in item_type that are specific
     * to this particular item class. 
     *
     * This method is used for the admin console.
     * 
     * @return array 
     **/
    public function listAttributes()
    {
        // The class name is held in the DB. 
        eval('$item = new '.$this->getPhpClass().'($this->db);');
        $attributes = $item->listAttributes();

        if(is_array($attributes) == true)
        {
            return $attributes;
        }
        
        throw new ArgumentErrors('#listAttributes has not been defined for this item.',10);
    } // end listAttributes
} // end ItemType

?>
