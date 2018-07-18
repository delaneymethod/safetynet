@extends('_layouts.email')

@section('content')
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 0px solid #cccccc;border-collapse: collapse;">
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"></td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 20px;line-height: 20px;font-weight: bold;">{{ $job }}</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;">Please inspect the logs for further details.</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"></td>
					</tr>
				</table>
@endsection
