<p align='center'>There {if $totals.sum != 1}are{else}is{/if} a total of {$totals.sum} {if $totals.sum != 1}people{else}person{/if} online.{if $totals.hidden > 0} {$totals.hidden} hidden user{if $totals.hidden != 1}s{/if} {if $totals.hidden == 1}is{else}are{/if} online.{/if}{if $totals.guests > 0} There {if $totals.guests != 1}are{else}is{/if} also {$totals.guests} guest{if $totals.guests != 1}s{/if} online.{/if}</p>

<div align='center'>
    <table class='inputTable' width='50%'>
        <tr>
            <td colspan='2' class='inputTableHead'>Online Users</td>
        </tr>
        <tr>
            <td class='inputTableSubhead inputTableRowAlt' style='text-align: center;'>User</td>
            <td class='inputTableSubhead inputTableRowAlt' style='text-align: center;'>Last Active</td>
        </tr>
        {section name=index loop=$users}
        {assign var=u value=$users[index]}
        {cycle values='inputTableRow,inputTableRowAlt' assign=class}
        <tr>
            <td class='{$class}'>{kkkurl link_text=$u.name slug='profile' args=$u.id}</td>
            <td class='{$class}'>{$u.last_active|timediff}</td>
        </tr> 
        {sectionelse}
        <tr>
            <td class='dataTableRow' colspan='2' style='text-align: center;'><em>There are no users online!</em></td>
        </tr>
        {/section}
    </table>
</div>

<br clear='all' />
<div class='pages'>{$pagination}</div>
