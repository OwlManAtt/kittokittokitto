<p>You may send mail to up to five people at a time. Click the <strong>plus symbol</strong> to add more recipients. Do not worry about <strong>empty <em>To</em></strong> fields - as long as you fill at least one recipient in, having extra empty <em>To</em> fields will not cause any problems.</p> 

<div align='center'>
    <form action='{$display_settings.public_dir}/send-message' method='post'>
        <table class='inputTable' width='50%'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='to[1]'>To</label>
                </td>
                <td class='inputTableRow'>
                    <div id='to_cell' style='display: inline;'>
                        {foreach from=$to item=to_user key=current}
                        {math equation="x + 1" x=$current assign=current}
                        <input type='text' name='to[]' id='to[{$current}]' maxlength='25' class='to_field' value='{$to_user}' /> {if $current != $to_total}<br />{/if}
                        {foreachelse}
                        <input type='text' name='to[]' id='to[1]' maxlength='25' class='to_field' />
                        {/foreach}
                    </div>
                    {if $to_total < $max_to}<a href='#' id='add_to' onClick='return addToField("to_cell");'>+</a>{/if}
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='message[title]'>Title</label>
                </td>
                <td class='inputTableRowAlt'>
                    <input type='text' name='message[title]' id='message[title]' maxlength='255' size='63' value='{$message.title}' />
                </td>
            </tr>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='message[body]'>Body</label>
                </td>
                <td class='inputTableRow'>
                    <textarea name='message[body]' id='message[body]' cols='60' rows='15'>{$message.body}</textarea>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt'>&nbsp;</td>
                <td class='inputTableRowAlt' align='right'>
                    <input type='submit' value='Send' />
                </td>
            </tr>
        </table>
    </form>
</div>
