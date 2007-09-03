<div align='center'>
    {if $pet.image != null}<img src='{$pet.image}' border='0' alt='{$pet.name}' />{else}No Image{/if}
    <table class='inputTable' width='30%' style='padding-bottom: 2em;'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>Name</td>
            <td class='inputTableRow'>{$pet.name}</td>
        </tr>

        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>Owner</td>
            <td class='inputTableRowAlt'>{kkkurl link_text=$pet.owner.name slug='profile' args=$pet.owner.id}</td>
        </tr>

        <tr>
            <td class='inputTableRow inputTableSubhead'>Specie</td>
            <td class='inputTableRow'>{$pet.specie}</td>
        </tr>

        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>Birthdate</td>
            <td class='inputTableRowAlt'>{$pet.birthdate}</td>
        </tr>

        <tr>
            <td class='inputTableRow inputTableSubhead'>Hunger</td>
            <td class='inputTableRow'>{$pet.hunger|capitalize}</td>
        </tr>

         <tr>
            <td class='inputTableRowAlt inputTableSubhead'>Happiness</td>
            <td class='inputTableRowAlt'>{$pet.happiness|capitalize}</td>
        </tr> 

        {if $pet.owner.id == $user->getUserId()}
        <tr>
            <td colspan='2' align='center' class='inputTableRow' style='font-size: large; font-weight: bold;'>{kkkurl link_text='Edit Profile' slug='edit-pet' args=$pet.id}</td>
        </tr>
        {/if}

    </table>

    {if $pet.profile != ''}<div id='pet-profile-text'>{$pet.profile}</div>{/if}
</div>
