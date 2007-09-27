<div align='center'> 
    <form action='{$display_settings.public_dir}/search' method='get' id='search_form'> 
        <input type='hidden' name='state' value='passthrough' />
        
        <table class='inputTable' width='30%'>
            <tr>
                <td colspan='2' class='inputTableHead'>Search</td>
            </tr>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='keyword'>Keyword</td>
                </td>
                <td class='inputTableRow' id='keyword_td'>
                    <input type='text' name='keyword' id='keyword' /><br />
                    <span class='textfieldRequiredMsg valid'>You must enter a keyword.</span>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='search'>Search For</td>
                </td>
                <td class='inputTableRowAlt' id='search_td'>
                    <select name='search' id='search'>
                        <option value='user'>Users</option>
                        <option value='pet'>Pets</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='match'>Match</td>
                </td>
                <td class='inputTableRow' id='match_td'>
                    <select name='match' id='match'>
                        <option value='contains'>Contains Keyword</option>
                        <option value='exact'>Exact Match</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt' colspan='2' style='text-align: right;'>
                    <input type='submit' value='Search' />
                </td>
            </tr>
        </table>
    </form>
</div>

{literal}
<script type='text/javascript'>
    var keyword = new Spry.Widget.ValidationTextField("keyword_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var type = new Spry.Widget.ValidationSelect('search_td',{validateOn:['blur','change']});
    var match = new Spry.Widget.ValidationSelect('match_td',{validateOn:['blur','change']});
</script>
{/literal}
