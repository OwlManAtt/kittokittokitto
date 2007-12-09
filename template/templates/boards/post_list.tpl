<div id='breadcrumb-trail'>{kkkurl link_text='Boards' slug='boards'} &raquo; {$board.category} &raquo; {kkkurl link_text=$board.name slug='threads' args=$board.id} &raquo; {if $thread.sticky == 1}Sticky: {/if}{$thread.name}{if $thread.can_edit == 1} <span style='color: gray;'>({kkkurl link_text='Change Topic' slug='edit-thread' args=`$thread.id`/`$page`})</span>{/if}</div>

{if $board_notice != ''}<p id='forum_notice' class='{$fat} notice-box'>{$board_notice}</p>{/if}

{section name=index loop=$posts}
{include file='boards/_post.tpl' post=$posts[index] locked=$thread.locked}
{sectionelse}
<p>There are no posts on this page!</p> 
{/section}

<br clear='all' />
<div class='pages'>{$pagination}</div>

{if $thread.locked == 'N'}<div align='center'>
    <a name='post'>&nbsp;</a>
    <div class='quick-reply'>
        <form action='{$display_settings.public_dir}/thread-reply/' method='post'>
            <input type='hidden' name='thread_id' value='{$thread.id}' />

            <table border='0'>
                <tr>
                    <td style='vertical-align: top; font-weight: bold; font-size: large;'>
                        <label for='post_text'>Message</label>
                    </td>
                    <td colspan='2' id='post_text_td'>
                        <textarea name='post[text]' id='post_text' cols='60' rows='15'></textarea><br />
                        <span class='validate textareaRequiredMsg'>You must enter a message.</span>
                    </td>
                </tr>
                <tr>
                    <td align='right' colspan='3'>
                        <input type='submit' value='Reply' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

{literal}
<script type='text/javascript'>
    var card = new Spry.Widget.ValidationTextarea('post_text_td');
</script>
{/literal}
{/if}
