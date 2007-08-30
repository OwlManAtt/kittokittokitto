<?php
/**
 *  
 **/

/**
 * Pet 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class Pet extends ActiveTable
{
    protected $table_name = 'user_pet';
    protected $primary_key = 'user_pet_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'pet_specie_id', 
            'foreign_table' => 'pet_specie',
            'foreign_key' => 'pet_specie_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'pet_specie_id', 
            'foreign_table' => 'pet_specie_color',
            'foreign_key' => 'pet_specie_color_id',
            'join_type' => 'inner',
        ),
    );
    protected $RELATED = array(
        'user' => array(
            'class' => 'User',
            'local_key' => 'user_id',
            'foreign_key' => 'user_id',
        ),
    );

    /**
     * Get the full URL for this pet's image.
     * 
     * @return string 
     **/
    public function getImageUrl()
    {
        global $APP_CONFIG;
        
        return "{$APP_CONFIG['public_dir']}/resource/images/pets/{$this->getRelativeImageDir()}/{$this->getColorImg()}";
    } // end getImageUrl
} // end Pet

?>
