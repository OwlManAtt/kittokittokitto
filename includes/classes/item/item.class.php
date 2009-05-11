<?php
/**
 * Base item definition that the specific types extend from. 
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
 * The base class for a simple item. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Items 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class Item extends ItemType 
{
    protected $table_name = 'user_item';
    protected $primary_key = 'user_item_id';
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
    );
    
    /**
     * Load a user-owned item stack.
     *
     * This method will look for a stack of $item_id owned by $user_id.
     * If the stack does not exist, an empty (uncreated) object of
     * the correct type will be returned.
     * 
     * @param int $user_id The user ID. 
     * @param int $item_id The item ID.
     * @param obkect $db DB connector.
     * @return object 
     **/
    static public function stackFactory($user_id,$item_id,$db)
    {
        $basic = new Item($db);
        $basic = $basic->findOneBy(array(
            'user_id' => $user_id,
            'item_type_id' => $item_id,
        ));

        // This stack exists. Let that crazy factory handle the details.
        if($basic != null)
        {
            return Item::factory($basic->getUserItemId(),$db);
        }

        // The stack does not exist. Prep a new object and give that back. 
        $type = new ItemType($db);
        $type = $type->findOneByItemTypeId($item_id);

        if($type == null)
        {
            throw new ArgumentError('Could not find item type that should exist.');
        }
    
        eval('$item = new '.$type->getPhpClass().'($db);');
        $item->setUserId($user_id);
        $item->setItemTypeId($item_id);
        $item->setQuantity(0);
        
        return $item;
    } // end stackFactory
    
    /**
     * Return an instance of the correct *_Item class.
     *
     * Different items have their functionality implemented
     * in different classes (#feed() in Food_Item, etc.). This
     * finds the right class for your item, loads the item's
     * details, and let's you get on with your business.
     * 
     * @param mixed $user_item_id The item to load.
     * @param object $db PEAR::DB connector.
     * @return object (See above)
     **/
    static public function factory($user_item_id,$db)
    {
        $basic = new Item($db);
        $basic = $basic->findOneByUserItemId($user_item_id);

        if($basic == null)
        {
            return null;
        }

        // The class name is held in the DB. 
        eval('$item = new '.$basic->getPhpClass().'($db);');
        $item = $item->findOneByUserItemId($basic->getUserItemId());
        
        return $item;
    } // end factory

    /**
     * Updates the stack quantity, destroying the row if <=0.
     *
     * This will either DESTROY or UPDATE the row, depending on
     * whether or not the new quantity requires the user_item
     * row to be deleted or merely updated.
     * 
     * At the end of this function, the object it was called on
     * will be an effectively 'new' and clean instance of Item,
     * or it will be the updated stack of one or more items.
     * 
     * @param int $quantity The new quantity. 
     * @return bool 
     **/
    public function updateQuantity($quantity)
    {
        // This may not be a loaded user_item row. If it's still
        // in the process of being created, it won't have the item_type
        // data loaded.
        $item = new ItemType($this->db);
        $item = $item->findOneByItemTypeId($this->getItemTypeId());
        if($item == null)
        {
            throw new ArgumentError('Invalid item type ID set when calling #updateQuantity().');
        }

        if($item->getUniqueItem() == 'Y')
        {
            $total = ($this->getQuantity() + $quantity);
            if($total > 1)
            {
                throw new ArgumentError('User cannot have more than one of a unique item.');
            }
        } // end unique checks

        if($quantity <= 0)
        {
            // Save some stuff so we can respawn the object as blank.
            $user_id = $this->getUserId();
            $item_id = $this->getItemId();
            $db = $this->db;

            // Burn.
            $this->destroy();

            // Respawn.
            $this->db = $db;
            $this->setUserId($user_id);
            $this->setItemTypeId($item_id);
            $this->setQuantity(0);
        }
        else
        {
            $this->setQuantity($quantity);
            $this->save();
        }

        return true;
    } // end quantity

    /**
     * Transfer ownership of an item to a different user.
     * 
     * @param User $new_user
     * @param integer $quantity
     * @return bool
     **/
    public function giveItem(User $new_user,$quantity)
    {
        if($quantity > $this->getQuantity())
        {
            throw new ArgumentError("Argument quantity = $quantity exceeds maximum of {$this->getQuantity()}.");
        }
                
        $old_user = new User($this->db);
        $old_user = $old_user->findOneByUserId($this->getUserId());

        $old_username = 'An old man';
        if($old_user != null)
        {
            $old_username = $old_user->getUserName();
        }
   
        $given_item = Item::stackFactory($new_user->getUserId(),$this->getItemTypeId(),$this->db);
        $given_item->updateQuantity(($given_item->getQuantity() + $quantity));
        
        $word = $given_item->getItemName();
        if($quantity > 1)
        {
            $word = English_Inflector::pluralize($word); 
        }
        
        $new_user->notify("{$old_username} has given you <strong>{$quantity} {$word}</strong>.",'items');
        $this->updateQuantity(($this->getQuantity() - $quantity));
        
        return true;
    } // end giveItem

    public function getInflectedItemName()
    {
        if($this->getQuantity() == 1)
        {
            return $this->getItemName();
        }

        return English_Inflector::pluralize($this->getItemName());
    } // end getInflectedItemName

    /**
     * Turns a quantity for the item into appropriately-pluralized text.
     *
     * 20 apples in total, using 1 => the apple
     * 20 apples in total, using 2 => 20 apples
     * 20 apples in total, using 20 => all 20 apples
     * 2 apple in total, using 1 => your apple
     * 
     * @param mixed $quantity 
     * @return string 
     **/
    public function makeActionText($quantity)
    {
        $word = '';
        if($quantity == $this->getQuantity())
        {
            // Figure it out here so you have the methods available.
            if($quantity > 1)
            {
                $word = "all <strong>$quantity {$this->getInflectedItemName()}</strong>"; 
            }
            else
            {
                $word = "your <strong>{$this->getItemName()}</strong>"; 
            }
        } // end destroy stack
        else
        {
            if($quantity > 1)
            {
                $plural = English_Inflector::pluralize($this->getItemName());
                $word = "<strong>$quantity $plural</strong>"; 
            }
            else
            {
                $word = "the <strong>{$this->getItemName()}</strong>"; 
            }
        } // end not all 
        
        return $word;
    } // end makeActionText
} // end Item

?>
