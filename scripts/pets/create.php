<?php
$renderer->display('pets/topnav.tpl');

switch($_REQUEST['state'])
{
    default:
    { 
        $species = new PetSpecie($db);
        $species = $species->findByAvailable('Y');

        $SPECIES_LIST = array();
        foreach($species as $specie)
        {
            $DATA = array(
                'id' => $specie->getPetSpecieId(),
                'name' => $specie->getSpecieName(),
                'description'=> $specie->getSpecieDescr(),
                'image' => null, 
            );

            // There may be no colors build for this pet. Don't blow up if that is the
            // case.
            $color = PetSpecie_PetSpecieColor::randomColor($specie->getPetSpecieId(),$db);
            if($color != null)
            {
                $DATA['image'] = "{$APP_CONFIG['public_dir']}/resources/pets/{$specie->getRelativeImageDir()}/{$color->getColorImg()}";
            }
            
            $SPECIES_LIST[] = $DATA;
        } // end species loop

        $renderer->assign('species',$SPECIES_LIST);
        $renderer->display('pets/create_list.tpl');

        break;
    } // end default

    case 'details':
    {
        $ERRORS = array();
        $id = stripinput($_REQUEST['species']['id']);

        // Does the user have too many pets?
        if(sizeof($User->grabPets()) >= $APP_CONFIG['max_pets'])
        {
            $ERRORS[] = "You already have {$APP_CONFIG['max_pets']} pets!";
        }
        else
        {
            $specie = new PetSpecie($db);
            $specie = $specie->findOneBy(array(
                'pet_specie_id' => $id,
                'available' => 'Y',
            ));

            if($specie == null)
            {
                $ERRORS[] = 'Invalid specie ID specified.';
            } // end no pet
            else
            {
                $colors = new PetSpecie_PetSpecieColor($db);
                $colors = $colors->findBy(array(
                    'pet_specie_id' => $specie->getPetSpecieId(),
                    array(
                        'table' => 'pet_specie_color',
                        'column' => 'base_color',
                        'value' => 'Y',
                    ),
                ));

                $COLOR_LIST = array('' => 'Select one...');
                foreach($colors as $color)
                {
                    $COLOR_LIST[$color->getPetSpeciePetSpecieColorId()] = $color->getColorName();
                }
               
                if(sizeof($COLOR_LIST) == 1)
                {
                    $ERRORS[] = 'Since there are no available colors for this pet, you cannot adopt it!';
                }
            } // end try to load color
        } // end user has acceptable number of pets

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            $PET = array(
                'id' => $specie->getPetSpecieId(),
                'name' => $specie->getSpecieName(),
                'description'=> $specie->getSpecieDescr(),
                'image' => null, 
            );

            $color = PetSpecie_PetSpecieColor::randomColor($specie->getPetSpecieId(),$db);
            if($color != null)
            {
                $PET['image'] = "{$APP_CONFIG['public_dir']}/resources/pets/{$specie->getRelativeImageDir()}/{$color->getColorImg()}";
            }
         
            $renderer->assign('pet',$PET);
            $renderer->assign('colors',$COLOR_LIST);
            $renderer->display('pets/create_details.tpl');
        } // end pet is valid

        break;
    } // end details

    case 'spawn':
    {
        $ERRORS = array();
        $specie_id = stripinput($_POST['pet']['specie_id']);
        $color_id = stripinput($_POST['pet']['color_id']);
        $pet_name = stripinput($_POST['pet']['name']);

        // Does the user have too many pets?
        if(sizeof($User->grabPets()) >= $APP_CONFIG['max_pets'])
        {
            $ERRORS[] = "You already have {$APP_CONFIG['max_pets']} pets!";
        }
        
        // Pet name OK?
        if($pet_name == null)
        {
            $ERRORS[] = 'Blank pet name specified.';
        }
        elseif(strlen($pet_name) > 25)
        {
            $ERRORS[] = 'There is a maxlength=25 attribute on that input tag for a reason.';
        }
		elseif(preg_match('/^[A-Z0-9_!@#\$%\^&\*\(\);:,\.]*$/i',$pet_name) == false)
        {
            $ERRORS[] = 'Invalid characters in pet name. No spaces allowed!';
        }
        else
        {
            $other_pet = new Pet($db);
            $other_pet = $other_pet->findOneByPetName($pet_name);

            if($other_pet != null)
            {
                $ERRORS[] = 'That name is already in use.';
            }
        } // end do name-in-use checks
       
        // Check species.
        $specie = new PetSpecie($db);
        $specie = $specie->findOneBy(array(
            'pet_specie_id' => $specie_id,
            'available' => 'Y',
        ));

        if($specie == null)
        {
            $ERRORS[] = 'Invalid specie ID specified.';
        } // end no pet
        
        $color = new PetSpecie_PetSpecieColor($db);
        $color = $color->findOneBy(array(
            'pet_specie_pet_specie_color_id' => $color_id,
            array(
                'table' => 'pet_specie_color',
                'column' => 'base_color',
                'value' => 'Y',
            ),
        ));

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
            // Add pet.
            $pet = new Pet($db);
            $pet->setUserId($User->getUserId());
            $pet->setPetSpecieId($specie->getPetSpecieId());
            $pet->setPetSpecieColorId($color->getPetSpecieColorId());
            $pet->setPetName($pet_name);
            $pet->setHunger($specie->getMaxHunger());
            $pet->setHappiness($specie->getMaxHappiness);
            $pet->setCreatedAt($pet->sysdate());
            $pet->save();

            // Session mog
            $_SESSION['new_pet_id'] = $pet->getUserPetId();
            
            // Redirect
            redirect('pets');
        } // end no errors; DO IT
        
        break;
    } // end spawn
} // end state switch
?>
