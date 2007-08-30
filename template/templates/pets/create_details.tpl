<div align='center'>
    <div class='pet-box' style='float:none;'>
        <div align='center'>
            {if $pet.image != null}<img src='{$pet.image}' border='0' alt='{$pet.name}' />{else}No Image{/if}
        </div>
        <p><strong>{$pet.name}</strong>: {$pet.description}</p>
    </div>

    <p>To adopt your new <strong>{$pet.name}</strong>, just choose a name and a color!</p>
    
    <form action='{$display_settings.public_dir}/create-pet' method='post'>
        <input type='hidden' name='state' value='spawn' />
        <input type='hidden' name='pet[specie_id]' value='{$pet.id}' />
        
        <table class='dataTable' width='20%'>
            <tr>
                <td class='dataTableSubhead'>
                    <label for='pet[name]'>Name</label>
                </td>
                <td class='dataTableRow'>
                    <input type='text' name='pet[name]' id='pet[name]' maxlength='25' />
                </td>
            </tr>
            <tr>
                <td class='dataTableSubhead'>
                    <label for='pet[color_id]'>Color</label>
                </td>
                <td class='dataTableRow'>
                    {html_options name='pet[color_id]' options=$colors id='pet[color_id]'}
                </td>
            </tr>
            <tr>
                <td align='right' class='dataTableRow' colspan='2'> 
                    <input type='submit' value='Adopt' />
                </td>
            </tr>
        </table>
    </form>
</div>
