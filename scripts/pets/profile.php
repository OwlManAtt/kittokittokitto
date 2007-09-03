<?php
/**
 *  
 **/

$pet_id = stripinput($_REQUEST['pet_id']);

$pet = new Pet($db);
$pet = $pet->findOneByUserPetId($pet_id);

if($pet == null)
{
    draw_errors('Invalid pet specified.');
}
else
{
    $owner = $pet->grabUser();
    
    $PET = array(
        'id' => $pet->getUserPetId(),
        'name' => $pet->getPetName(),
        'owner' => array(
            'id' => $owner->getUserId(),
            'name' => $owner->getUserName(),
        ),
        'specie' => $pet->getSpecieName(),
        'hunger' => $pet->getHungerText(),
        'happiness' => $pet->getHappinessText(),
        'birthdate' => $User->formatDate($pet->getCreatedAt()),
        'profile' => $pet->getProfile(),
        'image' => $pet->getImageUrl(),
    );
    
    $renderer->assign('pet',$PET);
    $renderer->display('pets/profile.tpl');
} // end display pet
?>
