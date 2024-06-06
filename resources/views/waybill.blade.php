<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        #invoice-POS {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #FFF;
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
        }

        #invoice-POS h1 {
            font-size: 1.5em;
            color: #222;
        }

        #invoice-POS h2 {
            font-size: 1.2em;
        }

        #invoice-POS h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        #invoice-POS p {
            font-size: 0.9em;
            color: #666;
            line-height: 1.2em;
        }

        #invoice-POS .info {
            margin: 20px 0;
        }

        #invoice-POS .title {
            float: right;
        }

        #invoice-POS .title p {
            text-align: right;
        }

        #invoice-POS table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #invoice-POS .tabletitle {
            font-size: 0.9em;
            background: #EEE;
            padding: 5px 0;
        }

        #invoice-POS .service {
            border-bottom: 1px solid #EEE;
        }

        #invoice-POS .itemtext {
            font-size: 0.9em;
        }

        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

        .row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .column {
            width: 48%;
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .logo img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>

<body>

    <div id="invoice-POS">

        <center id="top" class="logo">
          <img src="public/assets/img/realdeal logo.png" alt="Realdeal Logistics Logo">

            <div class="info">
                <h2>Realdeal Logistics</h2>
            </div>
        </center>

        <div id="mid" class="row">
            <div class="column">
                <div class="info">
                    <h2>Shipped From:</h2>
                    <p>
                        Realdeal Logistics<br>
                        +254 758 928 555<br>
                        Realdeallogistics@gmail.com<br>
                        "Your trusted delivery company"
                    </p>
                </div>
            </div>
            <div class="column">
                <div class="info">
                    <h2>Shipped To:</h2>
                    <p>
                        {{ $clientName }}<br>
                        {{ $phone }}<br>
                        {{ $date }}<br>
                        {{ $clientCity }}<br>
                    </p>
                </div>
            </div>
        </div>

        <div id="bot">

            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td class="item">
                            <h2>Item</h2>
                        </td>
                        <td class="Hours">
                            <h2>Amount</h2>
                        </td>
                        <td class="Hours">
                            <h2>Quantity</h2>
                        </td>
                        <td class="Rate">
                            <h2>Total Amount</h2>
                        </td>
                    </tr>

                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext">{{ $item }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $amount }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $quantity }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ intval($amount) * intval($quantity) }}</p>
                        </td>
                    </tr>

                    <tr class="tabletitle">
                        <td></td>
                        <td></td>
                        <td class="Rate">
                            <h2>Amount Total</h2>
                        </td>
                        <td class="payment">
                            <h2>{{ intval($amount) * intval($quantity) }}</h2>
                        </td>
                    </tr>

                </table>
            </div>

            <div id="legalcopy">
                <p class="legal"><strong>Thank you for your business!</strong> Payment is expected within 31 days;
                    please process this invoice within that time. There will be a 5% interest charge per month on late
                    invoices.
                </p>
            </div>

            <div class="qr-code">
                {!! DNS2D::getBarcodeHTML("$orderNo,",'QRCODE',7,7) !!}
            </div>

        </div>
    </div>

</body>

</html>
