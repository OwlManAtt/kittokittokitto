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

// LOL
switch($_REQUEST['state'])
{
    default:
    {

        if($_SESSION['permission_notice'] != null)
        {
            $renderer->assign('notice',$_SESSION['permission_notice']);
            unset($_SESSION['permission_notice']);
        }
        $renderer->display('admin/boards/create.tpl');
        break;
    }
    case 'create':
    {
        $ERRORS = array();
        $boards = new Board($db);

        $BOARD = array(
            'board_name'   => $_POST['board']['name'],
            'board_descr'  => $_POST['board']['descr'],
            'board_locked' => $_POST['board']['locked'],
            'news_source'  => $_POST['board']['news_source'],
            'order_by'     => $_POST['board']['order_by']
        );

        if($BOARD['board_name'] == null) { ## max 100
            $ERRORS[] = "No board name given.";
        }
        elseif(strlen($BOARD['board_name']) > 10) {
            $ERRORS[] = "Board name too long.";
        }
        
        if($BOARD['board_descr'] == null) { ## max 2^8-1
            $ERRORS[] = "No board description given.";
        }
        elseif(strlen($BOARD['board_descr']) > 255) {
            $ERRORS[] = "Board description too long.";
        }
        

        if($BOARD['board_locked'] == null) {
            $ERRORS[] = "Board Locked attribute undefined.";
        }

        if($BOARD['news_source'] == null) {
            $ERRORS[] = "News Source attribute undefined.";
        }

        if($BOARD['order_by'] == null) {
            $ERRORS[] = "Order By cannot be left blank";
        }
        
        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            $name = $_POST['board']['name'];
            $_SESSION['permission_notice'] = "You have created the <strong>$name</strong> board.";
            $boards->create($BOARD);
            redirect('admin-boards');
        } // end no errors
    } // end delete
} // end state switch
?> 
