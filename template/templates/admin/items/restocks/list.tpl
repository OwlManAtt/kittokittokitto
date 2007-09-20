{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<table class='dataTable' align='center' width='40%'>
    <tr>
        <td class='dataTableSubhead'>Shop</td>
        <td class='dataTableSubhead'>Frequency</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
    </tr>

    {section name=index loop=$restocks}
    {assign var=restock value=$restocks[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' width='40%'>{$restock.shop_name}</td>
        <td class='{$class}' width='30%'>{$restock.frequency}</td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-restock-edit/' method='get'>
                <input type='hidden' name='item[id]' value='{$item.id}' />
                <input type='hidden' name='restock[id]' value='{$restock.id}' />
                <input type='submit' value='Edit' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-restock/' method='post' onSubmit='return confirm("Are you sure you wish to delete the {$restock.name} restock?");'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='item[id]' value='{$item.id}' />
                <input type='hidden' name='restock[id]' value='{$restock.id}' />
                <input type='submit' value='Delete' />
            </form>
        </td>
    </tr>
    {sectionelse}
    <tr>
        <td class='dataTableRowAlt' align='center' colspan='4'>
            <em>This item does not stock anywhere.</em>
        </td>
    </tr>
    {/section}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='4'>
            <form action='{$display_settings.public_dir}/admin-restock-edit/' method='get'>
                <input type='hidden' name='item[id]' value='{$item.id}' />
                <input type='submit' value='Add' />
            </form>
        </td>
    </tr>
</table>
