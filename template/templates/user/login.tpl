<p align='center'>Please log in. If you do not have an account, then <a href='{$display_settings.public_dir}/register/'>create one</a> today!</p>

<form action='{$display_settings.public_dir}/login/' method='post'>
	<input type='hidden' name='state' value='process' />
	
	<table class='dataTable'>
		<tr>
			<td class='dataTableSubhead'><label for='user[username]'>User Name</label></td>
			<td class='dataTableRow'>
				<input type='text' name='user[username]' id='user[username]' maxlength='25' />
			</td>
		</tr>

		<tr>
			<td class='dataTableSubhead'><label for='user[password]'>Password</label></td>
			<td class='dataTableRow'>
				<input type='password' name='user[password]' id='user[password]' />
			</td>
		</tr>
		
		<tr>
			<td class='dataTableRow' colspan='2' align='right'>
				<input type='submit' value='Login' />
			</td>
		</tr>
	</table>
</form>
