<p style='text-align: center;'>These are all of the people who keep the site running.</p>

{section name=index loop=$groups}
{assign var=group value=$groups[index].group}
{assign var=users value=$groups[index].users}
<table class='inputTable' style='width: 40%; padding-bottom: .5em;'>
    <tr>
        <td class='inputTableHead'>{$group.name}</td>
    </tr>
    <tr>
        <td class='inputTableRowAlt'>{$group.description}</td>
    </tr>
    {section name=u_i loop=$users}
    {assign var=user value=$users[u_i]}
    {cycle values='inputTableRow,inputTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' style='text-align: center;'>{kkkurl link_text=$user.name slug='profile' args=$user.id}</td>
    </tr>
    {sectionelse}
    <tr>
        <td class='inputTableRow' style='text-align: center;'><em>There are no users in this group!</em></td>
    </tr>

    {/section}
</table>
<br />
{sectionelse}
<p style='font-weight: bold; text-align: center;'>There are no visible staff groups.</p>
{/section}
