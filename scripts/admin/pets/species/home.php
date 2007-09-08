<?php
/**
 * Pet specie list.
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

switch($_REQUEST['state'])
{
    default:
    {
        $species = new PetSpecie($db);
        $species = $species->findBy(array(),'ORDER BY pet_specie.specie_name ASC');

        $SPECIES = array();
        foreach($species as $specie)
        {
            $SPECIES[] = array(
                'id' => $specie->getPetSpecieId(),
                'name' => $specie->getSpecieName(),
            );
        } // end specie reformatter

        if($_SESSION['petadmin_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['petadmin_notice']);
            unset($_SESSION['petadmin_notice']);
        } 

        $renderer->assign('species',$SPECIES);
        $renderer->display('admin/pets/species/list.tpl');

        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        $specie_id = stripinput($_POST['specie']['id']);
        
        $specie = new PetSpecie($db);
        $specie = $specie->findOneByPetSpecieId($specie_id);

        if($specie == null)
        {
            $ERRORS[] = 'Invalid specie specified.';
        }

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            // Hold this for the delete message.
            $name = $specie->getSpecieName();
            $specie->destroy();
            
            $_SESSION['petadmin_notice'] = "You have deleted <strong>$name</strong>.";
            redirect('admin-pet-species');
        } // end no errors

        break;
    } // end delete

} // end state switch
?>
