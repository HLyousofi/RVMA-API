<!DOCTYPE html>
<html>
<head>
    <title>Devis {{ $workOrder->workorder_number }}</title>
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
            text-align: right; /* Aligner le nom de la société à droite */
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
            margin-bottom: 20px; /* Espace entre les deux blocs */
        }

        .info-right {
            width: 50%; /* Ajuste la largeur pour bien occuper la moitié droite */
            padding-left: 50%;
            text-align: left;
            word-wrap: break-word; /* Permet de couper les mots longs */
            overflow-wrap: break-word; /* Assure la rupture des mots */
            white-space: normal; /* Permet le retour à la ligne */
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
        .table th {
            font-weight: bold;
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
            padding: 4px ;
        }
       
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            border-top: 2px solid #374151;
            text-align: center;
            font-size: 12px;
            background-color: #fff; /* Assurez-vous qu'il est visible */
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
        <!-- En-tête (informations du garage) -->
        <table class="header">
            <!-- Logo -->
            <tr>
                <td class="logo">
                    <img src="{{ public_path('storage/' . $company->logo_path)}}" alt="G.A.Y. Fast Auto Logo" style="width: 80px; height: auto;" />
                </td>
                <!-- Company Info -->
                <td class="company-info">
                    <p>{{ $company->company_name }}</p>
                    <!-- <p class="tagline">Là où votre voiture retrouve sa jeunesse.</p> -->
                </td>
            </tr>
        </table>
        </div>

        <!-- Titre du devis -->
        <div class="title">
            {{ $workOrder->workorder_number }}
        </div>

        <!-- Informations client et véhicule -->
        <div class="info-grid">
           
            <div class="info-right">
                <p><strong>Client :</strong> {{ $workOrder->customer->name }}</p>
                <!-- <p><strong>Adresse :</strong> {{ $workOrder->customer->adress }}</p> -->
                <p><strong>ICE :</strong> {{ $workOrder->customer->ice }}</p>
            </div>
            <div class="info-left">
                <p><strong>Date du devis :</strong> {{ $workOrder->created_at ? date('d/m/Y', strtotime($workOrder->created_at)) : 'N/A' }}</p>
                <!-- <p><strong>Échéance :</strong> {{ $workOrder->expiration_date ? date('d/m/Y', strtotime($workOrder->expiration_date)) : 'N/A' }}</p> -->
                <p><strong>Véhicule :</strong> {{ $workOrder->vehicle->brand->label }} {{ $workOrder->vehicle->model }}</p>
            </div>
        </div>

        <!-- Tableau des produits -->
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
                @foreach ($workOrder->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }},00 </td>
                        <td>{{ number_format($product->pivot->unit_price, 2, ',', ' ') }} MAD</td>
                        <td>{{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2, ',', ' ') }} MAD</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totals">
            <div class="totalsCalcule">
                <p><strong>Montant hors taxes :</strong> {{ number_format($workOrder->total, 2, ',', ' ') }} MAD</p>
                <p><strong>TVA 20% :</strong> {{ number_format($workOrder->total * 0.20, 2, ',', ' ') }} MAD</p>
            </div>
            <p><strong>Total :</strong> {{ number_format($workOrder->total * 1.20, 2, ',', ' ') }} MAD</p>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>{{ $company->address }}</p>
            <p>TEL : {{ $company->phone }} EMAIL : {{ $company->email }} ICE : {{ $company->ice_number }}</p>
        </div>
    </div>
</body>
</html>