<?php
/**
 *  
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
