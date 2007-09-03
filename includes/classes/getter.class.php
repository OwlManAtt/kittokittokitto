<?php
/**
 *  
 **/

/**
 * Abstract class that provides getAttrName() methods similar to
 * that of ActiveTable.
 *
 * To use this class, simply extends your class from it. Any calls
 * to getSomething() will return $this->something.
 * 
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v3 {@link http://www.gnu.org/licenses/gpl-3.0.txt}
 **/
abstract class Getter
{
    /**
     * Provides the virtual method #getSomething(). 
     * 
     * @param string $method The methodname.
     * @param array $args Arguments passed to the method.
     * @return mixed
     **/
    public function __call($method,$args)
    {
        if(preg_match('/^get([A-Z][A-Za-z0-9_]*)$/',$method,$FOUND) == true)
        {
            $attr = $this->convert_studly_case($FOUND[1]);
            
            return $this->$attr;
        } // end get
        
        return false;
    } // end __call

    /**
     * Convert studlycase to all lowercase with underscores.
     * 
     * @param string $word StudlyWord 
     * @return string studly_word
     **/
    private function convert_studly_case($word)
    {
        $word = preg_replace('/([a-z0-9])([A-Z])/','\1_\2',$word);
        $word = strtolower($word);
        
        return $word;
    } // end convert_studly_case
} // end Getter

?>
