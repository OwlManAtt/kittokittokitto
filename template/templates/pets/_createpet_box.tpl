<div class='pet-box'>
    <div align='center'>
        {if $pet.image != null}<img src='{$pet.image}' border='0' alt='{$pet.name}' />{else}No Image{/if}
    </div>
    <p><strong>{$pet.name}</strong>: {$pet.description}</p>

    <div align='center'>
        {if $pet.image != ''}
        <form action='{$display_settings.public_dir}/create-pet/' action='get'>
            <input type='hidden' name='state' value='details' />
            <input type='hidden' name='species[id]' value='{$pet.id}' />
            <input type='submit' value='Adopt {$pet.name}'/>
        </form>
        {else}
        <em>Unavailable</em><!-- No colors for pet. -->
        {/if}
    </div>
</div>
