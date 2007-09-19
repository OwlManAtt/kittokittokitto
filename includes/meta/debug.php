<?php
/**
 * Exception handlers for Kitto. 
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
 * The exception handler used if the release mode is DEV.
 *
 * This is very simple - it just print_rs the whole error before
 * exiting. 
 * 
 * @param object $e The exception.
 * @return void
 **/
function development_exception_handler($e)
{
    pprint_r($e);
    die();
} // end development_exception_handler

/**
 * The exception handler used if the release mode is PROD. 
 *
 * You probably want to change this function to e-mail you
 * and show a nicer page.
 * 
 * @todo
 * @param object $e The exception.
 * @return void
 **/
function production_exception_handler($e)
{
    print "An error occured!";
    die();
} // end production_exception_handler
?>
