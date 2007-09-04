<div align='center'>
    <div class='pet-box' style='float:none;'>
        <div align='center'>
            {if $pet.image != null}<img src='{$pet.image}' border='0' alt='{$pet.name}' id='pet_image' />{else}No Image{/if}
        </div>
        <p><strong>{$pet.name}</strong>: {$pet.description}</p>
    </div>

    <p>To adopt your new <strong>{$pet.name}</strong>, just choose a name and a color!</p>
    
    <form action='{$display_settings.public_dir}/create-pet' method='post'>
        <input type='hidden' name='state' value='spawn' />
        <input type='hidden' name='pet[specie_id]' value='{$pet.id}' />
        
        <table class='inputTable' width='20%'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='pet[name]'>Name</label>
                </td>
                <td class='inputTableRow'>
                    <input type='text' name='pet[name]' id='pet[name]' maxlength='25' />
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='pet_color'>Color</label>
                </td>
                <td class='inputTableRowAlt'>
                    {html_options name='pet[color_id]' options=$colors id='pet_color' onChange="return imagePicker(this.form.pet_color[this.form.pet_color.selectedIndex].value,'`$display_settings.public_dir`/resources/pets/`$pet.image_dir`/','pet_image',false);"}
                </td>
            </tr>
            <tr>
                <td class='inputTableRow'>&nbsp;</td>
                <td align='right' class='inputTableRow'> 
                    <input type='submit' value='Adopt' />
                </td>
            </tr>
        </table>
    </form>
</div>
