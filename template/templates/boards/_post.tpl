<div id=post_{$post.id} class='board-post'>
    <div class='post-userinfo'>
        <ul>
            <li class='username'>{kkkurl link_text=$post.username slug='profile' args=$post.user_id}</li>
            <li>{$post.user_title}</li>
            <li><strong>Posts</strong>: {$post.user_post_count|number_format}</li>
        </ul>
        
        {if $post.avatar_url != ''} 
        <p align='center'>
            <img src='{$post.avatar_url}' alt='{$post.avatar_name}' border='1' />
        </p>
        {/if}

        <p align='center'><a onClick="quote('post-{$post.id}-message','post_text')">[Quote]</a></p>
    </div>
    <div class='post-content'>
        <p class='post-content-header'>Posted at {$post.posted_at} &mdash; {kkkurl link_text='Link' slug='thread' args=`$thread.id``$page`#`$post.id` name=$post.id}</p>
        <div id='post-{$post.id}-message'>{$post.text}</div>
        {if $post.signature != ''}<div class='post-signature'>{$post.signature}</div>{/if}
    </div>
    <br clear='all' />
</div>
