@extends('_layouts.email')

@section('content')
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 0px solid #cccccc;border-collapse: collapse;">
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;">
						</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 20px;line-height: 20px;font-weight: bold;">
							Hello {{ $firstName }},
						</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;">
							You are receiving this email because we received a new password request for your account.
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;">
							<a href="{{ $setPasswordUrl }}" title="{{ $subject }}" style="font-family: Arial, sans-serif;font-size: 14px;background-color: rgb(238, 116, 33);border: 1px solid rgb(238, 116, 33);border-radius: 3px;color: #ffffff;display: inline-block;font-size: 16px;line-height: 44px;text-align: center;text-decoration: none;width: 150px;-webkit-text-size-adjust: none;mso-hide: all;">{{ $subject }}</a>
						</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;">
							Regards,<br>{{ $siteName }}
						</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;">
						</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 0 0;font-family: Arial, sans-serif;font-size: 12px;line-height: 20px;">
							If you&#39;re having trouble clicking the &quot;{{ $subject }}&quot; button, copy and paste the URL below into your web browser: <a href="{{ $setPasswordUrl }}" title="{{ $subject }}" style="font-family: Arial, sans-serif;font-size: 12px;color: rgb(238, 116, 33);">{{ $setPasswordUrl }}</a>
						</td>
					</tr>
				</table>
@endsection
