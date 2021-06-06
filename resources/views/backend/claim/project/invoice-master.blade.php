<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title>{{'Master - '.$quarter->name.' Invoice'}}</title>

		<!-- Favicon -->
		<link rel="icon" href="./images/favicon.png" type="image/x-icon" />

		<!-- Invoice styling -->
		<style>
			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				color: #000;
				margin-right: 0.2em;
				margin-left: 0.2em;
			}

			body h1 {
				font-weight: 200;
				margin-bottom: 0px;
				padding-bottom: 0px;
				color: #000;
			}

			body h3 {
				font-weight: 200;
				/* margin-top: 10px; */
				/* margin-bottom: 20px; */
				font-style: italic;
				color: #000;
			}

			body a {
				color: #000;
			}

			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 5px;
				font-size: 12px;
				line-height: 18px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #000;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 25px;
				line-height: 15px;
				color: #000;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 10px;
			}

			.invoice-box table tr.heading td {
				/*background: #eee;*/
				/*border-bottom: 1px solid #ddd;*/
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			a {
				text-decoration: none;
				color: #4799eb !important;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table>
				<tr class="top">
					<td colspan="4">
						<table width="100%">
							<tr>
								<td class="title" width="65%" valign="top">
                                    @if(!empty($invoiceFromPartner->invoiceOrganisation->logo) && file_exists(public_path('uploads/organisations/logos/'.$invoiceFromPartner->invoiceOrganisation->logo)))
                                        <img src="{{ asset('uploads/organisations/logos/'.$invoiceFromPartner->invoiceOrganisation->logo) }}" style="height: auto; width: 225px;" />
                                    @else
                                        {{ $invoiceFromPartner->invoiceOrganisation->organisation_name }}
                                    @endif
								</td>

								<td style="text-align: left;float:right;">
									<strong>Invoice Date: </strong>{{$quarterPartner->invoice_date}}<br />
									<strong>Invoice Number: </strong>{{$quarterPartner->invoice_no}}<br />
									<strong>PO Number: </strong>{{$quarterPartner->po_number}}<br />
									<strong>Customer A/C No: </strong>{{$invoiceFromPartner->customer_ref}}<br />
									<strong>Project Funder Ref: </strong>{{$quarter->project->project_funder_ref}}<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="4">
						<table>
							<tr>
								<td width="65%">
									<span class="mb-3 text-sm">Invoice To:</span><br />
									@if(!empty($invoiceFunder->organisation_name))
                                    	<strong class="text-dark mb-1">{{$invoiceFunder->organisation_name}}</strong><br />
                                    @endif
                                    @if(!empty($invoiceToPartner->funder_office))
                                    	{{$invoiceToPartner->funder_office}}<br />
                                    @endif
                                    @if(!empty($invoiceToPartner->funder_building_name))
                                    	{{$invoiceToPartner->funder_building_name}}<br />
                                    @endif
                                    @if(!empty($invoiceToPartner->funder_address_line_1))
                                    	{{$invoiceToPartner->funder_address_line_1}}<br />
                                    @endif
                                    @if(!empty($invoiceToPartner->funder_address_line_2))
                                    	{{$invoiceToPartner->funder_address_line_2}}<br />	
                                    @endif
                                    @if(!empty($invoiceToPartner->funder_city))
                                    	{{$invoiceToPartner->funder_city}}<br />
                                    @endif
                                    @if(!empty($invoiceToPartner->funder_county))
                                    	{{$invoiceToPartner->funder_county}}<br />
                                    @endif
                                    @if(!empty($invoiceToPartner->funder_post_code))
                                    	{{$invoiceToPartner->funder_post_code}}<br />
                                    @endif

								</td>

								<td style="text-align: left;float:right;">
									<span class="mb-3 text-sm">Invoice From:</span><br />
									@if(!empty($invoiceFromPartner->invoiceOrganisation->organisation_name))
                                    	<strong class="text-dark mb-1">{{$invoiceFromPartner->invoiceOrganisation->organisation_name}}</strong><br />
                                    @endif

                                    @if(!empty($invoiceFromPartner->office_team_name))
                                    	{{$invoiceFromPartner->office_team_name}}<br />
                                    @endif
                                    @if(!empty($invoiceFromPartner->building_name))
                                    	{{$invoiceFromPartner->building_name}}<br />
                                    @endif
                                    @if(!empty($invoiceFromPartner->street))
                                    	{{$invoiceFromPartner->street}}<br />
                                    @endif
                                    @if(!empty($invoiceFromPartner->address_line_2))
                                    	{{$invoiceFromPartner->address_line_2}}<br />
                                    @endif
                                    @if(!empty($invoiceFromPartner->city))
                                    	{{$invoiceFromPartner->city}}<br />
                                    @endif
                                    @if(!empty($invoiceFromPartner->county))
                                    	{{$invoiceFromPartner->county}}<br />
                                    @endif
                                    @if(!empty($invoiceFromPartner->post_code))
                                    	{{$invoiceFromPartner->post_code}}<br />
                                    @endif
                                   	
                                   	<br>
                                    <strong>Finance Tel:</strong> {{$invoiceFromPartner->finance_tel ?? 'N/A'}}<br />
                                    <strong>Finance Email:</strong> {{$invoiceFromPartner->finance_email ?? 'N/A'}}<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>

				<tr class="heading">
					<td style="background-color: #dedede;padding: 10px;">DESCRIPTION<000>
					<td style="background-color: #dedede;padding: 10px;text-align:center;">AMOUNT<000>
					<td style="background-color: #dedede;padding: 10px;text-align:center;">VAT<000>
					<td style="background-color: #dedede;padding: 10px;text-align:center;">TOTAL<000>
				</tr>

				<tr>
					<td>
						<strong>Project Name:</strong> {{ $project->name }}<br>
						<strong>Claim Period:</strong> 
							@php
								$quarter_length = $quarter->length;
								$quarter_length_split = explode(' - ', $quarter_length);
								$quarter_start_month = substr($quarter_length_split[0], 0, 3);
								$quarter_start_year = substr($quarter_length_split[0], 3, 2);
								$quarter_end_month = substr($quarter_length_split[1], 0, 3);
								$quarter_end_year = substr($quarter_length_split[1], 3, 2);
								$quarter_length = $quarter_start_month.' '.$quarter_start_year.' - '.$quarter_end_month.' '.$quarter_end_year;
							@endphp
							{{ strtoupper($quarter_length) }}
						<br>
						<strong>Project Quarter:</strong>
							{{ $quarter->name }}
						<br>
					</td>
					<td style="text-align: center;">£{{number_format($total = array_sum(array_column($invoiceItems, 'item_price')), 2)}}</td>
					<td style="text-align: center;">£{{number_format($vat = ($total * (array_sum(array_column($invoiceItems, 'vat_perc')) / 100)), 2)}}</td>
					<td style="text-align: center;">£{{number_format($total + $vat, 2)}}</td>
				</tr>

                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>

                <tr class="information">
					<td colspan="3">
						<strong>PAYMENT TERMS:</strong> Net 30<br>
						<strong>PAYMENT DUE DATE:</strong> {{\Carbon\Carbon::createFromFormat('d/m/Y', $quarterPartner->invoice_date)->addMonth()->format('d/m/Y')}}<br><br>
					</td>
				</tr>

				<tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
				<tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>

                <tr>
					<td colspan="3">
						<strong>BANK DETAILS</strong><br>
						<span style="line-height: 0px;font-size: 9px;">ACCOUNT NAME: {{$invoiceFromPartner->account_name}}</span><br>
                        <span style="line-height: 0px;font-size: 9px;">BANK NAME: {{$invoiceFromPartner->bank_name}}</span><br>
                        <span style="line-height: 0px;font-size: 9px;">SORT CODE: {{$invoiceFromPartner->sort_code}}</span><br>
                        <span style="line-height: 0px;font-size: 9px;">ACCOUNT NUMBER: {{$invoiceFromPartner->account_no}}</span><br>
                        <span style="line-height: 0px;font-size: 9px;">IBAN: {{$invoiceFromPartner->iban}}</span><br>
                        <span style="line-height: 0px;font-size: 9px;">SWIFT: {{$invoiceFromPartner->swift}}</span><br>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<strong>PAYMENT LINK:</strong><br>
						@if(!empty($invoiceFromPartner->payment_link))
							<a href="{{ $invoiceFromPartner->preety_payment_link }}">{{ $invoiceFromPartner->payment_link }}</a>
						@endif
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<strong>VAT REG NO:</strong><br>
						{{ $invoiceFromPartner->vat }}
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>