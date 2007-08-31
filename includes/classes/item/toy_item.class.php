<?php
/**
 *  
 **/

/**
 * A toy.
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class Toy_Item extends Item
{
    public function playWith(Pet $pet)
    {
        $pet->play($this->getHappinessBonus());
        $this->destroy();
        
        return "{$pet->getPetName()} is happier now.";
    } // end playWith
    
} // end Toy_Item

?>
