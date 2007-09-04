<?php
/**
 *  
 **/

/**
 * Avatar 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v3 {@link http://www.gnu.org/licenses/gpl-3.0.txt}
 **/
class Avatar extends ActiveTable
{
    protected $table_name = 'avatar';
    protected $primary_key = 'avatar_id';

    /**
     * Return the URL to the image.
     * 
     * @return null|string  
     **/
    public function getAvatarUrl()
    {
        global $APP_CONFIG;
    
        // It's a LEFT JOIN, mind you - it could come up with nothing.
        if($this->getAvatarId() == 0)
        {
            return null;
        }
        
        return "{$APP_CONFIG['public_dir']}/resources/avatars/{$this->getAvatarImage()}";
    } // end getAvatarUrl
} // end Avatar

?>
