<p>Note that the <strong>required permission</strong> field will only allow users with the specified permission to view the board. Leave this as 'None' for a public board. The required permission does <strong>not</strong> affect who can post to the board if it is locked.</p>

<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='board_name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='board_name_td'>
                <input type='text' name='board[name]' id='board_name' maxlength='30' value='{$board.name}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a name.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='description'>Description</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='board_description_td'>
                <input type='text' name='board[description]' id='description' maxlength='255' value='{$board.description}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a description.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='category'>Category</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='category_td'>
                {html_options id='category' name='board[board_category_id]' options=$categories selected=$board.board_category_id}<br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='locked'>Locked</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='locked_td'>
                {html_options id='locked' name='board[locked]' options=$y_n selected=$board.locked}<br />
                <span class='validate selectRequiredMsg'>You must pick an option.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='news_source'>News Source</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='news_source_td'>
                {html_options id='news_source' name='board[news_source]' options=$y_n selected=$board.locked}<br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='permission'>Required Permission</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='permission_td'>
                {html_options id='permission' name='board[required_permission_id]' options=$permissions selected=$board.required_permission_id}<br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='order_by'>Order By</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='order_by_td'>
                <input type='text' name='board[order_by]' id='order_by' maxlength='3' value='{$board.order_by}' size='4' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter an order by index.</span>
            </td>
        </tr>
        
        <tr>
            <td class='inputTableRowAlt' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>

{literal}
<script type='text/javascript'>
    var name = new Spry.Widget.ValidationTextField("board_name_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var description = new Spry.Widget.ValidationTextField("board_description_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var order = new Spry.Widget.ValidationTextField("order_by_td", "integer", {useCharacterMasking:true, validateOn:['change','blur']});    
    var locked = new Spry.Widget.ValidationSelect('locked_td',{validateOn:['blur','change'], invalidValue: '0'});
    var permission = new Spry.Widget.ValidationSelect('permission_td',{validateOn:['blur','change'], isRequired: false});
    var permission = new Spry.Widget.ValidationSelect('category_td',{validateOn:['blur','change'], invalidValue: '0'});
    var news_source = new Spry.Widget.ValidationSelect('news_source_td',{validateOn:['blur','change'], invalidValue: '0'});
</script>
{/literal}
