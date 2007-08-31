<?php
/**
 *  
 **/

/**
 * A paintbrush for use on a pet. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class Paint_Item extends Item
{
    /**
     * Change a pet's color to the paintbrush's color (if available). 
     * 
     * @param Pet $pet 
     * @return void
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
            return "You put the brush down, realizing that {$pet->getPetName()} would not look good in that color.";
        }
        
        $pet->setPetSpecieColorId($color->getPetSpecieColorId());
        $pet->save();

        $this->destroy();

        return "{$pet->getPetName()} looks snazzy in <strong>{$color->getColorName()}</strong>!";
    } // end paint

} // end Paint_Item

?>
