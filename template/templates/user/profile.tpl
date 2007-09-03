{if $profile.profile != ''}<div id='user-profile-area'>{$profile.profile}</div>{/if}
	
<table class='inputTable' width='30%' style='margin-left: auto; margin-right: auto;'>
	<tr>
		<td class='inputTableRow inputTableSubhead' width='40%'>User</td>
        <td class='inputTableRow'>{$profile.user_name}</td>
	</tr>
    
    <tr>
        <td class='inputTableRowAlt inputTableSubhead'>Title</td>
        <td class='inputTableRowAlt'>{$profile.title}</td> 
    </tr>
	
    <tr>
		<td class='inputTableRow inputTableSubhead'>Member Since</td>
		<td class='inputTableRow'>{$profile.created}</td>
    </tr>
    
    <tr>
		<td class='inputTableRowAlt inputTableSubhead'>Last Online</td>
		<td class='inputTableRowAlt'>{if $profile.last_active == 0}<em>Never!</em>{else}{$profile.last_active|timediff}{/if}</td>
	</tr>
    <tr>
		<td class='inputTableRow inputTableSubhead'>Last Posted</td>
		<td class='inputTableRow'>{if $profile.last_post == 0}<em>Never!</em>{else}{$profile.last_post|timediff}{/if}</td>
	</tr>
    <tr>
        <td class='inputTableRowAlt inputTableSubhead'>Posts</td>
		<td class='inputTableRowAlt'>{$profile.posts|number_format}</td>
    </tr>
	<tr>
		<td class='inputTableRow inputTableSubhead'>Gender</td>
		<td class='inputTableRow'>{$profile.gender|capitalize}</td>
	</tr>
	<tr>
		<td class='inputTableRowAlt inputTableSubhead'>Age</td>
		<td class='inputTableRowAlt'>{$profile.age|number_format}</td>
	</tr>
    {if $profile.special_status != ''}<tr>
		<td align='center' class='inputTableRow' colspan='2' style='font-size: x-large;'>{$profile.special_status}</td>
    </tr>{/if}
</table>

{if $pet_count > 0}
<div align='center' style='margin-top: 3em;'>
    <div class='quick-reply' style='width: 95%;'>
    {section name=index loop=$pets}
    {assign var=pet value=$pets[index]}
    <div class='pet-box'>
        <div align='center'>
            {if $pet.image != null}<img src='{$pet.image}' border='0' alt='{$pet.name}' />{else}No Image{/if}
            <p><strong>{kkkurl link_text=$pet.name slug='pet' args=$pet.id}</strong></p>
        </div>
    </div>
    {/section}
    <br clear='all' />
</div>
{/if}
