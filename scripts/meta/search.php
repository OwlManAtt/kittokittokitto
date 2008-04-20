<?php
/**
 * Search for users/pets. 
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

// Maximum search results to show per
// page.
$max_items_per_page = 30;

switch($_REQUEST['state'])
{
    default:
    {
        $renderer->display('meta/search/form.tpl');

        break;
    } // end default
    
    // This case re-formats the GET from the search form into the very
    // pretty search URL and forwards the request onward to the user-
    // friendly URL. 
    case 'passthrough':
    {
        $SEARCH = array(
            'term' => urlencode(stripinput($_REQUEST['keyword'])),
            'search' => urlencode(stripinput($_REQUEST['search'])),
            'precision' => urlencode(stripinput($_REQUEST['match'])),
        );
        
        redirect(null,null,"search/{$SEARCH['search']}/{$SEARCH['term']}/{$SEARCH['precision']}/1"); 

        break;
    } // end passthrough

    case 'search':
    {
        $ERRORS = array();
        
        $page = stripinput($_REQUEST['page']);
        if($page_id == null || $page_id <= 0)
        {
            $page_id = 1;
        }

        $start = (($page_id - 1) * $max_items_per_page);
        $end = (($page_id - 1) * $max_items_per_page) + $max_items_per_page;

        $SEARCH = array(
            'term' => stripinput(trim($_REQUEST['term'])),
            'search' => strtolower(stripinput($_REQUEST['search'])),
            'precision' => strtolower(stripinput($_REQUEST['precision'])),
        );

        if($SEARCH['term'] == null)
        {
            $ERRORS[] = 'No keyword specified.';
        }

        if(in_array($SEARCH['precision'],array('exact','contains')) == false)
        {
            $ERRORS[] = 'Invalid match type specified.';
        }

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            $term = $SEARCH['term'];
            if($SEARCH['precision'] == 'contains') 
            {
                $term = "%{$SEARCH['term']}%";
            }
            
            $RESULTS = array();

            switch($SEARCH['search'])
            {
                case 'user':
                {
                    $search_terms = array(
                        array(
                            'table' => 'user',
                            'column' => 'user_name',
                            'like' => true,
                            'value' => $term, 
                        ),
                    );

                    // Generate the pagination. 
                    $total = new User($db);
                    $total = $total->findBy($search_terms,null,true);
                    $pagination = pagination("search/{$SEARCH['search']}/{$SEARCH['term']}/{$SEARCH['precision']}",$total,$max_items_per_page,$page_id);

                    $users = new User($db);
                    $users = $users->findBy($search_terms,array(
                        'direction' => 'ASC',
                        'columns' => array(
                            array(
                                'table' => 'user',
                                'column' => 'user_name'
                            ),
                        ),
                    ),false,$start,$end);
                    
                    foreach($users as $user)
                    {
                        $RESULTS[] = array(
                            'label' => $user->getUserName(),
                            'slug' => 'profile',
                            'args' => $user->getUserId(),
                        );
                    } // end user loop

                    break;
                } // end user

                case 'pet':
                {
                    $search_terms = array(
                        array(
                            'table' => 'user_pet',
                            'column' => 'pet_name',
                            'like' => true,
                            'value' => $term, 
                        ),
                    );

                    // Generate the pagination. 
                    $total = new Pet($db);
                    $total = $total->findBy($search_terms,null,true);
                    $pagination = pagination("search/{$SEARCH['search']}/{$SEARCH['term']}/{$SEARCH['precision']}",$total,$max_items_per_page,$page_id);

                    $pets = new Pet($db);
                    $pets = $pets->findBy($search_terms,array(
                        'direction' => 'ASC',
                        'columns' => array(
                            array(
                                'table' => 'user_pet',
                                'column' => 'pet_name'
                            ),
                        ),
                    ),false,$start,$end);
                    
                    foreach($pets as $pet)
                    {
                        $RESULTS[] = array(
                            'label' => $pet->getPetName(),
                            'slug' => 'pet',
                            'args' => $pet->getUserPetId(),
                        );
                    } // end pets loop

                    break;
                } // end pet search

                default:
                {
                    $ERRORS[] = 'Invalid search type specified.';
                     
                    break;
                } // end default
            } // end search type switch
            
            if(sizeof($ERRORS) > 0)
            {
                draw_errors($ERRORS);
            }
            else
            {
                $renderer->assign('pagination',$pagination);
                $renderer->assign('results',$RESULTS);
                $renderer->display('meta/search/results.tpl');
            } // end no errors
        } // end no errors

        break; 
    } // end search
} // end state switch

?>
