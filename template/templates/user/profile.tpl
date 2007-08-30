<table class='dataTable' width='30%'>
	<tr>
		<td colspan='2' class='dataTableHead'>{$profile->getUserName()}</td>
	</tr>
	
    <tr>
		<td class='dataTableSubhead' width='40%'>Joined</td>
		<td class='dataTableRow'>{$profile->getDatetimeCreated()}</td>
    </tr>
    
    <tr>
		<td class='dataTableSubhead' width='40%'>Last Active</td>
		<td class='dataTableRow'>{$profile->getLastActivity()}</td>
	</tr>
    
	<tr>
		<td class='dataTableSubhead' width='40%'>Gender</td>
		<td class='dataTableRow'>{$profile->getGender()|capitalize}</td>
	</tr>

	<tr>
		<td class='dataTableSubhead' width='40%'>Age</td>
		<td class='dataTableRow'>{$profile->getAge()|number_format}</td>
	</tr>

	
</table>
	
<br />	
