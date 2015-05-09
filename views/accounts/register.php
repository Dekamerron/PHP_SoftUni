<div class="content">
	<h1>Register</h1>
	<form action="/accounts/register" method="POST">
		<table>
			<tr>
				<td>
					<label for="username">Username</label>
				</td>
				<td>
					<input type="text" name="username" id="username" minlength="3" maxlength="20">
				</td>
			</tr>
		<tr>
			<td>
				<label for="password">Password</label>
			</td>
			<td>
				<input type="password" name="password" id="password" minlength="3" maxlength="20">
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" value="Register">
			</td>
		</tr>
		</table>
	</form>
</div>
