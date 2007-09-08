<p>This is a list of all potential pet colors. If a color is marked as a base color, then it is an option when the user is creating a pet. Otherwise, it is a restricted color and they will require a painting item to gain it.</p>
<p>Not all pets must have all colors. There is a mapping of color to pet on the 'Edit Pets' page.</p>

{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<table class='dataTable' align='center' width='40%'>
    <tr>
        <td class='dataTableSubhead'>Color</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
    </tr>

    {section name=index loop=$colors}
    {assign var=color value=$colors[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' width='70%'>{$color.name}</td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-pet-colors-edit/' method='get'>
                <input type='hidden' name='color[id]' value='{$color.id}' />
                <input type='submit' value='Edit' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-pet-colors/' method='post' onSubmit='return confirm("Are you sure you wish to delete {$color.name}?");'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='color[id]' value='{$color.id}' />
                <input type='submit' value='Delete' />
            </form>
        </td>
    </tr>
    {sectionelse}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='3'><em>There are no colors!</em></td>
    </tr>

    {/section}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='3'>
            <form action='{$display_settings.public_dir}/admin-pet-colors-edit/' method='get'>
                <input type='submit' value='New Color' />
            </form>
        </td>
    </tr>
</table>
