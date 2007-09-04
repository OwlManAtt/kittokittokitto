<?php
/**
 * Class to provide other classes with an ActiveTable-like API.
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
 * @subpackage Core
 * @version 1.0.0
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
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
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
