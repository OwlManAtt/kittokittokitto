<p>All of your inactive pets are cryogenically stored in safe, comfortable conditions in your personal freezer. They will not grow hungry or unhappy.</p>

{section name=pet loop=$pets}
<div class='pet-box'>
    <div align='center'>
        <img src='{$pets[pet].image}' border='0' alt='{$pets[pet].name}' />
        
        <p id='pet_{$pets[pet].id}'{if $pets[pet].fade == 1} class='fade'{/if}><strong>{$pets[pet].name}</strong></p>
    </div>
</div>
{sectionelse}
<div align='center'>
    <p><em>You do not have any pets.</em></p>
</div>
{/section}
