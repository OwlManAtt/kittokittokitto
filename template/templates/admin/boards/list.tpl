{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<table class='dataTable' align='center' width='40%'>
    <tr>
        <td class='dataTableSubhead'>Board</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
    </tr>

    {section name=index loop=$boards}
    {assign var=board value=$boards[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' width='70%'>{$board.name}</td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-boards-edit/' method='get'>
                <input type='hidden' name='board[id]' value='{$board.id}' />
                <input type='submit' value='Edit' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-boards/' method='post' onSubmit='return confirm("Are you sure you wish to delete the {$board.name} board?");'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='board[id]' value='{$board.id}' />
                <input type='submit' value='Delete' />
            </form>
        </td>
    </tr>
    {/section}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='3'>
            <form action='{$display_settings.public_dir}/admin-boards-edit/' method='get'>
                <input type='submit' value='New Board' />
            </form>
        </td>
    </tr>
</table>
