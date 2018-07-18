@extends('_layouts.email')

@if (!empty($formData['expert_email']))
	@php ($fullName = title_case($formData['expert_full_name']))
@else
	@php ($username = explode('@', $recipient))
	
	@php ($fullName = title_case(str_replace('.', ' ', $username[0])))
@endif

@section('content')
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 0px solid #cccccc;border-collapse: collapse;">
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"></td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Hello {{ $fullName }},</strong></td>
					</tr>
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"></td>
					</tr>
					<tr>
						<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>User:</strong><br>{{ $currentUserFullName }}<br>{{ $currentUserEmail }}<br>{{ $currentUserTelephone }}<br>{{ $currentUserLocation }}</td>
					</tr>
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"></td>
					</tr>
					@if (!empty($formData['page']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Page:</strong> {{ $formData['page'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['category']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Category:</strong> {{ $formData['category'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['form']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Form:</strong> {{ $formData['form'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['product']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Product:</strong> {{ $formData['product'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['model']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Model:</strong> {{ $formData['model'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['action']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Action:</strong> {{ $formData['action'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['minimum_number_of_units']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Minimum Number of Units:</strong> {{ $formData['minimum_number_of_units'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['comments']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Comments:</strong> {{ $formData['comments'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['title']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Title:</strong> {{ $formData['title'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['overview']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Overview:</strong> {{ $formData['overview'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['channels']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Channels:</strong><br>
								@foreach ($formData['channels'] as $channel)
									{{ title_case(str_replace('_', ' ', $channel)) }}<br>
								@endforeach
							</td>
						</tr>
					@endif
					@if (!empty($formData['question']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Question:</strong> {{ $formData['question'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['estimated_value']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Estimated Value:</strong> {{ $formData['estimated_value'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['submitted_by']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Submitted By:</strong> {{ $formData['submitted_by'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['supporting_files']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Supporting Files:</strong> </td>
						</tr>
						@foreach ($formData['supporting_files'] as $supportingFile)
							<tr>
								<td align="left" valign="top" style="padding: 0 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><a href="{{ $supportingFile }}" title="Supporting File" style="font-family: Arial, sans-serif;font-size: 12px;color: rgb(238, 116, 33);">{{ $supportingFile }}</a></td>
							</tr>
						@endforeach
					@endif
					@if (!empty($formData['name']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Name: </strong> {{ $formData['name'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['date']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Date: </strong> {{ $formData['date'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['sector']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Sector: </strong> {{ $formData['sector'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['region']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Region: </strong> {{ $formData['region'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['scopeOfAttendance']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Scope Of Attendance: </strong> {{ $formData['scopeOfAttendance'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['partnershipName']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Partnership Name: </strong> {{ $formData['partnershipName'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['justificationForAttendance']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Justification For Attendance: </strong> {{ $formData['justificationForAttendance'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['detailsOfStand']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Details Of Stand: </strong> {{ $formData['detailsOfStand'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['estimatedCostOfStand']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Estimated Cost Of Stand: </strong> {{ $formData['estimatedCostOfStand'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['objectiveOfEvent']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Objective Of Event: </strong> {{ $formData['objectiveOfEvent'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['messagingAtEvent']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Messaging At Event: </strong> {{ $formData['messagingAtEvent'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['productsToBeDisplayed']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Products To Be Displayed: </strong> {{ $formData['productsToBeDisplayed'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['customerInviteGroups']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Customer Invite Groups: </strong> {{ $formData['customerInviteGroups'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['customerPreArrangedMeetings']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Customer Pre Arranged Meetings: </strong> {{ $formData['customerPreArrangedMeetings'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['papersToBeSubmitted']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Papers To Be Submitted: </strong> {{ $formData['papersToBeSubmitted'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['pipelineOpportunities']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Pipeline Opportunities: </strong> {{ $formData['pipelineOpportunities'] }}</td>
						</tr>
					@endif
					@if (!empty($formData['attendees']))
						<tr>
							<td align="left" valign="top" style="padding: 15px 0 15px 0;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"><strong>Attendees: </strong> {{ $formData['attendees'] }}</td>
						</tr>
					@endif
					<tr>
						<td align="left" valign="top" style="border-top: 1px solid #f1f3f6;padding: 1px;font-family: Arial, sans-serif;font-size: 14px;line-height: 20px;"></td>
					</tr>
				</table>
@endsection
