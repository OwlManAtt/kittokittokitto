<?php
/**
 * Add materials to a recipe. 
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
$ERRORS = array();
$item_id = stripinput($_REQUEST['item']['id']);

$item = new ItemType($db);
$item = $item->findOneByItemTypeId($item_id);
if($item == null)
{
    $ERRORS[] = 'Invalid item specified.';
}
else
{
    if($item->hasMaterials() == false) 
    {
        $ERRORS[] = 'Item specified is not a recipe.';
    }
}


if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{
    switch($_REQUEST['state'])
    {
        default:
        {
            $RECIPE = array(
                'id' => $item->getItemTypeId(),
                'name' => $item->getItemName(),
            );
    
            $renderer->assign('recipe',$RECIPE);
            $renderer->display('admin/items/recipe_add_material.tpl');

            break;
        } // end default

        case 'save':
        {
            if($_REQUEST['quantity'] <= 0)
            {
                $ERRORS[] = 'Quantity must be greater than zero.';
            }

            $material_item = new ItemType($db);
            $material_item = $material_item->findOneByItemTypeId($_REQUEST['item_type_id']);

            if($material_item == null)
            {
                $ERRORS[] = 'Invalid item specified.';
            }
            else
            {
                $material = new RecipeMaterial($db);
                $material = $material->findBy(array(
                    'recipe_item_type_id' => $item->getItemTypeId(),
                    'material_item_type_id' => $material_item->getItemTypeId(),
                ));

                if($material != null)
                {
                    $ERRORS[] = 'This recipe already lists that item as a material.';
                }
            } // end item was found
            
            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $material = new RecipeMaterial($db);
                $material = $material->create(array(
                    'recipe_item_type_id' => $item->getItemTypeId(),
                    'material_item_type_id' => $material_item->getItemTypeId(),
                    'material_quantity' => $_REQUEST['quantity'],
                ));

                $_SESSION['recipe_notice'] = "<strong>{$item->getItemName()}</strong> has been updated.";
                redirect(null,null,"admin-recipe/?item[id]={$item->getItemTypeId()}");
            } // end

            break;
        } // end save
    } // end state switch
} // end no errors
?>
