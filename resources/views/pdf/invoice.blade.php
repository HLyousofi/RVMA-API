<!DOCTYPE html>
<html>
<head>
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: #000;
            font-size: 14px;
        }
        .container {
            max-width: 190mm;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #374151;
            padding-bottom: 0px;
        }
        .logo img {
            width: 80px;
            height: auto;
        }
        .company-info {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .tagline {
            font-size: 14px;
            font-weight: normal;
            color: #555;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
            color: #00BFC4;
            padding-top: 30px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 10px;
            table-layout: fixed;
            padding-top: 15px;
        }
        .info-left {
            width: 100%;
            text-align: left;
            margin-bottom: 20px;
        }
        .info-right {
            width: 50%;
            padding-left: 50%;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }
        .info-left p, .info-right p {
            margin: 2px 0;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 14px;
            padding-top: 15px;
            border-bottom: 1px solid #808080;
        }
        .table th, .table td {
            padding: 5px;
            text-align: left;
        }
        .titleTable tr {
            border-bottom: 1px solid #808080;
            font-weight: bold;
        }
        .totals {
            text-align: right;
            font-size: 14px;
            margin-top: 10px;
        }
        .totals p {
            margin: 4px 0;
            padding: 4px;
        }
        .total-in-words {
            text-align: left;
            font-size: 14px;
            margin-top: 20px;
            font-style: italic;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            border-top: 2px solid #374151;
            text-align: center;
            font-size: 12px;
            background-color: #fff;
        }
        .table th:nth-child(4), .table td:nth-child(4) {
            text-align: right;
        }
        .totalsCalcule {
            display: inline-block;
            border-bottom: 1px solid #808080;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header (Company Information) -->
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('storage/' . $company->logo_path) }}" alt="{{ $company->company_name }} Logo" style="width: 80px; height: auto;" />
                </td>
                <td class="company-info">
                    <p>{{ $company->company_name }}</p>
                </td>
            </tr>
        </table>

        <!-- Invoice Title -->
        <div class="title">
            Facture :{{ $invoice->invoice_number }}
        </div>

        <!-- Invoice and Client Information -->
        <div class="info-grid">
            <div class="info-right">
                <p><strong>Client :</strong> {{ $invoice->customer->name }}</p>
                <p><strong>ICE :</strong> {{ $invoice->customer->ice }}</p>
            </div>
            <div class="info-left">
                <p><strong>Date d'émission :</strong> {{ $invoice->created_at ? date('d/m/Y', strtotime($invoice->created_at)) : 'N/A' }}</p>
                <!-- <p><strong>Date d'échéance :</strong> {{ $invoice->due_date ? date('d/m/Y', strtotime($invoice->due_date)) : 'N/A' }}</p> -->
                <!-- <p><strong>Statut :</strong> {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</p> -->
                <p><strong>Véhicule :</strong> {{ $invoice->vehicle->brand->label }} {{ $invoice->vehicle->model }}</p>
            </div>
        </div>

        <!-- Products Table -->
        <table class="table">
            <thead class="titleTable">
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Montant hors taxes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }},00</td>
                        <td>{{ number_format($product->pivot->unit_price, 2, ',', ' ') }} MAD</td>
                        <td>{{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2, ',', ' ') }} MAD</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totalsCalcule">
                <p><strong>Montant hors taxes :</strong> {{ number_format($invoice->amount, 2, ',', ' ') }} MAD</p>
                <p><strong>TVA 20% :</strong> {{ number_format($invoice->amount * 0.20, 2, ',', ' ') }} MAD</p>
            </div>
            <p><strong>Total :</strong> {{ number_format($invoice->amount * 1.20, 2, ',', ' ') }} MAD</p>
        </div>

        <!-- Total in Words -->
        <div class="total-in-words">
            <p><strong>Montant en lettres :</strong> {{ (new \NumberToWords\NumberToWords())->getNumberTransformer('fr')->toWords($invoice->amount * 1.20) }} Dirhams</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ $company->address }}</p>
            <p>TEL : {{ $company->phone }} EMAIL : {{ $company->email }} ICE : {{ $company->ice_number }}</p>
        </div>
    </div>
</body>
</html>