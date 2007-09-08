<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='specie_name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                <input type='text' name='specie[name]' id='specie_name' maxlength='30' value='{$specie.name}' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='specie_descr'>Description</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead'>
                <textarea id='specie_descr' name='specie[descr]' cols='45' rows='10'>{$specie.description}</textarea>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='specie_dir'>Image Directory</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                <input type='input' name='specie[image_dir]' id='specie_dir' value='{$specie.image_dir}' maxlength='200'/>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='specie_hunger'>Max Hunger</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead'>
                <input type='text' name='specie[hunger]' id='specie_hunger' maxlength='3' size='3' value='{$specie.max_hunger}' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='specie_happiness'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                <input type='text' name='specie[happiness]' id='specie_happiness' maxlength='3' size='3' value='{$specie.max_happiness}' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='specie_available'>Available</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead'>
                {html_options id='specie_available' name='specie[available]' options=$available_options selected=$specie.available}
            </td>
        </tr>
        <tr>
            <td class='inputTableRow' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>
