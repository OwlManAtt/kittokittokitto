<?php
/**
 *  
 **/

/**
 * The base class for a simple item. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
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
     * Transfer ownership of an item to a different user.
     * 
     * @param User $new_user
     * @return bool
     **/
    public function giveItem(User $new_user)
    {
        $old_user = new User($this->db);
        $old_user = $old_user->findOneByUserId($this->getUserId());

        $old_username = 'An old man';
        if($old_user != null)
        {
            $old_username = $old_user->getUserName();
        }
        
        $this->setUserId($new_user->getUserId());
        $new_user->notify("{$old_username} has given you <strong>{$this->getItemName()}</strong>.",'items');

        return $this->save();
    } // end giveItem

} // end Item

?>
