<?php
/**
 * Item details, use item, destroy item, and give item away. 
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

switch($_REQUEST['state'])
{
    default:
    {
        $ERRORS = array();
        $id = stripinput($_REQUEST['item']['id']);

        $item = Item::factory($id,$db);
        if($item == null)
        {
            $ERRORS[] = 'Invalid item ID specified.';
        }
        else
        {
            if($item->getUserId() != $User->getUserId())
            {
                $ERRORS[] = 'This is not your item.';
            }

            if($item->getQuantity() <= 0)
            {
                $item->destroy();
                
                $ERRORS[] = 'You do not have any of this item.';
            }
        } // end item exists

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        } 
        else
        {
            $ACTIONS = array(
                 '' => 'Select one...',
                'use' => 'Use',
            );
            if($item->getTransferableItem() == 'Y')
            {
                $ACTIONS['give'] = 'Give';
            }
            $ACTIONS['destroy'] = 'Destroy';

            $DISPLAY = array(
                'id' => $item->getUserItemId(),
                'name' => $item->getInflectedItemName(),
                'quantity' => $item->getQuantity(),
                'description' => $item->getItemDescr(),
                'image' => $item->getImageUrl(),
                'actions' => $ACTIONS,
            );

            $renderer->assign('item',$DISPLAY);
            $renderer->display('items/details.tpl');
        } // end show item

        break;
    } // end default

    case 'action':
    {
        $ERRORS = array();
        $id = stripinput($_REQUEST['action']['item_id']);
        $quantity = stripinput($_REQUEST['action']['quantity']);

        $item = Item::factory($id,$db);
        if($item == null)
        {
            $ERRORS[] = 'Invalid item ID specified.';
        }
        else
        {
            if($item->getUserId() != $User->getUserId())
            {
                $ERRORS[] = 'This is not your item.';
            }

            if($quantity > $item->getQuantity())
            {
                $ERRORS[] = "You do not have $quantity of this item.";
            }

            if($item->getQuantity() <= 0)
            {
                $item->destroy();
                
                $ERRORS[] = 'You do not have any of this item.';
            }
        } // end item exists

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        } 
        else
        {
            // Determine what action is to be taken.
            switch($_REQUEST['action']['type'])
            {
                case 'give':
                {
                    if($item->getTransferableItem() == 'N')
                    {
                        $ERRORS[] = 'You cannot give this item away.';
                    }

                    if(sizeof($ERRORS) > 0)
                    {
                        draw_errors($ERRORS);
                    }
                    else
                    {
                        $QUANTITY = array(
                            'max' => $item->getQuantity(),
                            'default' => $quantity, 
                        );
                        
                        $renderer->assign('item_name',$item->getInflectedItemName());
                        $renderer->assign('quantity',$QUANTITY);
                        $renderer->assign('item_id',$item->getUserItemId());
                        $renderer->display('items/give_form.tpl');
                    } // end no error
                
                    break;
                } // end give
                
                case 'destroy':
                {
                    $word = $item->makeActionText($quantity);
                    $item->updateQuantity(($item->getQuantity() - $quantity));
                                        
                    $_SESSION['item_notice'] = "You have destroyed $word.";

                    redirect('items');

                    break;
                } // end destroy
                
                case 'use':
                {
                    $PET_LIST = array();
                    $ITEM = array(
                        'id' => $item->getUserItemId(),
                        'name' => $item->getInflectedItemName(),
                        'force_one' => $item->getOnePerUse(),
                    );

                    $QUANTITY = array(
                        'max' => $item->getQuantity(),
                        'default' => $quantity,
                    );
                    
                    $pets = $User->grabPets();
                    foreach($pets as $pet)
                    {
                        $PET_LIST[$pet->getUserPetId()] = $pet->getPetName();
                    } // end pets loop
                  
                    if(sizeof($PET_LIST) == 0)
                    {
                        draw_errors('You do not have a pet to use this item on.');
                    }
                    else
                    {
                        $renderer->assign('use_verb',$item->getVerb()); 
                        $renderer->assign('pets',$PET_LIST);
                        $renderer->assign('item',$ITEM);
                        $renderer->assign('quantity',$QUANTITY);
                        $renderer->display('items/use_form.tpl');
                    }

                    break;
                } // end use

                default:
                {
                    draw_errors('You forgot to pick an action!');
                    
                    break;
                } // end default
            } // end action switch
        } // end no errors

        break;
    } // end action

    // Process page for 'use' after pet has been selected.
    case 'use_item':
    {
        $ERRORS = array();
        $id = stripinput($_POST['use']['item_id']);
        $pet_id = stripinput($_POST['use']['pet_id']);
        $quantity = stripinput($_POST['use']['quantity']);

        $item = Item::factory($id,$db);
        if($item == null)
        {
            $ERRORS[] = 'Invalid item ID specified.';
        }
        else
        {
            if($item->getOnePerUse() == 'Y')
            {
                $quantity = 1;
            }
            
            if($item->getUserId() != $User->getUserId())
            {
                $ERRORS[] = 'This is not your item.';
            }

            if($quantity > $item->getQuantity())
            {
                $ERRORS[] = "You do not have $quantity of this item.";
            }

            if($item->getQuantity() <= 0)
            {
                $item->destroy();
                
                $ERRORS[] = 'You do not have any of this item.';
            }
        } // end item exists

        // Verify the pet.
        $pet = new Pet($db);
        $pet = $pet->findOneByUserPetId($pet_id);

        if($pet == null)
        {
            $ERRORS[] = 'Invalid pet specified.';
        }
        else
        {
            if($User->getUserId() != $pet->getUserId())
            {
                $ERRORS[] = 'This is not your pet.';
            }
        } // end pet found

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        } // end errors
        else
        {
            // To add a new item type, add the appropriate entry to 
            // item_class, define that class (use Food_Item as an example!)
            // and add a call to it's action method with the appropriate
            // parameters here.
            switch(get_class($item))
            {
                case 'Food_Item':
                {
                    $_SESSION['item_notice'] = $item->feedTo($pet,$quantity); 
                    redirect('items');
                    
                    break;
                } // end Food_Item

                case 'Toy_Item':
                {
                    $_SESSION['item_notice'] = $item->playWith($pet,$quantity); 
                    redirect('items');

                    break;
                } // end Food_Item

                case 'Paint_Item':
                {
                    $_SESSION['item_notice'] = $item->paint($pet);
                    redirect('items');
                    
                    break;
                } // end Food_Item
                
                default:
                {
                    draw_errors('System error - item is of an unknown type.');
                    
                    break;   
                } // end default

            } // end item type switch
        } // end no errors

        break;
    } // end use_item

    case 'give_process':
    {
        $ERRORS = array();
        $id = stripinput($_REQUEST['give']['item_id']);
        $other_user_name = stripinput($_REQUEST['give']['username']);
        $quantity = stripinput($_REQUEST['give']['quantity']);

        $item = Item::factory($id,$db);
        if($item == null)
        {
            $ERRORS[] = 'Invalid item ID specified.';
        }
        else
        {
            if($item->getUserId() != $User->getUserId())
            {
                $ERRORS[] = 'This is not your item.';
            }

            if($item->getTransferableItem() == 'N')
            {
                $ERRORS[] = 'You cannot give this item away.';
            }

            if($quantity > $item->getQuantity())
            {
                $ERRORS[] = "You do not have $quantity of this item.";
            }

            if($item->getQuantity() <= 0)
            {
                $item->destroy();
                
                $ERRORS[] = 'You do not have any of this item.';
            }
        } // end item exists

        // Verify the other user is real lolzu.
        $other_user = new User($db);
        $other_user = $other_user->findOneByUserName($other_user_name);

        if($other_user == null)
        {
            $ERRORS[] = 'Invalid user specified.';
        }
        elseif($User->getUserName() == $other_user->getUserName())
        {
            $ERRORS[] = 'You cannot give an item to yourself.';
        }

        if($item != null && $other_user != null)
        {
            if($item->getUniqueItem() == 'Y')
            {
                if($other_user->hasItem($item->getItemTypeId()) == true)
                {
                    $ERRORS[] = 'That user already has this unique item!';
                }
                elseif($quantity > 1)
                {
                    // This case should never come up...
                    $ERRORS[] = 'This is a unique item; you cannot give the user two!';
                }
            }
        } // end item and user exist

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        } 
        else
        {
            $word = $item->getItemName();
            $item->giveItem($other_user,$quantity);

            if($quantity > 1)
            {
                $word = English_Inflector::pluralize($word);
            }

            $_SESSION['item_notice'] = "You have given <strong>$quantity {$word}</strong> to {$other_user->getUserName()}.";
            
            redirect('items');
        } // end DO IT
        
        break;
    } // end give_process
} // end switch

?>
