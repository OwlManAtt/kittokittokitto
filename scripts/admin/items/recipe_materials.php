<?php
/**
 * Manage recipe materials. 
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
            $materials = new RecipeMaterial($db);
            $materials = $materials->findByRecipeItemTypeId($item->getItemTypeId());

            $MATERIALS = array();
            foreach($materials as $material)
            {
                $material_item = $material->grabMaterial();

                $MATERIALS[] = array(
                    'id' => $material->getItemRecipeMaterialId(),
                    'item' => array(
                        $material_item->getItemTypeId(),
                        $material_item->getItemName(),
                    ),
                    'quantity' => $material->getMaterialQuantity(), 
                ); 
            } // end loop
            
            $RECIPE = array(
                'id' => $item->getItemTypeId(),
                'name' => $item->getItemName(),
            );
    
            $renderer->assign('recipe',$RECIPE);
            $renderer->assign('materials',$MATERIALS);

            if($_SESSION['recipe_notice'] != null)
            {   
                $renderer->assign('notice',$_SESSION['recipe_notice']);
                unset($_SESSION['recipe_notice']);
            } // end notice

            $renderer->display('admin/items/recipe_materials.tpl');

            break;
        } // end default

        case 'save':
        {
            // Please note - I am purposefully skipping a check that
            // all items in the recipe were submitted with the form.
            // This is, after all, the admin panel, and adding/removing
            // materials is a proper admin function, so making sure
            // people aren't cheating by fiddling with the form is really
            // a waste of effort.

            $SAVE_MATERIALS = array(); 
            $DELETE_MATERIALS = array();
            foreach($_REQUEST['material'] as $material_update)
            {
                $material = new RecipeMaterial($db);
                $material = $material->findOneByItemRecipeMaterialId($material_update['material_id']);
                if($material == null)
                {
                    $ERRORS[] = 'Invalid material specified.';
                    continue;
                }

                if($material_update['delete'] == true)
                {
                    $DELETE_MATERIALS[] = $material;
                    continue;
                }

                $material_item = new ItemType($db);
                $material_item = $material_item->findOneByItemTypeId($material_update['id']);
                if($material_item == null)
                {
                    $ERRORS[] = 'Invalid item specified!';
                    continue;
                }

                if($material_update['quantity'] <= 0)
                {
                    $ERRORS[] = 'The quantity must be greater than one.';
                }

                $material->setMaterialItemTypeId($material_item->getItemTypeId());
                $material->setMaterialQuantity($material_update['quantity']);
                $SAVE_MATERIALS[] = $material;
            } // end loop

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                foreach($DELETE_MATERIALS as $material)
                {
                    $material->destroy();
                }

                foreach($SAVE_MATERIALS as $material)
                {
                    $material->save();
                } // end save loop

                $_SESSION['recipe_notice'] = "<strong>{$item->getItemName()}</strong> has been updated.";
                redirect(null,null,"admin-recipe/?item[id]={$item->getItemTypeId()}");
            } // end no errors

            break;
        } // end save
    } // end state switch
} // end no errors
?>
