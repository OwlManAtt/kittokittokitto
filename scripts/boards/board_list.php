<?php
/**
 *  
 **/

$BOARD_LIST = array();
$boards = new Board($db);
$boards = $boards->findBy(array(),'ORDER BY order_by');

foreach($boards as $board)
{
    $BOARD_LIST[] = array(
        'id' => $board->getBoardId(),
        'name' => $board->getBoardName(),
        'description' => $board->getBoardDescr(),
        'last_poster' => $board->getLastPosterUsername(), 
        'total_posts' => $board->grabPostCount(),
        'locked' => $board->getBoardLocked($User),
    );
} // end board loop

$renderer->assign('boards',$BOARD_LIST);
$renderer->display('boards/board_list.tpl');
?>
