<p>Below is a list of pet species. A specie cannot be adopted unless it is marked as available <em>and</em> has one of the basic colors turned on.</p>

{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<table class='dataTable' align='center' width='40%'>
    <tr>
        <td class='dataTableSubhead'>Specie</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead'>&nbsp;</td>
    </tr>

    {section name=index loop=$species}
    {assign var=specie value=$species[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' width='70%'>{$specie.name}</td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-pet-species-edit/' method='get'>
                <input type='hidden' name='specie[id]' value='{$specie.id}' />
                <input type='submit' value='Edit' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-pet-specie-colors/' method='get'>
                <input type='hidden' name='specie[id]' value='{$specie.id}' />
                <input type='submit' value='Colors' />
            </form>
        </td>
        <td class='{$class}' align='center'>
            <form action='{$display_settings.public_dir}/admin-pet-species/' method='post' onSubmit='return confirm("Are you sure you wish to delete {$specie.name}?");'>
                <input type='hidden' name='state' value='delete' />
                <input type='hidden' name='specie[id]' value='{$specie.id}' />
                <input type='submit' value='Delete' />
            </form>
        </td>
    </tr>
    {sectionelse}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='4'><em>There are no species!</em></td>
    </tr>

    {/section}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center' colspan='4'>
            <form action='{$display_settings.public_dir}/admin-pet-species-edit/' method='get'>
                <input type='submit' value='New Specie' />
            </form>
        </td>
    </tr>
</table>
