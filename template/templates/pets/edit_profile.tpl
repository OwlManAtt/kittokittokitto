{if $notice != ''}<p id='pet_notice' class='{$fat} notice-box'>{$notice}</p>{/if}

<div align='center'>
    <form action='{$display_settings.public_dir}/edit-pet' method='post'>
        <input type='hidden' name='state' value='save' />
        <input type='hidden' name='pet_id' value='{$pet.id}' />
    
        <table class='inputTable'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>Pet</td>
                <td class='inputTableRow'>{kkkurl link_text=$pet.name slug='pet' args=$pet.id}</td>
            </tr> 
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='pet[profile]'>Profile</label>
                </td>
                <td class='inputTableRowAlt'>
                    <textarea name='pet[profile]' id='pet[profile]' cols='60' rows='15'>{$pet.profile}</textarea> 
                </td>
            </tr> 
            <tr>
                <td class='inputTableRow'>&nbsp;</td>
                <td class='inputTableRow' align='right'>
                    <input type='submit' value='Save' />
                </td>
            </tr> 
        </table>
    </form>
</div>
