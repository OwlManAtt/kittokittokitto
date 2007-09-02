<?php
/**
 *  
 **/

$max_items_per_page = 15;

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{
    $page_id = 1;
}

// Where do we slice the record set? (Note: Don't worry about
// LIMIT X,Y starting from zero - that'll be abstracted away).
$start = (($page_id - 1) * $max_items_per_page);
$end = (($page_id - 1) * $max_items_per_page) + $max_items_per_page;

// Load the board.
$thread_id = stripinput($_REQUEST['thread_id']);
$thread = new BoardThread($db);
$thread = $thread->findOneByBoardThreadId($thread_id);

if($thread == null)
{
    draw_errors('Invalid thread specified.');
}
else
{
    $THREAD_DATA = array(
        'id' => $thread->getBoardThreadId(),
        'name' => $thread->getThreadName(),
        'locked' => $thread->getLocked(),
    );
    
    // Load the board info.
    $board = new Board($db);
    $board = $board->findOneByBoardId($thread->getBoardId());
    
    $BOARD_DATA = array(
        'id' => $board->getBoardId(),
        'name' => $board->getBoardName(),
        'locked' => $board->getBoardLocked($User),
    );
    
    // Generate the pagination. 
    $pagination = pagination("thread/{$thread->getBoardThreadId()}",$thread->grabPostsSize(),$max_items_per_page,$page_id);
    
    $POST_LIST = array();
    $posts = $thread->grabPosts($start,$end);

    foreach($posts as $post)
    {
        $POST_LIST[] = array(
            'id' => $post->getBoardThreadPostId(),
            'posted_at' => $User->formatDate($post->getPostedDatetime()),
            'text' => $post->getPostText(),
            'user_id' => $post->getUserId(), 
            'username' => $post->getUserName(),
            'user_title' => $post->getUserTitle(),
            'signature' => $post->getUserSignature(),
            'avatar_url' => $post->getAvatarUrl(),
            'avatar_name' => $post->getAvatarName(),
            'user_post_count' => $post->getPostCount(),
            'page' => $page_id,
        );
    } // end thread loop

    $renderer->assign('board',$BOARD_DATA);    
    $renderer->assign('thread',$THREAD_DATA);    
    $renderer->assign('posts',$POST_LIST);
    $renderer->assign('pagination',$pagination);
    $renderer->display('boards/post_list.tpl');
} // end board exists
?>
