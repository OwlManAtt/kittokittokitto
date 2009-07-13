<p>You may update your password, e-mail address, and other preferences here. If you wish to update any fields in the '<em>Account Settings</em>' section, please enter your old password. The text-editor preference allows you to toggle the formatting buttons for certain fields (like post bodies, messages, and even your profile) on or off.</p>

{if $notice != ''}<p id='pref_notice' class='{$fat} notice-box'>{$notice}{/if}

<div align='center'>
    <div style='width: 80%; margin-right: auto; margin-left: auto;'>
        <form action='{$display_settings.public_dir}/preferences/' method='post'>
            <input type='hidden' name='state' value='save_account' />
        
            <table class='inputTable' width='100%'>
                <tr>
                    <td colspan='2' class='inputTableHead'>Account Settings</td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='old'>Old Password</label>
                    </td>
                    <td width='80%' class='inputTableRow' id='old_td'>
                        <input type='password' name='password[old]' id='old' /><br />
                        <span class='textfieldRequiredMsg valid'>You must enter your old password.</span>
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRowAlt inputTableSubhead'>
                        <label for='a'>New Password</label>
                    </td>
                    <td class='inputTableRowAlt' id='a_td'>
                        <input type='password' name='password[a]' id='a' /><br />
                        <span class='passwordRequiredMsg valid'>You must enter a new password.</span>
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='b'>Repeat</label>
                    </td>
                    <td class='inputTableRow' id='b_td'>
                        <input type='password' name='password[b]' id='b' /><br />
                        <span class='textfieldRequiredMsg confirmInvalidMsg valid'>You must repeat the new password.</span>
                        <span class='textfieldInvalidFormatMsg valid'>Passwords do not match.</span>
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRowAlt inputTableSubhead'>
                        <label for='email'>E-mail Address</label>
                    </td>
                    <td class='inputTableRowAlt' id='email_td'>
                        <input type='text' name='user[email]' id='email' value='{$prefs.email}' />
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRow' style='text-align: right;' colspan='2'>
                        <input type='submit' value='Save Account Settings' />
                    </td>
                </tr>
            </table>
        </form>

        <form action='{$display_settings.public_dir}/preferences/' method='post'>
            <input type='hidden' name='state' value='save_preferences' />

            <table class='inputTable' width='100%' style='padding-top: 3em;'>
                <tr>
                    <td colspan='3' class='inputTableHead'>Preferences</td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='timezone'>Timezone</label>
                    </td>
                    <td class='inputTableRow' width='80%' colspan='2' id='timezone_td'>
                        {html_options name='user[timezone]' id='timezone' selected=$prefs.timezone_id options=$timezones}
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                        <label for='datetime_format'>Date &amp; Time Format</label>
                    </td>
                    <td class='inputTableRowAlt' width='80%' colspan='2' id='datetime_format_td'>
                        {html_options name='user[datetime_format]' id='datetime_format' selected=$prefs.datetime_format_id options=$datetime_formats}
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='gender'>Gender</label>
                    </td>
                    <td class='inputTableRow' width='80%' colspan='2' id='gender_td'>
                        {html_options name='user[gender]' id='gender' options=$genders selected=$prefs.gender}
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                        <label for='age'>Age</label>
                    </td>
                    <td class='inputTableRowAlt' width='80%' colspan='2' id='age_td'>
                        {html_options name='user[age]' id='age' options=$ages selected=$prefs.age}
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='editor'>Texteditor</label>
                    </td>
                    <td width='80%' class='inputTableRow' colspan='2' id='editor_td'>
                        {html_options name='user[editor]' id='editor' options=$editors selected=$prefs.editor}
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                        <label for='avatar'>Avatar</label>
                    </td>
                    <td class='inputTableRowAlt' style='vertical-align: top;' id='avatar_td'>
                        {html_options name='user[avatar]' id='avatar' options=$avatars selected=$prefs.avatar_id onChange="return imagePicker(this.form.avatar[this.form.avatar.selectedIndex].value,'`$display_settings.public_dir`/resources/avatars/','avatar_image');"}
                    </td>
                    <td class='inputTableRowAlt'>
                        <img src='{$prefs.avatar_url}' alt='Avatar' border='0' id='avatar_image' {if $prefs.avatar_id == ''}style='display: none;' {/if}/>
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='show_status'>Show Online Status</label>
                    </td>
                    <td width='80%' class='inputTableRow' colspan='2' id='show_status_td'>
                        {html_options name='user[show_online_status]' id='show_status' options=$online_status selected=$prefs.show_online_status}
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                        <label for='profile'>Profile</label>
                    </td>
                    <td width='80%' class='inputTableRowAlt' colspan='2' id='profile_td'>
                        <textarea name='user[profile]' id='profile' cols='55' rows='10'>{$prefs.profile}</textarea>
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='signature'>Signature</label>
                    </td>
                    <td width='80%' class='inputTableRow' colspan='2' id='signature_td'>
                        <textarea name='user[signature]' id='signature' cols='55' rows='10'>{$prefs.signature}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRowAlt' style='text-align: right;' colspan='3'>
                        <input type='submit' value='Save Preferences' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

{literal}
<script type='text/javascript'>
    var old_password = new Spry.Widget.ValidationTextField("old_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var password_a = new Spry.Widget.ValidationPassword("a_td",{validateOn:["blur"], isRequired:false});
    var password_b = new Spry.Widget.ValidationPassword("b_td",{validateOn:["blur"], isRequired:false});
    var password_confirm = new Spry.Widget.ValidationConfirm("b_td", "a_td", {validateOn: ['blur'], isRequired:false});

    var email = new Spry.Widget.ValidationTextField("email_td", "email", {useCharacterMasking:true, validateOn:['change','blur']});    
    
    var timezone = new Spry.Widget.ValidationSelect('timezone_td',{validateOn:['blur','change'], invalidValue: ''});
    var datetime_format = new Spry.Widget.ValidationSelect('datetime_format_td',{validateOn:['blur','change'], invalidValue: ''});
    var age = new Spry.Widget.ValidationSelect('age_td',{validateOn:['blur','change'], invalidValue: ''});
    var gender = new Spry.Widget.ValidationSelect('gender_td',{validateOn:['blur','change'], invalidValue: '0'});
    var editor = new Spry.Widget.ValidationSelect('editor_td',{validateOn:['blur','change'], invalidValue: '0'});
    var avatar = new Spry.Widget.ValidationSelect('avatar_td',{validateOn:['blur','change'], invalidValue: '0', isRequired: false});
    var online_status = new Spry.Widget.ValidationSelect('show_status_td',{validateOn:['blur','change'], isRequired: false});

    var profile = new Spry.Widget.ValidationTextarea('profile_td',{isRequired: false});
    var signature = new Spry.Widget.ValidationTextarea('signature_td',{isRequired: false});
</script>
{/literal}
