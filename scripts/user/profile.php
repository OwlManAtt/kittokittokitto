<?php
/**
 * Displays a user's profile.  
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
 * @subpackage Core
 * @version 1.0.0
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
    if($profile->getAccessLevel() == 'banned')
    {
        $PROFILE['special_status'] = 'This user has been banned.';
    } // end banned test
    else
    {
        $group_string = null;
        $groups = $profile->grabStaffGroups();
        if(sizeof($groups) > 0)
        {
            foreach($groups as $group)
            {
                $group_string[] = $group->getGroupName();
            } // end group reformatitng loop

            $PROFILE['special_status'] = 'Groups: '.implode(', ',$group_string);
        } // end has groups
    } // end try to load groups
   
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
    $renderer->assign('edit_user',$User->hasPermission('manage_users'));
	$renderer->display('user/profile.tpl');
} // end no errors
?>
