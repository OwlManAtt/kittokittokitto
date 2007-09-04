<?php
/**
 * Pagination function by Caroline Lukens of the Yasashii Syndicate. 
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
 * @author Caroline Lukens <feuerfalke@gmail.com>
 * @copyright Caroline Lukens, 2007
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @package Kitto_Kitto_Kitto
 * @subpackage Core
 * @version 1.0.0
 **/

/**
 * Generate pagination links. 
 * 
 * This was originally written by Caroline Lukens and has been
 * adapted to Kitto_Kitto_Kitto by Nick Evans.
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link <http://www.virtualpetlist.com/forum/showthread.php?t=1733>
 * @author Caroline Lukens <feuerfalke@gmail.com>
 * @author Nicholas 'Owl' Evans <owlmanatt@gmail.com>
 * @param string $page_slug The page's slug and any other URL segments.
 * @param mixed $object_count Total number of items being peginated.
 * @param mixed $objects_per_page The number of items shown per page.
 * @param mixed $current_page The current page.
 * @param int $initial_page The initial page (defaults to 1)
 * @return string The HTML for the pagination links. 
 **/
function pagination($page_slug,$object_count,$objects_per_page,$current_page,$initial_page=1)
{
    global $APP_CONFIG;
   
    // Full URL for this app. 
    $page_url = "{$APP_CONFIG['public_dir']}/$page_slug";

    // Store the HTML.
    $pages = "";
    
    // Find out how many pages there are.
    $page_count = ceil($object_count / $objects_per_page) + ($initial_page - 1);
    if($page_count == 0) { $page_count = 1; }
    
    // Aliases to make shit readable.
    $n = $page_count;
    $p = $current_page;
    $a = $initial_page;
    
    /*
    * $a is the minimum limiter, and $n is the maximum limiter. Create 
    * an array of integers between $a and $n and line it up with
    * $page_array to get rid of any values that are too high or too low.
    * Get rid of duplicate values.
    */
    $valid_integers = range($a,$n);
    
    $array = array($a, $a+1, $p-1, $p, $p+1, $n-1, $n);
    $array = array_intersect($array,$valid_integers);
    $array = array_unique($array);
    
    // Resetting key values to be contiguous.
    foreach($array as $key => $value)
    {
        $page_array[] = $value;
    } // end reset loop
    
    /*
    * If the next value of the array is NOT EQUAL to the current value 
    * plus one, it is noncontiguous and needs ellipses.
    * Apart from this, each number is processed normally according to 
    * whether or not it's the current page.
    */
    for($i = 0; $i < sizeof($page_array); $i++)
    {
        $add = null;
        
        if(
            (($page_array[$i] + 1) != $page_array[$i + 1]) && 
            isset($page_array[$i + 1])
        ) // noncontiguous
        {
            $add = "... ";
        }
        
        if($page_array[$i] == $p)
        {
            $pages .= "<strong>{$page_array[$i]}</strong> $add";
        } 
        else 
        {
            $pages .= "<a href='{$page_url}/{$page_array[$i]}'>{$page_array[$i]}</a> $add";
        }
    } // end for
    
    $previous = '&laquo; Prev | ';
    if(($p - 1) > 0)
    {
        $previous = "<a href='{$page_url}/".($p - 1)."'>&laquo; Prev</a> | ";
    }
    
    $next = ' | Next &raquo;';
    if(($p + 1) <= $page_count)
    {
        $next = " | <a href='{$page_url}/".($p + 1)."'>Next &raquo;</a>";
    }
    
    $pages = $previous . $pages . $next;
    
    return $pages;
} // end pagination

?>
