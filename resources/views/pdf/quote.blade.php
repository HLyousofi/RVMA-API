<!DOCTYPE html>
<html>
<head>
    <title>Devis {{ $workOrder->workorder_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 10px;
            padding: 0;
            color: #000;
            font-size: 12px; /* Taille par défaut compacte */
        }
        .container {
            max-width: 190mm; /* Largeur A4 */
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header p {
            margin: 2px 0;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
            color : #00BFF7;
        }
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .info-left, .info-right {
            width: 48%;
        }
        .info-left p, .info-right p {
            margin: 2px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .table th, .table td {
           
            padding: 5px;
            text-align: left;
        }
        .table th {
            /* border: 1px solid #000; */
            font-weight: bold;
        }
        .titleTable tr {
            border-bottom: 1px solid #000;
            border-color : #808080;
            font-weight: bold;
        }
        .totals {
            text-align: right;
            font-size: 14px;
            margin-top: 10px;

        }
        .totals p {
            margin: 2px 0;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
        }
        .hors-tax {
            text-align: right;
        }
        .table th:nth-child(4), .table td:nth-child(4) {
            text-align: right;
        }

        .totalsCalcule {
            display: inline-block;
            border-bottom: 1px solid #374151;
            border-top : 1px solid #374151;
            border-color : #808080;
        }
        
        
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête (informations du garage) -->
        <div class="header">
            <p><strong>Garage Mécanique Dupont</strong></p>
            <p>45 Avenue des Champs, 69000 Lyon</p>
            <p>France</p>
            <p>Tél : +33 4 78 90 12 34</p>
            <p>Email : contact@mecaniquedupont.fr</p>
        </div>

        <!-- Titre du devis -->
        <div class="title">
            {{ $workOrder->workorder_number }}
        </div>

        <!-- Informations client et véhicule -->
        <div class="info-grid">
            <div class="info-left">
                <p><strong>Date du devis :</strong> {{ $workOrder->created_at ? date('d/m/Y', strtotime($workOrder->created_at)) : 'N/A' }}</p>
                <p><strong>Échéance :</strong> {{ $workOrder->expiration_date ? date('d/m/Y', strtotime($workOrder->expiration_date)) : 'N/A' }}</p>
                <p><strong>Vendeur :</strong> Administrator</p>
            </div>
            <div class="info-right">
                <p><strong>Client :</strong> {{ $workOrder->customer->name }}</p>
                <p><strong>Adresse :</strong> {{ $workOrder->customer->adress }}</p>
                <p><strong>Véhicule :</strong> {{ $workOrder->vehicle->brand->label }} {{ $workOrder->vehicle->model }}</p>
                <p><strong>Immatriculation :</strong> {{ $workOrder->vehicle->plate_number }}</p>
            </div>
        </div>

        <!-- Tableau des produits -->
        <table class="table">
            <thead class="titleTable">
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Montant hors taxes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workOrder->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }},00 Unité(s)</td>
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
            <p>Garage Mécanique Dupont - SIRET : 987 654 321 00019</p>
            <p>Page : 1 sur 1</p>
        </div>
    </div>
</body>
</html>