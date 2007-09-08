<?php
/**
 * Pet => Color mapper. 
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

if($specie == null)
{
    $ERRORS[] = 'Invalid specie.';
} 
else
{
    switch($_REQUEST['state'])
    {
        default:
        {
            $colors = new PetSpecieColor($db);
            $colors = $colors->findBy(array(),'ORDER BY color_name ASC');

            $LIST = array();
            foreach($colors as $color)
            {
                $mapping = new PetSpecie_PetSpecieColor($db);
                $mapping = $mapping->findOneBy(array('pet_specie_id' => $specie->getPetSpecieId(), 'pet_specie_color_id' => $color->getPetSpecieColorId()));
                
                $LIST[] = array(
                    'id' => $color->getPetSpecieColorId(),
                    'name' => $color->getColorName(),
                    'built' => (bool)$mapping,
                );
            } // end color reformatter

            $SPECIE = array(
                'id' => $specie->getPetSpecieId(),
                'name' => $specie->getSpecieName(),
            );

            if($_SESSION['petadmin_notice'] != null)
            {
                $renderer->assign('notice',$_SESSION['petadmin_notice']);
                unset($_SESSION['petadmin_notice']);
            } 

            $renderer->assign('colors',$LIST);
            $renderer->assign('specie',$SPECIE);
            $renderer->display('admin/pets/species/color_mapping.tpl');

            break;
        } // end default

        case 'toggle':
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
                $mapping = new PetSpecie_PetSpecieColor($db);
                $mapping = $mapping->findOneBy(array('pet_specie_id' => $specie->getPetSpecieId(), 'pet_specie_color_id' => $color->getPetSpecieColorId()));

                if($mapping == null)
                {
                    $mapping = new PetSpecie_PetSpecieColor($db);
                    $mapping->setPetSpecieId($specie->getPetSpecieId());
                    $mapping->setPetSpecieColorId($color->getPetSpecieColorId());
                    $mapping->save();
                    $_SESSION['petadmin_notice'] = "{$color->getColorName()} enabled.";
                }
                else
                {
                    $mapping->destroy();
                    $_SESSION['petadmin_notice'] = "{$color->getColorName()} disabled.";
                }
                
                redirect(null,null,"admin-pet-specie-colors/?specie[id]={$specie->getPetSpecieId()}");
            } // end no errors

            break;
        } // end toggle
    } // end switch
} // end no errors
?>
