<p align='center'>{kkkurl link_text='Inbox' slug='messages'}</p>

<table class='inputTable' width='70%'>
     <tr>
        <td class='inputTableRow inputTableSubhead' width='8%'>From</td>
        <td class='inputTableRow'>{kkkurl link_text=$message.from.name slug='profile' args=$message.from.id}</td>
    </tr>
    <tr>
        <td class='inputTableRowAlt inputTableSubhead'>To</td>
        <td class='inputTableRowAlt'>{$message.recipients}</td>
    </tr>
    <tr>
        <td class='inputTableRow inputTableSubhead'>Sent</td>
        <td class='inputTableRow'>{$message.sent_at}</td>
    </tr>
    <tr>
        <td class='inputTableRowAlt inputTableSubhead'>Title</td>
        <td class='inputTableRowAlt'>{$message.title|wordwrap:80:"<br />\n":true}</td>
    </tr>
    <tr>
        <td class='inputTableRow inputTableSubhead'>Body</td>
        <td class='inputTableRow' style='vertical-align:top; height: 10em; padding-top: 0; padding-bottom: 0;'>{$message.body}</td>
    </tr>
    <tr>
        <td class='inputTableRowAlt'>&nbsp;</td>
        <td class='inputTableRowAlt' align='right'>
            <form action='{$display_settings.public_dir}/messages/' method='post' class='inline-form'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='delete[]' value='{$message.id}' />
                <input type='submit' value='Delete' />
            </form>
            <form action='{$display_settings.public_dir}/write-message-reply/{$message.id}' method='get' class='inline-form'>
                <input type='submit' value='Reply' />
            </form>
            <form action='{$display_settings.public_dir}/write-message-reply/{$message.id}/all' method='get' class='inline-form'>
                <input type='submit' value='Reply to All' />
            </form>
        </td>
    </tr>
</table>
