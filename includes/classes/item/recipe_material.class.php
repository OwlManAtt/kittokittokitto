<?php

class RecipeMaterial extends ActiveTable
{
    protected $table_name = 'item_recipe_material';
    protected $primary_key = 'item_recipe_material_id';
    protected $RELATED = array(
        'recipe' => array(
            'class' => 'Recipe_Item',
            'local_key' => 'recipe_item_type_id',
            'foreign_key' => 'item_type_id',
            'one' => true,
        ),
        'material' => array(
            'class' => 'ItemType',
            'local_key' => 'material_item_type_id',
            'foreign_key' => 'item_type_id',
            'one' => true,
        ),
    );

} // end RecipeMaterial

?>
