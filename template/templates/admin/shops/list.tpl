{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<table class='dataTable' align='center' width='40%'>
    <tr>
        <td class='dataTableSubhead'>Shop</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
    </tr>

    {section name=index loop=$shops}
    {assign var=shop value=$shops[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' width='70%'>{$shop.name}</td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-shops-edit/' method='get'>
                <input type='hidden' name='shop[id]' value='{$shop.id}' />
                <input type='submit' value='Edit' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-shops/' method='post' onSubmit='return confirm("Are you sure you wish to delete {$shop.name}?");'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='shop[id]' value='{$shop.id}' />
                <input type='submit' value='Delete' />
            </form>
        </td>
    </tr>
    {sectionelse}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='3'><em>There are no shops!</em></td>
    </tr>

    {/section}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='3'>
            <form action='{$display_settings.public_dir}/admin-shops-edit/' method='get'>
                <input type='submit' value='New Shop' />
            </form>
        </td>
    </tr>
</table>
