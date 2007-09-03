<p>Welcome to {$site_name}! To sign up for the game, just fill in a few details. As soon as your hit <em>Submit</em>, you'll be a member!</p>

<p>Oh, and if you can't read the security code - those blue letters in the image - click it to generate a new one. This is just to make sure you aren't a computer!</p>

<form action='{$display_settings.public_dir}/{$self.slug}' method='post'>
	<input type='hidden' name='state' value='process' />
	
	<table class='inputTable'>
		<tr>
			<td class='inputTableRow inputTableSubhead'>
                <label for='user[user_name]'>User Name</label>
            </td>
			<td class='inputTableRow'>
				<input type='text' name='user[user_name]' id='user[user_name]' value='' maxlength='25' /> <span class='tiny'>(No more than 25 characters &amp; you can put some funky stuff in!) <!-- FUNKY CAT MEBE?!?! --></span>
			</td>
		</tr>
		
		<tr>
			<td class='inputTableRowAlt inputTableSubhead'>
                <label for='user[password]'>Password</label>
            </td>
			<td class='inputTableRowAlt'>
				<input type='password' name='user[password]' id='user[password]' value='' />
			</td>
		</tr>

		<tr>
			<td class='inputTableRow inputTableSubhead'>
                <label for='user[password_again]'>Password Again</label>
            </td>
			<td class='inputTableRow'>
				<input type='password' name='user[password_again]' id='user[password_again]' value='' />
			</td>
		</tr>
	
        <tr>
			<td class='inputTableRowAlt inputTableSubhead'>
                <label for='user[email]'>E-mail Address</label>
            </td>
			<td class='inputTableRowAlt'>
				<input type='text' name='user[email]' id='user[email]' value='' />
			</td>
		</tr>

		<tr>
			<td class='inputTableRow inputTableSubhead'>
                <label for='user[age]'>Age</label>
            </td>
			<td class='inputTableRow'>
				{html_options name=user[age] options=$ages}
			</td>
		</tr>

		<tr>
			<td class='inputTableRowAlt inputTableSubhead'>
                <label for='user[gender]'>Gender</label>
            </td>
			<td class='inputTableRowAlt'>
				{html_options name=user[gender] options=$genders}
			</td>
		</tr>

		<tr>
			<td class='inputTableRow inputTableSubhead' align='center'>
				<a href="#" onclick="javascript:document.getElementById('captchaimage').src = '{$display_settings.public_dir}/captcha.php?' + Math.random();return false;">
					<img id="captchaimage" src="{$display_settings.public_dir}/captcha.php" alt="CAPTCHA image" style='border: 0;' />
				</a>
			</td>
			<td class='inputTableRow'>
				<input type='text' name='captcha_code' id='captcha_code' value='' />
			</td>
		</tr>
		
		<tr>
			<td colspan='2' class='inputTableRowAlt' align='center'>
			    Registration indicates that you agree to abide by the {kkkurl link_text='Terms and Conditions' slug='terms-and-conditions'}.
            </td>
		</tr>

		<tr>
			<td colspan='2' class='inputTableRow' align='right'>
				<input type='submit' value='Submit' />
			</td>
		</tr>

	</table>
</form>
