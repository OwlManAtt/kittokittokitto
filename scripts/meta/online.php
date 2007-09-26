<?php
/**
 * Show a list of online users. 
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

// The maximum number of users to show on each page.
$max_items_per_page = 30;

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{
    $page_id = 1;
}

$start = (($page_id - 1) * $max_items_per_page);
$end = (($page_id - 1) * $max_items_per_page) + $max_items_per_page;

// Generate the pagination. 
$total = UserOnline::totalUnhiddenUsers($db);
$pagination = pagination('online',$total,$max_items_per_page,$page_id);

$users = UserOnline::findOnlineUsers($start,$end,$db);

$USER_LIST = array();
foreach($users as $user)
{
    $USER_LIST[] = array(
        'id' => $user->getUserId(),
        'name' => $user->getUserName(),
        'last_active' => $user->getDatetimeLastActive(),
    );
} // end users formatting loop

$TOTAL = array(
    'sum' => UserOnline::totalOnline($db),
    'hidden' => UserOnline::totalHidden($db),
    'guests' => UserOnline::totalGuests($db),
);

$renderer->assign('pagination',$pagination);
$renderer->assign('users',$USER_LIST);
$renderer->assign('totals',$TOTAL);
$renderer->display('meta/online.tpl');
?>
