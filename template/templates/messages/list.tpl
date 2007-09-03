{if $notice != ''}<p id='message_notice' class='{$fat} notice-box'>{$notice}</p>{/if}

<p align='center'>{kkkurl link_text='Compose New Message' slug='write-new-message'}</p>

<form action='{$display_settings.public_dir}/messages' method='post' onSubmit='return confirm("Are you sure you wish to delete the selected messages?")'>
    <input type='hidden' name='state' value='delete' />

    <table class='dataTable' width='70%'>
        <tr>
            <td class='dataTableSubhead' align='center' width='45%'>Title</td>
            <td class='dataTableSubhead' align='center'>Sender</td>
            <td class='dataTableSubhead' align='center'>Sent At</td>
            <td class='dataTableSubhead' align='center' width='5%'>
                <a href='#' onClick='return tickAll("delete_box")'>Delete?</a>
            </td>
        </tr>
        {section name=index loop=$messages}
        {assign var=message value=$messages[index]}
        {cycle values='dataTableRow,dataTableRowAlt' assign=class}
        <tr>
            <td class='{$class}' align='center'{if $message.read == 'N'} style='font-weight: bold;'{/if}>{kkkurl link_text=$message.title slug='message' args=$message.id}</td>
            <td class='{$class}' align='center'>{kkkurl link_text=$message.sender.name slug='profile' args=$message.sender.id}</td>
            <td class='{$class}' align='center'>{$message.sent_at}</td>
            <td class='{$class}' align='center'>
                <input type='checkbox' name='delete[{$message.id}]' id='delete[{$message.id}]' class='delete_box' value='{$message.id}' />
            </td>
        </tr> 
        {sectionelse}
        <tr>
            <td class='dataTableRow' colspan='4' align='center' style='font-size: x-large; padding: .5em;'>You have no messages.</td>
        </tr>
        {/section}
        <tr>
            <td class='' colspan='3'>&nbsp;</td>
            <td class='' align='right'>
                <input type='submit' value='Delete' />
            </td>
        </tr>
    </table>
</form>

<div class='pages'>{$pages}</div>
