<div align='center'>
    <p>Who would you like to give your <strong>{$item_name}</strong> to?</p>

    <form action='{$display_settings.public_dir}/item' method='post'>
        <input type='hidden' name='state' value='give_process' />
        <input type='hidden' name='give[item_id]' value='{$item_id}' />
    
        <table width='20%' class='inputTable'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='username'>Username</label>
                </td>
                <td class='inputTableRow' id='username_td'>
                    <input type='text' name='give[username]' id='username' maxlength='25' /><br />
                    <span class='textfieldRequiredMsg valid'>You must pick a user.</span>
                </td>
            </tr>
            <tr>
                <td colspan='2' class='inputTableRowAlt' style='text-align: right;'>
                    <input type='submit' value='Give' />
                </td>
            </tr>
        </table>
    </form>
</div>

{literal}
<script type='text/javascript'>
    var user_name = new Spry.Widget.ValidationTextField("username_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
</script>
{/literal}
