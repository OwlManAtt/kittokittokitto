<?php
/**
 *  
 **/

$ERRORS = array();
$user_id = stripinput($_REQUEST['user_id']);

$profile = new User($db);
$profile = $profile->findOneByUserId($user_id);

if($profile == null)
{
    $ERRORS[] = 'Invalid user specified.';
} // end bad user

if(sizeof($ERRORS) > 0)
{
	draw_errors($ERRORS);
}
else
{
    $PROFILE = array(
        'id' => $profile->getUserId(),
        'user_name' => $profile->getUserName(),
        'created' => $User->formatDate($profile->getDatetimeCreated()),
        'last_active' => $profile->getLastActivity(),
        'gender' => $profile->getGender(),
        'age' => $profile->getAge(),
        'last_post' => ($profile->getDatetimeLastPost() == '0000-00-00 00:00:00') ? 0 : $profile->getDatetimeLastPost(),
        'profile' => $profile->getProfile(),
        'title' => $profile->getUserTitle(),
        'posts' => $profile->getPostCount(),
    );

    // Add a special note if needed.
    switch($profile->getAccessLevel())
    {
        case 'admin':
        {
            $PROFILE['special_status'] = 'This user is an administrator.';

            break;
        } // end admin

        case 'mod':
        {
            $PROFILE['special_status'] = 'This user is a moderator.';

            break;
        } // end admin

        case 'admin':
        {
            $PROFILE['special_status'] = 'This user has been banned.';

            break;
        } // end admin
    } // end userlevel switch
   
    $PETS = array();
    $pets = $profile->grabPets();
    
    foreach($pets as $pet)
    {
        $PETS[] = array(
            'id' => $pet->getUserPetId(),
            'name' => $pet->getPetName(),
            'image' => $pet->getImageUrl(),
            'species' => $pet->getSpecieName(),
        );    
    } // end pets loop
    
    $renderer->assign('pets',$PETS); 
    $renderer->assign('pet_count',sizeof($PETS)); 
	$renderer->assign('profile',$PROFILE);
	$renderer->display('user/profile.tpl');
} // end no errors
?>
