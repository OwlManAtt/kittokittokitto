<div id='breadcrumb-trail'>Boards</div>

<div align='center'>
    <table class='dataTable' width='70%'>
        <tr>
            <td class='dataTableSubhead' align='center'>Board</td>
            <td class='dataTableSubhead' align='center'>Last Poster</td>
            <td class='dataTableSubhead' align='center'>Posts</td>
        </tr>
        {section name=index loop=$boards}
        {assign var=board value=$boards[index]}
        {cycle values='dataTableRow,dataTableRowAlt' assign=class}
        <tr>
            <td class='{$class}'>
                {kkkurl link_text=$board.name slug='threads' args=$board.id}<br />
                {$board.description}
            </td>
            <td class='{$class}'>{if $board.last_poster != ''}{$board.last_poster}{else}<em>Nobody</em>{/if}</td>
            <td class='{$class}'>{$board.total_posts|number_format}</td>
        </tr>
        {sectionelse}
        <tr>
            <td colspan='3' class='dataTableRow' align='center'><em>There are no boards.</em></td>
        </tr>
        {/section}
    </table>
</div>
