<?php
/**
 * An incredibly easy game of odds. This serves as a demo.
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
 * @subpackage Games
 * @version 1.0.0
 **/

$CARDS = array(
    'ace' => 'Ace of Spades',
    'queen' => 'Queen of Hearts',
    'three' => 'Three of Clubs',
    'seven' => 'Seven of Diamonds',
    'joker' => 'Joker Pierrot',
);
$prize = 25;
$cost = 50;

switch($_REQUEST['state'])
{
    default:
    {
        $CARD_LIST = array('' => 'Select one...');
        foreach($CARDS as $i => $name)
        {
            $CARD_LIST[$i] = $name;
        }
       
        if($_SESSION['magic_game'] != null)
        {
            $renderer->assign('result',$_SESSION['magic_game']);
            unset($_SESSION['magic_game']);
        }
        
        $renderer->assign('cost',$cost);
        $renderer->assign('prize',$prize);
        $renderer->assign('cards',$CARD_LIST);
        $renderer->display('games/magic/wager.tpl');

        break;
    } // end default

    case 'guess':
    {
        $guess = stripinput($_POST['card']);

        if(in_array($guess,array_keys($CARDS)) == false)
        {
            draw_errors('You have to pick a card.');
        }
        else
        {
            $random = array_rand($CARDS);
            $choice = $CARDS[$random];

            if($random == $guess)
            {
                $User->subtractCurrency($cost);
                
                $_SESSION['magic_game'] = "Got your card? Alright...I think you have the <strong>$choice</strong>! What, I'm right? Woohoo, that'll be ".format_currency($cost)."!";
            }
            else
            {
                $User->addCurrency($prize);
                
                $_SESSION['magic_game'] = "Got your card? Alright...I think you have the <strong>$choice</strong>! What, I'm wrong? Let me see..aww, shucks. I guess that's ".format_currency($prize)." to you...";
            }

            redirect('magic-game');
        } // end card selected

        break;
    } // end guess
} // end state switch

?>
