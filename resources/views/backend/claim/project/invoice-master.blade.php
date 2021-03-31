<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <title>{{'Master - '.$quarter->name.' Invoice'}}</title>
        <!-- START: Template CSS-->
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/bootstrap/css/bootstrap.min.css') }}">
        <!-- START: Custom CSS-->
        <!-- <link rel="stylesheet" href="{{ asset('assets/backend/css/main.css') }}"> -->
        <style>
            body {
                background-color: #FFF;
                color: #000;
                font-family: 'DejaVu Sans';
                font-size: 13px;
            }

            .padding {
                padding: 2rem !important
            }

            .card {
                margin-bottom: 30px;
                border: none;
                -webkit-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
                -moz-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
                box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22)
            }

            .card-header {
                background-color: #fff;
                border-bottom: 1px solid #e6e6f2
            }

            h3 {
                font-size: 16px;
                font-family: 'DejaVu Sans' !important;
            }

            h5 {
                font-size: 13px;
                line-height: 26px;
                /* color: #c2c2c4; */
                margin: 0px 0px 15px 0px;
                /* font-family: 'Circular Std Medium' */
                font-family: 'DejaVu Sans' !important;
            }

            .text-dark {
                color: #3d405c !important
            }
        </style>
        <!-- END: Custom CSS-->
    </head>
    <body id="main-container" class="default">
        <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding">
            <div class="">
                <div class=" p-4">
                    <table class="w-100">
                        <tr>
                            <td>
                                @if(!empty($invoiceFromPartner->invoiceOrganisation->logo) && file_exists(public_path('uploads/organisations/logos/'.$invoiceFromPartner->invoiceOrganisation->logo)))
                                    <img src="{{ asset('uploads/organisations/logos/'.$invoiceFromPartner->invoiceOrganisation->logo) }}" height="160" width="160" />
                                @else
                                    {{ $invoiceFromPartner->invoiceOrganisation->organisation_name }}
                                @endif
                            </td>
                            <td class="float-right">
                                <table class="w-100">
                                    <tr>
                                        <td><strong>Invoice Date: </strong></td>
                                        <td>{{$quarterPartner->invoice_date}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Invoice Number: </strong></td>
                                        <td>#{{$quarterPartner->invoice_no}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>PO Number: </strong></td>
                                        <td>{{$quarterPartner->po_number}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer Account Number: </strong></td>
                                        <td>{{$invoiceToPartner->account_no}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Project Funder Ref: </strong></td>
                                        <td>{{$invoiceToPartner->customer_ref}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="">
                    <div class="row mb-4 m-2">
                        <table class="w-100">
                            <tr>
                                <td>
                                    <p>
                                        <strong class="mb-3 text-sm">Invoice To:</strong>
                                    </p>
                                    <strong class="text-dark mb-1">{{$invoiceFunder->organisation_name}}</strong>
                                    {{-- <div>{{$invoiceToPartner->funder_office}}</div>
                                    <div>{{$invoiceToPartner->funder_building_name}}</div> --}}
                                    <div>{{$invoiceToPartner->funder_address_line_1}}</div>
                                    <div>{{$invoiceToPartner->funder_address_line_2}}</div>
                                    <div>{{$invoiceToPartner->funder_city}}</div>
                                    <div>{{$invoiceToPartner->funder_county}}</div>
                                    <div>{{$invoiceToPartner->funder_post_code}}</div>
                                    <div><strong>Finance Tel:</strong> {{$invoiceToPartner->finance_tel ?? 'N/A'}}</div>
                                    <div><strong>Finance Email:</strong> {{$invoiceToPartner->finance_email ?? 'N/A'}}</div>
                                </td>
                                <td class="float-right">
                                    <p>
                                        <strong class="mb-3 text-sm">Invoice From:</strong>
                                    </p>
                                    <strong class="text-dark mb-1">{{$invoiceFromPartner->invoiceOrganisation->organisation_name}}</strong>
                                    <div>{{$invoiceFromPartner->street_address}}</div>
                                    <div>{{$invoiceFromPartner->address_line_2}}</div>
                                    <div>{{$invoiceFromPartner->county}}</div>
                                    <div>{{$invoiceFromPartner->city}}</div>
                                    <div>{{$invoiceFromPartner->post_code}}</div>
                                    <div><strong>Finance Tel:</strong> {{$invoiceFromPartner->finance_tel ?? 'N/A'}}</div>
                                    <div><strong>Finance Email:</strong> {{$invoiceFromPartner->finance_email ?? 'N/A'}}</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th class="right">Amount</th>
                                    <th class="center">VAT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoiceItems as $invoiceItem)
                                <tr>
                                    <td class="left strong">{{$invoiceItem->item_name.' '.$invoiceItem->item_description}}</td>
                                    <td class="right">£{{$invoiceItem->item_price}}</td>
                                    <td class="right">{{$invoiceItem->vat_perc}}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5">
                        </div>
                        <div class="col-lg-4 col-sm-5 ml-auto">
                            <table class="table table-clear">
                                <tbody>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">Total (Net)</strong>
                                        </td>
                                        <td class="right">£{{number_format($total = array_sum(array_column($invoiceItems, 'item_price')), 2)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">VAT</strong>
                                        </td>
                                        <td class="right">£{{number_format($vat = ($total * (array_sum(array_column($invoiceItems, 'vat_perc')) / 100)), 2)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">Balance Due</strong> </td>
                                        <td class="right">
                                            <strong class="text-dark">£{{number_format($total + $vat, 2)}}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <table class="w-50">
                            <tr>
                                <td>
                                    <table class="table table-clear table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="left">
                                                    <strong class="text-dark">Payment Terms</strong>
                                                </td>
                                                <td class="left">Net 30/ 30 days</td>
                                            </tr>
                                            <tr>
                                                <td class="left">
                                                    <strong class="text-dark">Payment Due Date</strong>
                                                </td>
                                                <td>{{carbon($quarterPartner->invoice_date)->addMonth()->format('Y-m-d')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table class="w-100 mb-3">
                            <tr style="background-color:#000000;color: #FFFFFF;">
                                <td>
                                    <strong>BANK DETAILS</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table class="w-100 mt-3">
                            <tr>
                                <td style="background-color:#000000;color: #FFFFFF;">
                                    <strong>FOR BACS PAYMENTS</strong>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong class="text-dark">ACCOUNT NAME:</strong>
                                </td>
                                <td class="left">{{$invoiceFromPartner->account_name}}</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong class="text-dark">BANK NAME</strong>
                                </td>
                                <td>{{$invoiceFromPartner->bank_name}}</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong class="text-dark">SORT CODE</strong>
                                </td>
                                <td>{{$invoiceFromPartner->sort_code}}</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong class="text-dark">ACCOUNT NUMBER</strong>
                                </td>
                                <td>{{$invoiceFromPartner->account_no}}</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong class="text-dark">IBAN</strong>
                                </td>
                                <td>{{$invoiceFromPartner->iban}}</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong class="text-dark">SWIFT</strong>
                                </td>
                                <td>{{$invoiceFromPartner->swift}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table class="w-100 mt-3">
                            <tr>
                                <td style="background-color:#000000;color: #FFFFFF;">
                                    <strong>ONLINE PAYEMENTS</strong>
                                </td>
                                <td width="60%"></td>
                            </tr>
                            <tr>
                                <td>
                                    <!-- <a href="{{$invoiceFromPartner->web_url}}">{{$invoiceFromPartner->web_url}}</a> -->
                                    {{$invoiceFromPartner->web_url}}
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table class="w-100">
                            <tr>
                                <td>
                                    VAT REG No: {{$invoiceFromPartner->vat}}        
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>