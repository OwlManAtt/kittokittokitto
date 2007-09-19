<?php
/**
 * Board admin. 
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

switch($_REQUEST['state'])
{
    default:
    {
        $boards = new Board($db);
        $boards = $boards->findBy(array(),'ORDER BY board.order_by ASC');

        $SHOPS = array();
        foreach($boards as $board)
        {
            $SHOPS[] = array(
                'id' => $board->getBoardId(),
                'name' => $board->getBoardName(),
                'image' => $board->getBoardImage(),
                'welcome_text' => $board->getWelcomeText(),
            );
        } // end board reformatter

        if($_SESSION['board_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['board_notice']);
            unset($_SESSION['board_notice']);
        } 

        $renderer->assign('boards',$SHOPS);
        $renderer->display('admin/boards/list.tpl');

        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        $board_id = stripinput($_POST['board']['id']);
        
        $board = new Board($db);
        $board = $board->findOneByBoardId($board_id);

        if($board == null)
        {
            $ERRORS[] = 'Invalid board specified.';
        }

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            // Hold this for the delete message.
            $name = $board->getBoardName();
            $board->destroy();
            
            $_SESSION['board_notice'] = "You have deleted <strong>$name</strong>.";
            redirect('admin-boards');
        } // end no errors

        break;
    } // end delete

} // end state switch
?>
