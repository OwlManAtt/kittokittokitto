<p>You may select a new password for your account. You will be logged in after you complete this step.</p>

<form action='{$display_settings.public_dir}/reset-password' method='post'>
    <input type='hidden' name='state' value='change' />
    <input type='hidden' name='substate' value='process' />
    <input type='hidden' name='user_id' value='{$user_id}' />
    <input type='hidden' name='confirm' value='{$confirm}' />

    <table class='inputTable' width='30%'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='password_a'>New Password</label>
            </td>
            <td class='inputTableRow' id='password_a_td'>
                <input type='password' name='password[a]' id='password_a' /><br />
                <span class='textfieldRequiredMsg valid'>You must pick a password.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='password_b'>Repeat Password</label>
            </td>
            <td class='inputTableRowAlt' id='password_b_td'>
                <input type='password' name='password[b]' id='password_b' /><br />
                <span class='textfieldRequiredMsg valid'>You must repeat your password.</span>
                <span class='textfieldInvalidFormatMsg valid'>Passwords does not match.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow' colspan='2' style='text-align: right;'>
                <input type='submit' value='Change' />
            </td>
        </tr>
    </table>
</form>

{literal}
<script type='text/javascript'>
    var passwordTheSame = function(value,options) {
        var other_value = document.getElementById('password_a').value;
        if(value != other_value) return false;

        return true;
    } // end anon

    var password = new Spry.Widget.ValidationTextField("password_a_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var password_again = new Spry.Widget.ValidationTextField("password_b_td", "custom", {validation: passwordTheSame, validateOn:['change','blur']});    
</script>
{/literal}
