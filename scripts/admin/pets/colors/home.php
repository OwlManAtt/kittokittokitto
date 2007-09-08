<?php
/**
 * Pet color list.
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
        $colors = new PetSpecieColor($db);
        $colors = $colors->findBy(array(),'ORDER BY pet_specie_color.color_name ASC');

        $COLORS = array();
        foreach($colors as $color)
        {
            $COLORS[] = array(
                'id' => $color->getPetSpecieColorId(),
                'name' => $color->getColorName(),
                'image' => $color->getColorImg(),
                'base_color' => $color->getBaseColor(),
            );
        } // end color reformatter

        if($_SESSION['petadmin_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['petadmin_notice']);
            unset($_SESSION['petadmin_notice']);
        } 

        $renderer->assign('colors',$COLORS);
        $renderer->display('admin/pets/colors/list.tpl');

        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        $color_id = stripinput($_POST['color']['id']);
        
        $color = new PetSpecieColor($db);
        $color = $color->findOneByPetSpecieColorId($color_id);

        if($color == null)
        {
            $ERRORS[] = 'Invalid color specified.';
        }

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            // Hold this for the delete message.
            $name = $color->getColorName();
            $color->destroy();
            
            $_SESSION['petadmin_notice'] = "You have deleted <strong>$name</strong>.";
            redirect('admin-pet-colors');
        } // end no errors

        break;
    } // end delete

} // end state switch
?>
