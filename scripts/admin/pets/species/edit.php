<?php
/**
 * Edit pet specie.
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
$specie_id = stripinput($_REQUEST['specie']['id']);

$specie = new PetSpecie($db);
$specie = $specie->findOneByPetSpecieId($specie_id);

if(sizeof($ERRORS) > 0)
{
    draw_errors($ERRORS);
}
else
{
    switch($_REQUEST['state'])
    {
        default:
        {
            if($specie != null)
            {
                $SPECIE = array(
                    'id' => $specie->getPetSpecieId(),
                    'name' => $specie->getSpecieName(),
                    'description' => $specie->getSpecieDescr(),
                    'image_dir' => $specie->getRelativeImageDir(),
                    'available' => $specie->getAvailable(),
                    'max_hunger' => $specie->getMaxHunger(),
                    'max_happiness' => $specie->getMaxHappiness(),
                );
            } // end edit mode
            
            $renderer->assign('specie',$SPECIE);
            $renderer->assign('available_options',array('' => 'Select one...','N' => 'No','Y' => 'Yes'));

            if($specie != null)
            {
                $renderer->display('admin/pets/species/edit.tpl');
            }
            else
            {
                $renderer->display('admin/pets/species/new.tpl');
            }

            break;
        } // end default

        case 'save':
        {
            $SPECIE = array(
                'name' => trim(stripinput($_POST['specie']['name'])),
                'description' => trim(clean_xhtml($_POST['specie']['descr'])),
                'image_dir' => trim(stripinput($_POST['specie']['image_dir'])),
                'hunger' => trim(stripinput($_POST['specie']['hunger'])),
                'happiness' => trim(stripinput($_POST['specie']['happiness'])),
                'available' => trim(stripinput($_POST['specie']['available'])),
            );
            
            // If the group could not be loaded, start making a new one.
            if($specie == null)
            {
                $specie = new PetSpecie($db);
            }
            
            if($SPECIE['name'] == null)
            {
                $ERRORS[] = 'No name specified.';
            }
            elseif(strlen($SPECIE['name']) > 50)
            {
                $ERRORS[] = 'There is a maxlength=50 on that field for a reason.';
            }

            if($SPECIE['image_dir'] == null)
            {
                $ERRORS[] = 'No image directory specified.';
            }
            elseif(strlen($SPECIE['image_dir']) > 200)
            {
                $ERRORS[] = 'There is a maxlength=200 on that field for a reason.';
            }

            if($SPECIE['hunger'] <= 0)
            {
                $ERRORS[] = 'Invalid max hunger level.';
            }

            if($SPECIE['happiness'] <= 0)
            {
                $ERRORS[] = 'Invalid max happiness level.';
            }

            if(in_array($SPECIE['available'],array('Y','N')) == false)
            {
                $ERRORS[] = 'Invalid option for availability.';
            }

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $specie->setSpecieName($SPECIE['name']);
                $specie->setSpecieDescr($SPECIE['description']);
                $specie->setRelativeImageDir($SPECIE['image_dir']);
                $specie->setMaxHunger($SPECIE['hunger']);
                $specie->setMaxHappiness($SPECIE['happiness']);
                $specie->setAvailable($SPECIE['available']);
                $specie->save();

                $_SESSION['petadmin_notice'] = "You have saved <strong>{$specie->getSpecieName()}</strong>.";
                redirect('admin-pet-species');
            } // end no errors

            break;
        } // end save
    } // end state switch
} // end no errors
?>
