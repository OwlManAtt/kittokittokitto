<?php
/**
 * Toy item definition. 
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
 * A toy.
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Items 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class Toy_Item extends Item
{
    /**
     * Increase a pet's happiness level.
     *
     * Increases the pet's happiness level and destroys the item. 
     * 
     * @param Pet $pet 
     * @param integer $quantity
     * @return string The success message.
     **/
    public function playWith(Pet $pet,$quantity)
    {
        if($quantity > $this->getQuantity())
        {
            throw new ArgumentError("This stack does not have $quantity items.");
        }

        $pet->play(($this->getHappinessBonus() * $quantity));
        $this->updateQuantity(($this->getQuantity() - $quantity));
        
        return "{$pet->getPetName()} is happier now.";
    } // end playWith

    public function listAttributes()
    {
        return array(
            array(
                'name' => 'happiness_bonus',
                'label' => 'Happiness Level',
                'type' => 'text',
                'validation_type' => 'integer',
                'max_length' => 3,
                'size' => 4,
            ),
        );
    } // end listAttributes
} // end Toy_Item

?>
