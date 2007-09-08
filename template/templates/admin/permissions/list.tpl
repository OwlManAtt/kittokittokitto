<p>Shown is a list of staff groups. Hit edit to modify the group's name, description, and permissions.</p>

{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<table class='dataTable' align='center' width='40%'>
    <tr>
        <td class='dataTableSubhead'>Group</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
    </tr>

    {section name=index loop=$groups}
    {assign var=group value=$groups[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' width='70%'>{$group.name}</td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-permissions-edit/' method='get'>
                <input type='hidden' name='group[id]' value='{$group.id}' />
                <input type='submit' value='Edit' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-permissions/' method='post' onSubmit='return confirm("Are you sure you wish to delete the {$group.name} group?");'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='group[id]' value='{$group.id}' />
                <input type='submit' value='Delete' />
            </form>
        </td>
    </tr>
    {/section}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='3'>
            <form action='{$display_settings.public_dir}/admin-permissions-edit/' method='get'>
                <input type='submit' value='New Group' />
            </form>
        </td>
    </tr>
</table>
