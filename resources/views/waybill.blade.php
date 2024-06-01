<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        {{ config('app.name') }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet"
        href="{{ asset('assets/invoice/fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
</head>

<body>
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="logo">
                                        <h1>REALDEAL LOGISTICS</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="invoice">
                                        <h1>
                                            Order No # <span>01000</span>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-info">
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <div class="invoice-number">
                                        <h4 class="inv-title-1">
                                            Order date:
                                      </h4>
                                        <p class="invo-addr-1">
                                            01 - 09 - 2024
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <h4 class="inv-title-1">Customer</h4>
                                    <p class="inv-from-1">Client Name</p>
                                    <p class="inv-from-1">Client Phone</p>
                                    <p class="inv-from-1">Date</p>
                                    <p class="inv-from-2">Client City</p>
                                </div>
                               
                                <div class="col-sm-6 text-end mb-50">
                                    <h4 class="inv-title-1">Realdeal Logistics</h4>
                                    <p class="inv-from-1">+254 758 928 555</p>
                                    <p class="inv-from-1">Realdeallogistics@gmail.com</p>
                                    <p class="inv-from-2">"Your trusted delivery company"</p>
                                </div>
                            </div>
                        </div>
                        <div class="order-summary">
                            <div class="table-outer">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">Item</th>
                                            <th class="align-middle text-center">Amount</th>
                                            <th class="align-middle text-center">Quantity</th>
                                            <th class="align-middle text-center">Total Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                      
                                            <tr>
                                                <td class="align-middle">
                                                 
                                                </td>
                                                <td class="align-middle text-center">
                                                   
                                                </td>
                                                <td class="align-middle text-center">
                                                  
                                                </td>
                                                <td class="align-middle text-center">
                                                   
                                                </td>
                                            </tr>
                                        

                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>
                                                    Amount Total
                                                </strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                   KSH 
                                                </strong>
                                            </td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="invoice-informeshon-footer">
                                <ul>
                                    <li><a href="#">www.Realdeal logistics.com</a></li>
                                    <li><a href="Realdeal logistics.com">Realdeal Logistics.com</a></li>
                                    <li><a href="tel:+088-01737-133959">+254 712 345 678</a></li>
                                </ul>
                            </div>
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Print Order
                        </a>
                        <a id="" class="btn btn-lg btn-download">
                            <i class="fa fa-download"></i>
                            Download Order
                        </a>
                    </div>

                    {{-- back button --}}
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="" class="btn btn-lg btn-print">
                            <i class="fa fa-arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/invoice/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/html2canvas.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/app.js') }}"></script>
</body>

</html>