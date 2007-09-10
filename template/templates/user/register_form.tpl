<p>Welcome to {$site_name}! To sign up for the game, just fill in a few details. As soon as your hit <em>Submit</em>, you'll be a member!</p>

<p>Oh, and if you can't read the security code - those blue letters in the image - click it to generate a new one. This is just to make sure you aren't a computer!</p>

<div align='center'>
    <form action='{$display_settings.public_dir}/{$self.slug}' method='post'>
        <input type='hidden' name='state' value='process' />
        
        <table class='inputTable'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='username'>User Name</label>
                </td>
                <td class='inputTableRow' id='username_td'>
                    <input type='text' name='user[user_name]' id='username' value='' maxlength='25' /> <span class='tiny'>(You can put some funky stuff in!) <!-- FUNKY CAT MEBE?!?! --></span><br />
                    <span class='textfieldRequiredMsg valid'>You must pick a username.</span>
                </td>
            </tr>
            
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='password'>Password</label>
                </td>
                <td class='inputTableRowAlt' id='password_td'>
                    <input type='password' name='user[password]' id='password' value='' /><br />
                    <span class='textfieldRequiredMsg valid'>You must pick a password.</span>
                </td>
            </tr>

            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='password_again'>Password Again</label>
                </td>
                <td class='inputTableRow' id='password_again_td'>
                    <input type='password' name='user[password_again]' id='password_again' value='' /><br />
                    <span class='textfieldRequiredMsg valid'>You must repeat your password.</span>
                    <span class='textfieldInvalidFormatMsg valid'>Passwords does not match.</span>
                </td>
            </tr>
        
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='email'>E-mail Address</label>
                </td>
                <td class='inputTableRowAlt' id='email_td'>
                    <input type='text' name='user[email]' id='email' value='' /><br />
                    <span class='textfieldRequiredMsg valid'>You must specify your e-mail address.</span>
                    <span class='textfieldInvalidFormatMsg valid'>Invalid e-mail address.</span>
                </td>
            </tr>

            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='age'>Age</label>
                </td>
                <td class='inputTableRow' id='age_td'>
                    {html_options id='age' name=user[age] options=$ages}<br />
                    <span class='selectInvalidMsg valid'>You must specify your age.</span>
                </td>
            </tr>

            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='gender'>Gender</label>
                </td>
                <td class='inputTableRowAlt' id='gender_td'>
                    {html_options id='gender' name=user[gender] options=$genders}<br />
                    <span class='selectInvalidMsg valid'>You must specify your gender.</span>
                </td>
            </tr>

            <tr>
                <td class='inputTableRow inputTableSubhead' align='center'>
                    <a href="#" onclick="javascript:document.getElementById('captchaimage').src = '{$display_settings.public_dir}/captcha.php?' + Math.random();return false;">
                        <img id="captchaimage" src="{$display_settings.public_dir}/captcha.php" alt="CAPTCHA image" style='border: 0;' />
                    </a>
                </td>
                <td class='inputTableRow' id='code_td'>
                    <input type='text' name='captcha_code' id='captcha_code' value='' /><br />
                    <span class='textfieldRequiredMsg valid'>You must enter the code.</span>
                </td>
            </tr>
            
            <tr>
                <td colspan='2' class='inputTableRowAlt' align='center'>
                    Registration indicates that you agree to abide by the {kkkurl link_text='Terms and Conditions' slug='terms-and-conditions'}.
                </td>
            </tr>

            <tr>
                <td colspan='2' class='inputTableRow' style='text-align: right;'>
                    <input type='submit' value='Submit' />
                </td>
            </tr>

        </table>
    </form>
</div>

{literal}
<script type='text/javascript'>
    var passwordTheSame = function(value,options) {
        var other_value = document.getElementById('password').value;
        if(value != other_value) return false;

        return true;
    } // end anon

    var user_name = new Spry.Widget.ValidationTextField("username_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var password = new Spry.Widget.ValidationTextField("password_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var password_again = new Spry.Widget.ValidationTextField("password_again_td", "custom", {validation: passwordTheSame, validateOn:['change','blur']});    
    var email = new Spry.Widget.ValidationTextField("email_td", "email", {useCharacterMasking:true, validateOn:['change','blur']});    
    var age = new Spry.Widget.ValidationSelect('age_td',{validateOn:['blur','change'], invalidValue: ''});
    var gender = new Spry.Widget.ValidationSelect('gender_td',{validateOn:['blur','change'], invalidValue: '0'});
    var captcha = new Spry.Widget.ValidationTextField("code_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
</script>
{/literal}
