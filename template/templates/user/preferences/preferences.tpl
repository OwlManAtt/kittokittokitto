<p>You may update your password, e-mail address, and other preferences here. If you wish to update any fields in the '<em>Account Details</em>' section, please enter your old password. The text-editor preference allows you to toggle the formatting buttons for certain fields (like post bodies, messages, and even your profile) on or off.</p>

{if $notice != ''}<p id='pref_notice' class='{$fat} notice-box'>{$notice}{/if}

<div style='width: 70%; margin-right: auto; margin-left: auto;' align='center'>
    <form action='{$display_settings.public_dir}/preferences/' method='post'>
        <input type='hidden' name='state' value='save' />
    
        <table class='inputTable' width='100%'>
            <tr>
                <td colspan='2' class='inputTableHead'>Account Settings</td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='password[old]'>Old Password</label>
                </td>
                <td width='80%' class='inputTableRow'>
                    <input type='password' name='password[old]' id='password[old]' />
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='password[a]'>New Password</label>
                </td>
                <td class='inputTableRowAlt'>
                    <input type='password' name='password[a]' id='password[a]' />
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='password[b]'>Repeat</label>
                </td>
                <td class='inputTableRow'>
                    <input type='password' name='password[b]' id='password[b]' />
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='user[email]'>E-mail Address</label>
                </td>
                <td class='inputTableRowAlt'>
                    <input type='text' name='user[email]' id='user[email]' value='{$prefs.email}' />
                </td>
            </tr>
        </table>
        
        <table class='inputTable' width='100%' style='padding-top: 3em;'>
            <tr>
                <td colspan='3' class='inputTableHead'>Preferences</td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='user[timezone]'>Timezone</label>
                </td>
                <td class='inputTableRow' width='80%' colspan='2'>
                    {html_options name='user[timezone]' id='user[timezone]' selected=$prefs.timezone_id options=$timezones}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                    <label for='user[datetime_format]'>Date &amp; Time Format</label>
                </td>
                <td class='inputTableRowAlt' width='80%' colspan='2'>
                    {html_options name='user[datetime_format]' id='user[datetime_format]' selected=$prefs.datetime_format_id options=$datetime_formats}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='user[gender]'>Gender</label>
                </td>
                <td class='inputTableRow' width='80%' colspan='2'>
                    {html_options name='user[gender]' id='user[gender]' options=$genders selected=$prefs.gender}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                    <label for='user[age]'>Age</label>
                </td>
                <td class='inputTableRowAlt' width='80%' colspan='2'>
                    {html_options name='user[age]' id='user[age]' options=$ages selected=$prefs.age}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='user[editor]'>Texteditor</label>
                </td>
                <td width='80%' class='inputTableRow' colspan='2'>
                    {html_options name='user[editor]' id='user[editor]' options=$editors selected=$prefs.editor}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                    <label for='avatar'>Avatar</label>
                </td>
                <td class='inputTableRowAlt' style='vertical-align: top;'>
                    {html_options name='user[avatar]' id='avatar' options=$avatars selected=$prefs.avatar_id onChange="return avatarPicker(this.form.avatar[this.form.avatar.selectedIndex].value,'`$display_settings.public_dir`/resources/avatars/');"}
                </td>
                <td class='inputTableRowAlt'>
                    <img src='{$prefs.avatar_url}' alt='Avatar' border='0' id='avatar_image' {if $prefs.avatar_id == ''}style='display: none;' {/if}/>
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='user[profile]'>Profile</label>
                </td>
                <td width='80%' class='inputTableRow' colspan='2'>
                    <textarea name='user[profile]' id='user[profile]' cols='55' rows='10'>{$prefs.profile}</textarea>
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                    <label for='user[signature]'>Signature</label>
                </td>
                <td width='80%' class='inputTableRowAlt' colspan='2'>
                    <textarea name='user[signature]' id='user[signature]' cols='55' rows='10'>{$prefs.signature}</textarea>
                </td>
            </tr>
            <tr>
                <td class='inputTableRow'>&nbsp;</td>
                <td class='inputTableRow' align='right' colspan='2'>
                    <input type='submit' value='Save' />
                </td>
            </tr>
        </table>
    </form>
</div>
