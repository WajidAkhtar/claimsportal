<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $quarter->name.' Invoice')</title>
        <!-- START: Template CSS-->
        <link rel="stylesheet" href="{{ asset('assets/backend/vendors/bootstrap/css/bootstrap.min.css') }}">
        <!-- START: Custom CSS-->
        <link rel="stylesheet" href="{{ asset('assets/backend/css/main.css') }}">
        <style>
            body {
                background-color: #FFF;
                color: #000
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
                font-size: 20px
            }

            h5 {
                font-size: 15px;
                line-height: 26px;
                /* color: #c2c2c4; */
                margin: 0px 0px 15px 0px;
                /* font-family: 'Circular Std Medium' */
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
                                @if(!empty($invoiceFrom->logo) && file_exists(public_path('uploads/organisations/logos/'.$invoiceFrom->logo)))
                                    <img src="{{ asset('uploads/organisations/logos/'.$invoiceFrom->logo) }}" height="160" width="160" />
                                @else
                                    {{ $invoiceFrom->organisation_name }}
                                @endif
                            </td>
                            <td class="float-right">
                                <h5><strong>Invoice Date: </strong> {{$quarterPartner->pivot->invoice_date}}</h5>
                                <h5><strong>Invoice Number: </strong> #{{$quarterPartner->pivot->invoice_no}}</h5>
                                <h5><strong>PO Number: </strong> {{$quarterPartner->pivot->po_number}}</h4>
                                <h5><strong>Customer Account Number: </strong> {{$invoiceToPartner->account_no}}</h4>
                                <h5><strong>Contract Ref: </strong> {{$invoiceToPartner->customer_ref}}</h4>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="">
                    <div class="row mb-4 m-2">
                        <table class="w-100">
                            <tr>
                                <td>
                                    <h5 class="mb-3">Invoice To:</h5>
                                    <h3 class="text-dark mb-1">{{$invoiceTo->organisation_name}}</h3>
                                    <div>{{$invoiceTo->street_address}}</div>
                                    <div>{{$invoiceTo->address_line_2}}</div>
                                    <div>{{$invoiceTo->county}}</div>
                                    <div>{{$invoiceTo->city}}</div>
                                    <div>{{$invoiceTo->postcode}}</div>
                                    <div><strong>Finance Tel:</strong> {{$invoiceToPartner->finance_tel ?? 'N/A'}}</div>
                                    <div><strong>Finance Email:</strong> {{$invoiceToPartner->finance_email ?? 'N/A'}}</div>
                                </td>
                                <td class="float-right">
                                    <h5 class="mb-3">Invoice From:</h5>
                                    <h3 class="text-dark mb-1">{{$invoiceFrom->organisation_name}}</h3>
                                    <div>{{$invoiceFrom->street_address}}</div>
                                    <div>{{$invoiceFrom->address_line_2}}</div>
                                    <div>{{$invoiceFrom->county}}</div>
                                    <div>{{$invoiceFrom->city}}</div>
                                    <div>{{$invoiceFrom->postcode}}</div>
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
                                                <td>{{carbon($quarterPartner->pivot->invoice_date)->addMonth()->format('Y-m-d')}}</td>
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
                                    <h3>BANK DETAILS</h3>
                                </td>
                            </tr>
                        </table>
                        <table class="w-50">
                            <tr>
                                <td>
                                    <h5 style="background-color:#000000;color: #FFFFFF;">FOR BACS PAYMENTS</h5>
                                </td>
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
                        <table class="w-100">
                            <tr>
                                <td>
                                    <h5 style="background-color:#000000;color: #FFFFFF;">ONLINE PAYEMENTS</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{$invoiceFromPartner->web_url}}
                                </td>
                            </tr>
                        </table
                    </div>
                    <div class="row mt-5">
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