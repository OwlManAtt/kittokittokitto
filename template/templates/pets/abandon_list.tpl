<p>Oh, the humanity! Your poor pet will be put out on the street with nobody to care for it. It will have to scrounge through trashcans to find food and live under an overpass or in Shii's old box (which, I admit, is pretty nice...)!</p>

<p>Understand that you are <em>not</em> putting it up for adoption. You are <strong>throwing the pet out onto the streets</strong>, and it's probable that nobody will ever take it in.</p>

{section name=pet loop=$pets}
<div class='pet-box'>
    <div align='center'>
        <img src='{$pets[pet].image}' border='0' alt='{$pets[pet].name}' />
        
        <p id='pet_{$pets[pet].id}'><strong>{$pets[pet].name}</strong></p>

        {if $pets[pet].active == 0}
        <form action='{$display_settings.public_dir}/abandon-pet' method='post' onSubmit="return confirm('Are you SURE?')">
            <input type='hidden' name='state' value='abandon' />
            <input type='hidden' name='pet_id' value='{$pets[pet].id}' />
            <input type='submit' value='Abandon :-(' />
        </form>
        {/if}
    </div>
</div>
{sectionelse}
<div align='center'>
    <p><em>You do not have any pets to give up.</em></p>
</div>
{/section}
