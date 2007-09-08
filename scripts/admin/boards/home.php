<?php
/**
 * Board Administration Panel
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

switch($_REQUEST['state'])
{
    default:
    {
        $ERRORS = array();
        $BOARDS = array();
        $boards = new Board($db);
        $boards = $boards->findBy(array(),'ORDER BY board.order_by ASC');

        foreach($boards as $board)
        {
            $BOARDS[] = array(
                'id' => $board->getBoardId(),
                'name' => $board->getBoardName(),
            );
        } // end board reformatting loop

        if($_SESSION['permission_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['permission_notice']);
            unset($_SESSION['permission_notice']);
        }

        if(count($BOARDS)!=0) {
            $renderer->assign('boards',$BOARDS);
            $renderer->display('admin/boards/list.tpl');
        }
        else
        {
            $ERRORS[] = 'No Boards.  You must create a board.';
        }
        
        break;
    } // end default

    case 'delete':
    {
        $ERRORS = array();
        $board_id = stripinput($_POST['board']['id']);
        
        $board = new Board($db);
        $board = $group->findByOneBoardId($board_id);

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
            $name = $board->getBoardName();
            $board->destroy();

            $_SESSION['permission_notice'] = "You have deleted the <strong>$name</strong> board.";
            redirect('admin-boards');
        } // end no errors

        break;
    } // end delete
    case 'create':
    {
        // LOL
        $boards = new Board($db);
        $insert = array(
            'board_name'   => $_POST['board']['name'],
            'board_descr'  => $_POST['board']['descr'],
            'board_locked' => $_POST['board']['locked'],
            'news_source'  => $_POST['board']['news_source'],
            'order_by'     => $_POST['board']['order_by']
            );
        $boards->create($insert);
    } // end create
} // end state switch
?>
