<?php
/**
 * Recipe item definition. 
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
 * @copyright Nicolas Evans, 2009
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
 * @copyright 2009 Nicholas Evans
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

    /**
    * An efficient bill of materials vs inventory check.
    *
    * This will return how many batches the user can produce.
    * 
    * @rdbms-specific
    * @return integer 
    **/
    public function canCraft()
    {
        switch($this->db->phptype)
        {
            case 'mysqli':
            case 'mysql':
            {
                $sql = '
                    SELECT
                        FLOOR(IFNULL(user_item.quantity,0) / item_recipe_material.material_quantity) AS batch_size
                    FROM item_type
                    INNER JOIN item_recipe_material ON (item_type.item_type_id = item_recipe_material.recipe_item_type_id)
                    LEFT JOIN user_item ON (
                        item_recipe_material.material_item_type_id = user_item.item_type_id 
                        AND ? = user_item.user_id
                    )
                    WHERE item_type.item_type_id = ? 
                    ORDER BY batch_size
                    LIMIT 1
                ';

               break;
            } // end mysql

            case 'pgsql':
            {
                $sql = '
                    SELECT
                        FLOOR(COALESCE(user_item.quantity,0) / item_recipe_material.material_quantity) AS batch_size
                    FROM item_type
                    INNER JOIN item_recipe_material ON (item_type.item_type_id = item_recipe_material.recipe_item_type_id)
                    LEFT JOIN user_item ON (
                        item_recipe_material.material_item_type_id = user_item.item_type_id 
                        AND ? = user_item.user_id
                    )
                    WHERE item_type.item_type_id = ? 
                    ORDER BY batch_size
                    LIMIT 1
                ';

                break;
            } // end pgsql

            default:
            {
               throw new ArgumentError('RDBMS unsupported.');

               break;
            } // end default
        } // end db engine switch

        $batch_size = $this->db->getOne($sql,array($this->getUserId(),$this->getItemTypeId()));
        if(PEAR::isError($batch_size))
        {
            throw new SQLError($batch_size->getDebugInfo(),$batch_size->userinfo,10);
        }

        return $batch_size;
    } // end canCraft 

    public function grabProduct()
    {
       $item = new ItemType($this->db);
       $item = $item->findOneByItemTypeId($this->getRecipeCreatedItemTypeId());

       if($item == null)
       {
           throw new ArgumentError('Could not find product item - recipe buildout invalid!');
       }

       return $item;
    } // end grabProduct

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
