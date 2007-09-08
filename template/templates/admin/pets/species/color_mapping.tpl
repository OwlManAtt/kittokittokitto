<p>You are viewing the color mapping for the <strong>{$specie.name}</strong> species.</p>

{if $notice != ''}<p id='notice_box' class='{$fat} notice-box'>{$notice}</p>{/if}

<div align='center'>
    <table class='dataTable' width='30%'>
        <tr>
            <td class='dataTableSubhead'>Color</td>
            <td class='dataTableSubhead'>Available</td>
            <td class='dataTableSubhead'>&nbsp;<td>
        </tr>
        {section name=index loop=$colors}
        {assign var=color value=$colors[index]}
        {cycle values='dataTableRow,dataTableRowAlt' assign=class}
         <tr>
            <td class='{$class}'>{$color.name}</td>
            <td class='{$class}' width='20%'>{if $color.built == 1}Yes{else}No{/if}</td>
            <td class='{$class}' width='20%'>
                <form action='{$display_settings.public_dir}/admin-pet-specie-colors/' method='post'>
                    <input type='hidden' name='state' value='toggle' />
                    <input type='hidden' name='specie[id]' value='{$specie.id}' />
                    <input type='hidden' name='color[id]' value='{$color.id}' />
                    <input type='submit' value='{if $color.built == 1}Disable{else}Enable{/if}' />
                </form> 
            <td>
        </tr>
        {sectionelse}
        <tr>
            <td class='dataTableHead' colspan='3' align='center'><em>No colors found.</em><td>
        </tr>
        {/section}
    </table>
</div>
