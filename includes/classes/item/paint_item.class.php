<?php
/**
 * Paintbrush item type definition.
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
 * A paintbrush for use on a pet. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Items
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class Paint_Item extends Item
{
    /**
     * Change a pet's color to the paintbrush's color (if available). 
     *
     * Changes the pet's pet_specie_color_id and destroys the item.
     *
     * If a pet doesn't have the specific color built out in 
     * pet_specie_pet_specie_color, a failure message will be shown and the
     * item will not be destroyed.
     * 
     * @param Pet $pet 
     * @return string The success (or failure) message. 
     **/
    public function paint(Pet $pet)
    {
        $color = new PetSpecie_PetSpecieColor($this->db);
        $color = $color->findOneBy(array(
            'pet_specie_id' => $pet->getPetSpecieId(),
            'pet_specie_color_id' => $this->getPetSpecieColorId(),
        ));

        // It's possible that that particular color is not built out for
        // that species (ie, nobody fucking drew it). Handle that.
        if($color == null)
        {
            return "You put the brush down, realizing that this color is not available for your pet.";
        }
        elseif($pet->getPetSpecieColorId() == $color->getPetSpecieColorId())
        {
            return "You frown and notice that {$pet->getPetName()} is already painted in {$color->getColorName()}.";
        }
        
        $pet->setPetSpecieColorId($color->getPetSpecieColorId());
        $pet->save();

        $this->updateQuantity(($this->getQuantity() - 1));

        return "{$pet->getPetName()} looks snazzy in <strong>{$color->getColorName()}</strong>!";
    } // end paint

    public function listAttributes()
    {
        $COLORS = array();
        $colors = new PetSpecieColor($this->db);
        $colors = $colors->findBy(array());

        foreach($colors as $color)
        {
            $COLORS[$color->getPetSpecieColorId()] = $color->getColorName();
        } // end color reformat
        
        return array(
            array(
                'name' => 'pet_specie_color_id',
                'label' => 'Color',
                'type' => 'select',
                'values' => $COLORS,
            ),
        );
    } // end listAttributes
} // end Paint_Item

?>
