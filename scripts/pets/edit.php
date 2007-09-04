<?php
/**
 * Edit a pet's profile text.  
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

$ERRORS = array();
$pet_id = stripinput($_REQUEST['pet_id']);
$pet = new Pet($db);
$pet = $pet->findOneByUserPetId($pet_id);

if($pet == null)
{
    $ERRORS[] = 'Invalid pet specified.';
}
else
{
    if($pet->getUserId() != $User->getUserId())
    {
        $ERRORS[] = 'That is not your pet.';
    }
} // end pet found

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{
    switch($_POST['state'])
    {
        default:
        {
            $PET = array(
                'id' => $pet->getUserPetId(),
                'name' => $pet->getPetName(),
                'profile' => $pet->getProfile(),
            );

            if($_SESSION['pet_notice'] != null)
            {
                $renderer->assign('notice',$_SESSION['pet_notice']);
                unset($_SESSION['pet_notice']);
            }

            $renderer->assign('pet',$PET);
            $renderer->display('pets/edit_profile.tpl');

            break;
        } // end default

        case 'save':
        {
            $profile = clean_xhtml($_POST['pet']['profile']);
            
            $pet->setProfile($profile);
            $pet->save();
            
            $_SESSION['pet_notice'] = "You have updated the profile of <strong>{$pet->getPetName()}</strong>.";
            redirect(null,null,"edit-pet/{$pet->getUserPetId()}");

            break;
        } // end save
    } // end state switch
} // end no errors
?>
