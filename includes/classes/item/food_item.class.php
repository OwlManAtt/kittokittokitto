<?php
/**
 *  
 **/

/**
 * An item of food. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class Food_Item extends Item
{
    /**
     * feedTo 
     * 
     * @param Pet $pet 
     * @return void 
     **/
    public function feedTo(Pet $pet)
    {
        $pet->consume($this->getHungerBonus());
        $name = $this->getItemName();
        $this->destroy();
         
        return "You have fed {$pet->getPetName()} the <strong>$name</strong>.";
    } // end feedTo
} // end Food_Item

?>
