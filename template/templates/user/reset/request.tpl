<p>If you have forgotten your password, you can reset it by filling out the form below. An e-mail will be sent to verify that it was, in fact, you who initiated the reset process.</p>

{if $notice != ''}<p id='reset_notice' class='{$fat} notice-box'>{$notice}</p>{/if}

<form action='{$display_settings.public_dir}/reset-password' method='post'>
    <input type='hidden' name='state' value='send' />

    <table class='inputTable' width='35%'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='user_name'>User Name</label>
            </td>
            <td class='inputTableRow' id='user_name_td'>
                <input type='text' name='forgot[user_name]' id='user_name' maxlength='25' /><br />
                <span class='textfieldRequiredMsg valid'>You must pick a username.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='email'>E-mail</label>
            </td>
            <td class='inputTableRowAlt' id='email_td'>
                <input type='text' name='forgot[email]' id='email' /><br />
                <span class='textfieldRequiredMsg valid'>You must specify your e-mail address.</span>
                <span class='textfieldInvalidFormatMsg valid'>Invalid e-mail address.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead' style='text-align: center;'>
                <a href="#" onclick="javascript:document.getElementById('captchaimage').src = '{$display_settings.public_dir}/captcha.php?' + Math.random();return false;">
                    <img id="captchaimage" src="{$display_settings.public_dir}/captcha.php" alt="CAPTCHA image" style='border: 0;' />
                </a>
            </td>
            <td class='inputTableRow' id='code_td'>
                <input type='text' name='forgot[code]' id='code' /><br />
                <span class='textfieldRequiredMsg valid'>You must enter the code.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt' colspan='2' style='text-align: right;'>
                <input type='submit' value='Submit' />
            </td>
        </tr>
    </table>
</form>

{literal}
<script type='text/javascript'>
    var user_name = new Spry.Widget.ValidationTextField("user_name_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var email = new Spry.Widget.ValidationTextField("email_td", "email", {useCharacterMasking:true, validateOn:['change','blur']});    
    var captcha = new Spry.Widget.ValidationTextField("code_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
</script>
{/literal}
