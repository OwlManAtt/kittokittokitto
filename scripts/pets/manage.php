<?php
$renderer->display('pets/topnav.tpl');

$PET_LIST = array();
foreach($User->grabPets() as $pet)
{
    $PET_LIST[] = array(
        'id' => $pet->getUserPetId(),
        'name' => $pet->getPetName(),
        'image' => $pet->getImageUrl(),
        'fade' => $_SESSION['new_pet_id'] == $pet->getUserPetId() ? true : false,
    );
} // end pet loop

unset($_SESSION['new_pet_id']);
$renderer->assign('pets',$PET_LIST);
$renderer->display('pets/overview.tpl');
?>
