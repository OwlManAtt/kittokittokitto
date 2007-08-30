<?php
/**
 *  
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
     * @rdbms-specific
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
