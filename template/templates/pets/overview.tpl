<p>All of your inactive pets are cryogenically stored in safe, comfortable conditions in your personal freezer. They will not grow hungry or unhappy.</p>

{section name=pet loop=$pets}
<div class='pet-box'>
    <div align='center'>
        <img src='{$pets[pet].image}' border='0' alt='{$pets[pet].name}' />
        
        <p id='pet_{$pets[pet].id}'{if $pets[pet].fade == 1} class='{$fat} notice-box'{/if}><strong>{$pets[pet].name}</strong></p>

        {if $pets[pet].active == 0}
        <form action='{$display_settings.public_dir}/pets' method='post'>
            <input type='hidden' name='state' value='active' />
            <input type='hidden' name='pet_id' value='{$pets[pet].id}' />
            <input type='submit' value='Make Active' />
        </form>
        {/if}
    </div>
</div>
{sectionelse}
<div align='center'>
    <p><em>You do not have any pets.</em></p>
</div>
{/section}
