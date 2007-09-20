{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<table class='dataTable' align='center' width='50%'>
    <tr>
        <td class='dataTableSubhead'>Name</td>
        <td class='dataTableSubhead'>Type</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
    </tr>

    {section name=index loop=$items}
    {assign var=item value=$items[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' width='40%'>{$item.name}</td>
        <td class='{$class}' width='30%'>{$item.type}</td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-restock/' method='get'>
                <input type='hidden' name='item[id]' value='{$item.id}' />
                <input type='submit' value='Restocks' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-items-edit/' method='get'>
                <input type='hidden' name='item[id]' value='{$item.id}' />
                <input type='submit' value='Edit' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-items/' method='post' onSubmit='return confirm("Are you sure you wish to delete the {$item.name} item?");'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='item[id]' value='{$item.id}' />
                <input type='submit' value='Delete' />
            </form>
        </td>
    </tr>
    {sectionelse}
    <tr>
        <td class='dataTableRow' align='center' colspan='5'>
            <em>There are no items!</em>
        </td>
    </tr>
    {/section}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='5'>
            <form action='{$display_settings.public_dir}/admin-items-add/' method='get'>
                <input type='submit' value='New Item' />
            </form>
        </td>
    </tr>
</table>
