<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='color_name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                <input type='text' name='color[name]' id='color_name' maxlength='30' value='{$color.name}' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='color_image'>Image</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead'>
                <input type='text' name='color[image]' id='color_image' value='{$color.image}' maxlength='200' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='color_base'>Base Color</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                {html_options id='color_base' name='color[base]' options=$base_options selected=$color.base}
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>
