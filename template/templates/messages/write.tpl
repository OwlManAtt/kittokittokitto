<p>You may send mail to up to five people at a time. Click the <strong>plus symbol</strong> to add more recipients. Do not worry about <strong>empty <em>To</em></strong> fields - as long as you fill at least one recipient in, having extra empty <em>To</em> fields will not cause any problems.</p> 

<div align='center'>
    <form action='{$display_settings.public_dir}/send-message' method='post'>
        <table class='inputTable' width='50%'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='to[1]'>To</label>
                </td>
                <td class='inputTableRow' id='to_td'>
                    <div id='to_cell' style='display: inline;'>
                        {foreach from=$to item=to_user key=current}
                        {math equation="x + 1" x=$current assign=current}
                        <input type='text' name='to[]' id='to[{$current}]' maxlength='25' class='to_field' value='{$to_user}' /> {if $current != $to_total}<br />{/if}
                        {foreachelse}
                        <input type='text' name='to[]' id='to[1]' maxlength='25' class='to_field' />
                        {/foreach}
                    </div>
                    {if $to_total < $max_to}<a href='#' id='add_to' onClick='return addToField("to_cell");'>+</a><br />{/if}
                    <span class='validate textfieldRequiredMsg'>You must at least one recipient.</span>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='title'>Title</label>
                </td>
                <td class='inputTableRowAlt' id='title_td'>
                    <input type='text' name='message[title]' id='title' maxlength='255' size='63' value='{$message.title}' /><br />
                    <span class='validate textfieldRequiredMsg'>You must enter a title.</span>
                </td>
            </tr>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='body'>Body</label>
                </td>
                <td class='inputTableRow' id='body_td'>
                    <textarea name='message[body]' id='body' cols='60' rows='15'>{$message.body}</textarea><br />
                    <span class='validate textareaRequiredMsg'>You must enter a message.</span>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt' colspan='2' style='text-align: right;'>
                    <input type='submit' value='Send' />
                </td>
            </tr>
        </table>
    </form>
</div>

{literal}
<script type='text/javascript'>
    var to_1 = new Spry.Widget.ValidationTextField("to_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var title = new Spry.Widget.ValidationTextField("title_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var post_text = new Spry.Widget.ValidationTextarea('body_td');
</script>
{/literal}
