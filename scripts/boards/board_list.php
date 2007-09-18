<?php
/**
 * List out the boards. 
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
 * @subpackage Board
 * @version 1.0.0
 **/

$BOARD_LIST = array();
$boards = new Board($db);
$boards = $boards->findBy(array(),'ORDER BY order_by');

foreach($boards as $board)
{
     $BOARD = array(
        'id' => $board->getBoardId(),
        'name' => $board->getBoardName(),
        'description' => $board->getBoardDescr(),
        'last_poster' => $board->getLastPosterUsername(), 
        'total_posts' => $board->grabPostCount(),
        'locked' => $board->getBoardLocked($User),
    );
   
    // If there is a required permission on this board, check it. 
    if($board->hasAccess($User) == true)
    {
        $BOARD_LIST[] = $BOARD; 
    }
} // end board loop

$renderer->assign('boards',$BOARD_LIST);
$renderer->display('boards/board_list.tpl');
?>
