<?php
/**
 * Edit pet color.
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
$color_id = (int)stripinput($_REQUEST['color']['id']);

$color = new PetSpecieColor($db);
$color = $color->findOneByPetSpecieColorId($color_id);

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
            if($color != null)
            {
                $COLOR = array(
                    'id' => $color->getPetSpecieColorId(),
                    'name' => $color->getColorName(),
                    'image' => $color->getColorImg(),
                    'base' => $color->getBaseColor(),
                );
            } // end edit mode
            
            $renderer->assign('color',$COLOR);
            $renderer->assign('base_options',array('' => 'Select one...','N' => 'No','Y' => 'Yes'));

            if($color != null)
            {
                $renderer->display('admin/pets/colors/edit.tpl');
            }
            else
            {
                $renderer->display('admin/pets/colors/new.tpl');
            }

            break;
        } // end default

        case 'save':
        {
            $COLOR = array(
                'name' => trim(stripinput($_POST['color']['name'])),
                'image' => trim(stripinput($_POST['color']['image'])),
                'base' => trim(stripinput($_POST['color']['base'])),
            );
            
            // If the group could not be loaded, start making a new one.
            if($color == null)
            {
                $color = new PetSpecieColor($db);
            }
            
            if($COLOR['name'] == null)
            {
                $ERRORS[] = 'No name specified.';
            }
            elseif(strlen($COLOR['name']) > 30)
            {
                $ERRORS[] = 'There is a maxlength=30 on that field for a reason.';
            }

            if($COLOR['image'] == null)
            {
                $ERRORS[] = 'No image specified.';
            }
            elseif(strlen($COLOR['image']) > 200)
            {
                $ERRORS[] = 'There is a maxlength=200 on that field for a reason.';
            }

            if(in_array($COLOR['base'],array('Y','N')) == false)
            {
                $ERRORS[] = 'Invalid option for base color.';
            }

            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $color->setColorName($COLOR['name']);
                $color->setColorImg($COLOR['image']);
                $color->setBaseColor($COLOR['base']);
                $color->save();

                $_SESSION['petadmin_notice'] = "You have saved <strong>{$color->getColorName()}</strong>.";
                redirect('admin-pet-colors');
            } // end no errors

            break;
        } // end save
    } // end state switch
} // end no errors
?>
