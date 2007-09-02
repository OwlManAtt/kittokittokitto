<div id='breadcrumb-trail'>{kkkurl link_text='Boards' slug='boards'} &raquo; {kkkurl link_text=$board.name slug='threads' args=$board.id} &raquo; {if $thread.sticky == 1}Sticky: {/if}{kkkurl link_text=$thread.name slug='thread' args=`$thread.id`/`$page`} &raquo; Edit Thread Title</div>

<div align='center'>
    <div class='quick-reply'>
        <form action='{$display_settings.public_dir}/edit-thread/' method='post'>
            <input type='hidden' name='state' value='save' />
            <input type='hidden' name='thread_id' value='{$thread.id}' />
            <input type='hidden' name='page' value='{$page}' />

            <table border='0'>
                <tr>
                    <td style='font-weight: bold; font-size: large;'>
                        <label for='thread[title]'>Title</title>
                    </td>
                    <td>
                        <input type='text' name='thread[title]' id='thread[title]' maxlength='255' size='81' value='{$thread.name}' />
                    </td>
                </tr>
                <tr>
                    <td align='right' colspan='3'>
                        <input type='submit' value='Save' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
