<?php





?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>登录</title>
<style type="text/css">
	html, body { margin: 0; padding: 0; width: 100%; height: 100%; font-size: 12px; }
	table { line-height: 18px; }
		table th { text-align: left; font-weight: normal; }
	input.text { width: 200px; }
</style>
</head>
<body>
	<table border="0" width="100%" height="100%">
		<tr>
			<td valign="middle">
				<form method="post" action="?model=login">
					<table align="center">
						<tr>
							<th>账户</th>
						</tr>
						<tr>
							<td><input type="text" name="username" value="" class="text" /></td>
						</tr>
						<tr>
							<th>密码</th>
						</tr>
						<tr>
							<td><input type="password" name="password" value="" class="text" /></td>
						</tr>
						<tr>
							<td><input type="submit" value="登录" /></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
	</table>	
</body>
</html>