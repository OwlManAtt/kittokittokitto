<?php
/**
 * Crafting screens - recipe => useful item. 
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
$user_item_type_id = stripinput($_REQUEST['id']);

if($user_item_type_id == null)
{
    $ERRORS[] = 'No item ID specified.';
}
else
{
    $item = Item::factory($user_item_type_id,$db);
    if($item == null)
    {
        $ERRORS[] = 'Invalid item ID specified.';
    } // end no item
    else
    {
        if($item->getItemClassId() != 4)
        {
            $ERRORS[] = 'That is not a recipe!';
        }

        if($item->getUserId() != $User->getUserId())
        {
            $ERRORS[] = 'This is not your item.';
        }

        if($item->getQuantity() <= 0)
        {
            $item->destroy();
            
            $ERRORS[] = 'You do not have any of this item.';
        }
    } // end valid ID
} // end ID specified

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
            $batches = $item->canCraft();
            $product = $item->grabProduct();
            
            $unique_lock = false;
            if($product->getUniqueItem() == 'Y')
            {
                $batches = ($batches > 1 ? 1 : 0); // only 1 of a unqiue
                if($User->hasItem($product->getItemTypeId()) == true)
                {
                    $unique_lock = true;
                }
            } // end uniquness

            $DISPLAY = array(
                'id' => $item->getUserItemId(),
                'type' => $item->getRecipeTypeDescription(),
                'name' => $item->getInflectedItemName(),
                'description' => $item->getItemDescr(),
                'image' => $item->getImageUrl(),
                'can_make_batch' => ($batches > 0 ? true : false),
                'max_batch' => $batches,
                'unique_lock' => $unique_lock,
            );

            $CREATES = array(
                'name' => ($item->getRecipeBatchQuantity() > 1 ? English_Inflector::pluralize($product->getItemName()) : $product->getItemName()),
                'quantity' => $item->getRecipeBatchQuantity(),
                'image' => $product->getImageUrl(),
            );

            $MATS = array();
            foreach($item->grabMaterials() as $material)
            {
                $material_item = Item::stackFactory($User->getUserId(),$material->getMaterialItemTypeId(),$db);
                $material_type = $material->grabMaterial();
                
                $MATS[] = array(
                    'name' => $material_type->getItemName(),
                    'have_quantity' => $material_item->getQuantity(), 
                    'need_quantity' => $material->getMaterialQuantity(),
                );
            } // end materials loop
            
            $renderer->assign('item',$DISPLAY);
            $renderer->assign('materials',$MATS);
            $renderer->assign('product',$CREATES);
            $renderer->display('crafting/details.tpl');
         
            break;   
        } // end default

        case 'craft':
        {
            // Ownership of the recipe is ensured elsewhere.
            // Round this up if someone is a dick and enters a float.
            $quantity = (int)ceil(stripinput($_REQUEST['quantity']));
            $product = $item->grabProduct();
            
            if($quantity == 0)
            {
                $ERRORS[] = 'No quantity specified.';
            }
            elseif($quantity < 0)
            {
                $ERRORS[] = 'Invalid quantity specified.';
            }

            if($product->getUniqueItem() == 'Y')
            {
                if($User->hasItem($product->getItemTypeId()) == true)
                {
                    $ERRORS[] = 'The product of this crafting pattern is a unique item that you already have.';
                }
                elseif($quantity > 1)
                {
                    $ERRORS[] = 'You cannot create more than one of this unique item.';
                }
            } // end unique item checks


            // TODO - Is the resulting item unique? If so, does the user have it?

            // We'll be loading some user_item rows below - store them
            // in this hash so we don't have to pull them back up if
            // validation passes.
            $ITEM_HASH = array();
            foreach($item->grabMaterials() as $material)
            {
                // Material quantity is an int in the DB, no rounding
                // to be worried about. Quantity is ensured to be an int
                // above.
                $required_quantity = ($quantity * $material->getMaterialQuantity());

                $item_stack = Item::stackFactory($User->getUserId(),$material->getMaterialItemTypeId(),$db);
                if($item_stack == null)
                {
                    $ERRORS[] = 'Critical error.';
                }
                else
                {
                    $ITEM_HASH[$item_stack->getItemTypeId()] = $item_stack;

                    if($item_stack->getQuantity() < $required_quantity)
                    {
                        $material_item = $material->grabMaterial();
                        $word = $material_item->getItemName();
                        
                        if($required_quantity > 1)
                        {
                            $word = English_Inflector::pluralize($word);
                        }

                        $ERRORS[] = "You need {$required_quantity} $word; you have {$item_stack->getQuantity()}!";
                    } // end quantity FAIL
                } // end loaded stack
            } // end mat validation loop
            
            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                foreach($item->grabMaterials() as $material)
                {
                    $item_stack = $ITEM_HASH[$material->getMaterialItemTypeId()];
                    if($item_stack == null)
                    {
                        // If the item is missing from the hash, something very bad has occured.
                        die('Something broke rather badly. Perhaps you should tell someone important?'); 
                    }

                    $required_quantity = ($quantity * $material->getMaterialQuantity());
                    $item_stack->updateQuantity(($item_stack->getQuantity() - $required_quantity));
                } // end remove mats loop
                
                $result = Item::stackFactory($User->getUserId(),$item->getRecipeCreatedItemTypeId(),$db);  

                $result_quantity = ($quantity * $item->getRecipeBatchQuantity()); 
                $result->updateQuantity(($result->getQuantity() + $result_quantity));

                $word = $product->getItemName();
                if($result_quantity > 1) 
                {
                    $word = English_Inflector::pluralize($word);
                }

                $_SESSION['craft_notice'] = "You have created {$result_quantity} {$word}.";
                redirect('crafting');
            } // end no errors; craft

            break;
        } // end craft
    } // end switch
} // no error

?>
