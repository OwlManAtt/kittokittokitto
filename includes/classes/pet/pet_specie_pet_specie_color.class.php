<?php
/**
 * Specie <=> Color availability mapping. 
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
 * @subpackage Pets
 * @version 1.0.0
 **/

/**
 * PetSpecie_PetSpecieColor 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Pets
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class PetSpecie_PetSpecieColor extends ActiveTable
{
    protected $table_name = 'pet_specie_pet_specie_color';
    protected $primary_key = 'pet_specie_pet_specie_color_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'pet_specie_id',
            'foreign_table' => 'pet_specie',
            'foreign_key' => 'pet_specie_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'pet_specie_color_id',
            'foreign_table' => 'pet_specie_color',
            'foreign_key' => 'pet_specie_color_id',
            'join_type' => 'inner',
        ),
    );
    
    /**
     * Get a random pet color for a species. 
     *
     * This is useful in places where you want to show a pet,
     * but it is not a specific instance of a pet, so it has no
     * color. It's for showcasing...
     * 
     * @rdbms-specific MySQL 4/5
     * @param int $specie_id 
     * @param object $db PEAR::DB connector.
     * @return PetSpecie_PetSpecieColorId Random color instance.
     **/
    static public function randomColor($specie_id,$db)
    {
        $colors = new PetSpecie_PetSpecieColor($db);

        // RDBMS Note: For Oracle, change ORDER BY RAND() to:
        // ORDER BY mod(DBMS_RANDOM.random,50)+50
        $colors = $colors->findOneByPetSpecieId($specie_id,'ORDER BY RAND()');
        
        return $colors;
    } // end randomColor
} // end PetSpecie_PetSpecieColor

?>
