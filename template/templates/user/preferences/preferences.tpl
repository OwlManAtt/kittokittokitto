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
                <td colspan='2' class='inputTableHead'>Preferences</td>
            </tr>

            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='user[gender]'>Gender</label>
                </td>
                <td class='inputTableRow' width='80%'>
                    {html_options name='user[gender]' id='user[gender]' options=$genders selected=$prefs.gender}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                    <label for='user[age]'>Age</label>
                </td>
                <td class='inputTableRowAlt' width='80%'>
                    {html_options name='user[age]' id='user[age]' options=$ages selected=$prefs.age}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='user[editor]'>Texteditor</label>
                </td>
                <td width='80%' class='inputTableRow'>
                    {html_options name='user[editor]' id='user[editor]' options=$editors selected=$prefs.editor}
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRowAlt inputTableSubhead'>
                    <label for='user[profile]'>Profile</label>
                </td>
                <td width='80%' class='inputTableRowAlt'>
                    <textarea name='user[profile]' id='user[profile]' cols='55' rows='10'>{$prefs.profile}</textarea>
                </td>
            </tr>
            <tr>
                <td width='20%' class='inputTableRow inputTableSubhead'>
                    <label for='user[signature]'>Signature</label>
                </td>
                <td width='80%' class='inputTableRow'>
                    <textarea name='user[signature]' id='user[signature]' cols='55' rows='10'>{$prefs.signature}</textarea>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt'>&nbsp;</td>
                <td class='inputTableRowAlt' align='right'>
                    <input type='submit' value='Save' />
                </td>
            </tr>
        </table>
    </form>
</div>
