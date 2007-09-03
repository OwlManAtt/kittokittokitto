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
        'sticky' => $thread->getStickied(),
        'can_edit' => (($User->hasPermission('edit_post') == true)) ? true : false,
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
            'signature' => $post->getSignature(),
            'avatar_url' => $post->getAvatarUrl(),
            'avatar_name' => $post->getAvatarName(),
            'user_post_count' => $post->getPostCount(),
            'page' => $page_id,
            'can_edit' => (($User->hasPermission('edit_post') == true)) ? true : false,
        );
    } // end thread loop

    $ADMIN_ACTIONS = array('' => 'Moderation...');

    if($User->hasPermission('delete_post') == true)
    {
        $ADMIN_ACTIONS['delete_post'] = 'Delete Post';
        $ADMIN_ACTIONS['delete_thread'] = 'Delete Thread';
    }

    if($User->hasPermission('manage_thread') == true)
    {
        if($thread->getLocked() == 'N')
        {
            $ADMIN_ACTIONS['lock'] = 'Lock Thread'; 
        }
        else
        {
            $ADMIN_ACTIONS['lock'] = 'Unock Thread'; 
        }
         
        if($thread->getStickied() == 0)
        {
            $ADMIN_ACTIONS['stick'] = 'Stick Thread';
        }
        else
        {
            $ADMIN_ACTIONS['stick'] = 'Unstick Thread';
        }
    } // end thread management
    
    if(sizeof($ADMIN_ACTIONS) > 1)
    {
        $renderer->assign('actions',$ADMIN_ACTIONS);
    }

    if($_SESSION['board_notice'] != null)
    {
        $renderer->assign('board_notice',$_SESSION['board_notice']);
        unset($_SESSION['board_notice']);
    }
    
    $renderer->assign('board',$BOARD_DATA);    
    $renderer->assign('thread',$THREAD_DATA);    
    $renderer->assign('posts',$POST_LIST);
    $renderer->assign('page',$page_id);
    $renderer->assign('pagination',$pagination);
    $renderer->display('boards/post_list.tpl');
} // end board exists
?>
