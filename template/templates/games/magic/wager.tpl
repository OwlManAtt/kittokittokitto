<div align='center'>
    <div style='width: 40%;'>
        <p><em>I can do a magic trick! I have five cards. I want you to pick one, and then I'll tell you what card you picked! If I'm right, you have to give me {$cost|kkkcurrency}, and if I lose, I'll give you {$prize|kkkcurrency}!</em></p>
        <p><em>Well? You wanna try?</em></p>
        {if $result != ''}<p id='result' class='{$fat}'><em>{$result}</em></p>{/if}
    </div>
</div>

<form action='{$display_settings.public_dir}/magic-game' method='post'>
    <input type='hidden' name='state' value='guess' />
    
    <table align='center' class='dataTable'>
        <tr>
            <td class='dataTableSubhead'>
                <label for='card'>Card</label>
            </td>
            <td class='dataTableRow'>
                {html_options name='card' id='card' options=$cards}
            </td>
        </tr>
        <tr>
            <td class='dataTableRow' colspan='2' align='right'>
                <input type='submit' value='Guess!' />
            </td>
        </tr>
    </table>
</form>
