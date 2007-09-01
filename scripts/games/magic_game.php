<?php
/**
 *  
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
