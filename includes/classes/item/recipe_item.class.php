<?php
/**
 * Fooditem definition. 
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
 * A pattern. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Items 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class Recipe_Item extends Item
{
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
        array(
            'local_table' => 'item_type',
            'local_key' => 'item_recipe_type_id',
            'foreign_table' => 'item_recipe_type',
            'foreign_key' => 'item_recipe_type_id',
            'join_type' => 'inner',
        ),
    );
    protected $RELATED = array(
        'materials' => array(
            'class' => 'RecipeMaterial',
            'local_table' => 'item_type',
            'local_key' => 'item_type_id',
            'foreign_table' => 'item_recipe_material',
            'foreign_key' => 'recipe_item_type_id',
            'foreign_primary_key' => 'item_recipe_material_id',
        ),
    );

    public function listAttributes()
    {
        $TYPES = array(0 => 'Select one...');

        $types = new ItemRecipeType($this->db);
        $types = $types->findBy(array());
        foreach($types as $type)
        {
            $TYPES[$type->getItemRecipeTypeId()] = $type->getRecipeTypeDescription();   
        }

        return array(
            array(
                'name' => 'item_recipe_type_id',
                'label' => 'Recipe Type',
                'type' => 'select',
                'values' => $TYPES, 
            ),
            array(
                'name' => 'recipe_created_item_type_id',
                'label' => 'Creates Item',
                'type' => 'item',
            ),
            array(
                'name' => 'recipe_batch_quantity',
                'label' => 'Batch Quantity',
                'type' => 'text',
                'validation_type' => 'integer',
            ),

        );
    } // end listAttributes
} // end Food_Item

?>
