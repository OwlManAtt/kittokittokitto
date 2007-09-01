{if $event_notice != ''}<div id='event_notice' class='{$fat}'>{$event_notice}</div>{/if}

<p>Below are all notices awaiting your review.</p>

<table class='dataTable' width='80%'>
    <tr>
        <td class='dataTableSubhead' align='center'>Notice</td>
        <td class='dataTableSubhead' align='center'>Time</td>
    </tr>
    {section name=index loop=$events}
    {assign var=event value=$events[index]}
    {cycle values="dataTableRow,dataTableRowAlt" assign='class'}
    <tr>
        <td class='{$class}'>{kkkurl link_text=$event.text slug='notice' args=$event.id}</td>
        <td class='{$class}'>{$event.date}</td>
    </tr>
    {sectionelse}
     <tr>
        <td class='dataTableRow' align='center' colspan='2'><em>You have no events!</em></td>
    </tr>
    {/section}

    {if $event != ''}
    <tr>
        <td class='dataTableRow' colspan='2' align='right'>
            <form action='' method='post'>
                <input type='hidden' name='state' value='clear' />
                <input type='submit' value='Clear Notices' />
            </form>
        </td>
    </tr>
    {/if}
</table>
