<div align='center'>
    <table class='inputTable' width='30%'>
        <tr>
            <td class='dataTableHead'>Search Results</td>
        </tr>
        {section name=index loop=$results}
        {assign var=result value=$results[index]}
        {cycle values='dataTableRow,dataTableRowAlt' assign=class}
        <tr>
            <td class='{$class}'>{kkkurl link_text=$result.label slug=$result.slug args=$result.args}</td>
        </tr>
        {sectionelse}
        <tr>
            <td class='dataTableRow'><em>No results were found for your search terms!</em></td>
        </tr>
        {/section}
    </table>
</div>

<br clear='all' />
<div class='pages'>{$pagination}</div>
