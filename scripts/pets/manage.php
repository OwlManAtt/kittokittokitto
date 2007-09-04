<?php
/**
 * Shows a user their pets and deals with setting another pet to active. 
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

$renderer->display('pets/topnav.tpl');

switch($_REQUEST['state'])
{
    default:
    {
        $PET_LIST = array();
        foreach($User->grabPets() as $pet)
        {
            $PET_LIST[] = array(
                'id' => $pet->getUserPetId(),
                'name' => $pet->getPetName(),
                'image' => $pet->getImageUrl(),
                'active' => $pet->getUserPetId() == $User->getActiveUserPetId() ? true : false,
                'fade' => $_SESSION['hilight_pet_id'] == $pet->getUserPetId() ? true : false,
            );
        } // end pet loop

        unset($_SESSION['hilight_pet_id']);
        $renderer->assign('pets',$PET_LIST);
        $renderer->display('pets/overview.tpl');

        break;
    } // end default

    case 'active':
    {
        $ERRORS = array();
        $pet_id = stripinput($_REQUEST['pet_id']);
        
        $pet = new Pet($db);
        $pet = $pet->findOneByUserPetId($pet_id);
        
        if($pet == null)
        {
            $ERRORS[] = 'Pet not found.';
        }
        else
        {
            if($pet->getUserId() != $User->getUserId())
            {
                $ERRORS[] = 'That is not your pet!';
            } // end not your pet
        } // end got pet

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            $pet->makeActive();
            $_SESSION['hilight_pet_id'] = $pet->getUserPetId();
            redirect('pets');
        }

        break;
    } // end case active
} // end state switch
?>
