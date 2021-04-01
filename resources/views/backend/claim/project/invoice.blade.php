<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title>{{$quarter->name.' Invoice'}}</title>

		<!-- Favicon -->
		{{-- <link rel="icon" href="./images/favicon.png" type="image/x-icon" /> --}}

		<!-- Invoice styling -->
		<style>
			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				color: #777;
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
				color: #555;
			}

			body a {
				color: #06f;
			}

			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 5px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 12px;
				line-height: 20px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
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
				line-height: 25px;
				color: #333;
				vertical-align: top;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
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
					<td colspan="3">
						<table>
							<tr>
								<td class="title" style="margin-top:-20px;">
                                    @if(!empty($invoiceFromPartner->invoiceOrganisation->logo) && file_exists(public_path('uploads/organisations/logos/'.$invoiceFromPartner->invoiceOrganisation->logo)))
                                        <img src="{{ asset('uploads/organisations/logos/'.$invoiceFromPartner->invoiceOrganisation->logo) }}" style="height: auto; width: 225px;" />
                                    @else
                                        {{ $invoiceFromPartner->invoiceOrganisation->organisation_name }}
                                    @endif
								</td>

								<td style="text-align: left;float:right;">
									<strong>Invoice Date: </strong>{{$quarterPartner->pivot->invoice_date}}<br />
									<strong>Invoice Number: </strong>#{{$quarterPartner->pivot->invoice_no}}<br />
									<strong>PO Number: </strong>{{$quarterPartner->pivot->po_number}}<br />
									<strong>Customer A/C No: </strong>{{$invoiceToPartner->account_no}}<br />
									<strong>Project Funder Ref: </strong>{{$quarter->project->project_funder_ref}}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="3">
						<table>
							<tr>
								<td>
                                    <strong>Invoice To:</strong><br>
									@if (!$isInvoiceToFunder)
                                    <strong>{{$invoiceToPartner->invoiceOrganisation->organisation_name}}</strong><br />
                                    {{$invoiceToPartner->street_address}}<br />
                                    {{$invoiceToPartner->address_line_2}}<br />
                                    {{$invoiceToPartner->county}}<br />
                                    {{$invoiceToPartner->city}}<br />
                                    {{$invoiceToPartner->post_code}}<br />
                                    <strong>Finance Tel:</strong> {{$invoiceToPartner->finance_tel ?? 'N/A'}}<br />
                                    <strong>Finance Email:</strong> {{$invoiceToPartner->finance_email ?? 'N/A'}}<br />
                                    @else    
                                    <strong>{{$invoiceTo->organisation_name}}</strong><br />
                                    {{$invoiceToPartner->funder_address_line_1}}<br />
                                    {{$invoiceToPartner->funder_address_line_2}}<br />
                                    {{$invoiceToPartner->funder_county}}<br />
                                    {{$invoiceToPartner->funder_city}}<br />
                                    {{$invoiceToPartner->funder_post_code}}<br />
                                    <strong>Finance Tel:</strong> {{$invoiceToPartner->finance_tel ?? 'N/A'}}<br />
                                    <strong>Finance Email:</strong> {{$invoiceToPartner->finance_email ?? 'N/A'}}<br />
                                    @endif
								</td>

								<td style="text-align: left;float:right;">
                                    <strong>Invoice From:</strong><br />
                                    <strong>{{$invoiceFromPartner->invoiceOrganisation->organisation_name}}</strong><br />
                                    {{$invoiceFromPartner->street_address}}<br />
                                    {{$invoiceFromPartner->address_line_2}}<br />
                                    {{$invoiceFromPartner->county}}<br />
                                    {{$invoiceFromPartner->city}}<br />
                                    {{$invoiceFromPartner->post_code}}<br />
                                    <strong>Finance Tel:</strong> {{$invoiceFromPartner->finance_tel ?? 'N/A'}}<br />
                                    <strong>Finance Email:</strong> {{$invoiceFromPartner->finance_email ?? 'N/A'}}<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>Description</td>
					<td style="text-align:center;">Amount</td>
					<td style="text-align:center;">VAT</td>
				</tr>

                @foreach (array_slice($invoiceItems, 0, 5) as $invoiceItem)
                <tr class="item {{$loop->last ? 'last' : ''}}">
                    <td>{{$invoiceItem->item_name.' '.$invoiceItem->item_description}}</td>
                    <td style="text-align:center;">£{{$invoiceItem->item_price}}</td>
                    <td style="text-align:center;">{{$invoiceItem->vat_perc}}%</td>
                </tr>
                @endforeach
				<tr class="total">
					<td style="text-align:right;"><strong>Total (Net):</strong> </td>
					<td style="text-align:center;">£{{number_format($total = array_sum(array_column($invoiceItems, 'item_price')), 2)}}</td>
					<td></td>
				</tr>
				<tr class="total">
					<td style="text-align:right;"><strong>VAT:</strong> </td>
					<td style="text-align:center;">£{{number_format($vat = ($total * (array_sum(array_column($invoiceItems, 'vat_perc')) / 100)), 2)}}</td>
					<td></td>
				</tr>
				<tr class="total">
					<td style="text-align:right;"><strong>Balance Due:</strong> </td>
					<td style="text-align:center;">£{{number_format($total + $vat, 2)}}</td>
					<td></td>
				</tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="information">
					<td colspan="3"><strong>Payment Terms: </strong>Net 30/ 30 days</td>
				</tr>
                <tr class="information">
					<td colspan="3"><strong>Payment Due Date: </strong>{{\Carbon\Carbon::createFromFormat('d/m/Y', $quarterPartner->pivot->invoice_date)->addMonth()->format('d/m/Y')}}</td>
				</tr>
                <tr class="information">
                    <td colspan="3"></td>
                </tr>
                <tr class="heading">
					<td colspan="3">BANK DETAILS</td>
				</tr>
                <tr>
					<td>
                        <table>
                            <tr class="heading">
                                <td>FOR BACS PAYMENTS</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>ACCOUNT NAME: </strong>{{$invoiceFromPartner->account_name}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>BANK NAME: </strong>{{$invoiceFromPartner->bank_name}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>SORT CODE: </strong>{{$invoiceFromPartner->sort_code}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>ACCOUNT NUMBER: </strong>{{$invoiceFromPartner->account_no}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>IBAN: </strong>{{$invoiceFromPartner->iban}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>SWIFT: </strong>{{$invoiceFromPartner->swift}}
                                </td>
                            </tr>
                            <tr class="heading">
                                <td>FOR ONLINE PAYMENTS</td>
                            </tr>
                            <tr>
                                <td><a href="#"></a></td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                    <td></td>
				</tr>
                <tr>
                    <td colspan="3">
                        <strong>VAT REG No: </strong>{{$invoiceFromPartner->vat}}
                    </td>
                </tr>
			</table>
		</div>
	</body>
</html>