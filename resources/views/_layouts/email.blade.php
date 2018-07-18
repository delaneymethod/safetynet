<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{{ $siteName }}</title>
	<style type="text/css">
		.white-label {
			display: none !important;
		}
	</style>
</head>
<body style="margin: 0; padding: 0;">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 0px solid #cccccc;border-collapse: collapse;">
		<tr>
			<td align="center" bgcolor="#ffffff" style="padding: 20px 0 0 0;">
				<img src="{{ $siteLogo }}" alt="{{ $siteName }} Logo" style="width:40%; height: auto;">
			</td>
		</tr>
		<tr>
			<td align="center" bgcolor="#ffffff" style="padding: 20px 0 20px 0;">
				@yield('content')
			</td>
		</tr>
		<tr>
			<td align="center" bgcolor="#ffffff" style="padding: 0 20px 20px 20px;font-family: Arial, sans-serif;font-size: 16px;line-height: 20px;color: #000000;">
				{{ $siteName }}<span style="display: none;" class="white-label"> - Powered by {{ config('app.name') }}</span>
			</td>
		</tr>
	</table>
</body>
</html>
