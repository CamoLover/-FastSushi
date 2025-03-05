<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Restaurant Japonais</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #d32f2f;
        }
        .item {
            margin-bottom: 20px;
        }
        .item h3 {
            margin: 0;
            font-size: 18px;
        }
        .item p {
            margin: 5px 0;
        }
        .summary {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .summary p {
            margin: 5px 0;
        }
        .total {
            font-weight: bold;
            font-size: 20px;
        }
        .payment {
            margin-top: 20px;
        }
        .button {
            background-color: #d32f2f;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>
    

        <div class="item">
            <h2>Sushi Combo Deluxe</h2>
            <p>Assortiment de sushis et sashimis frais</p>
            <p>Taille: Grand</p>
            
        </div>
<br>
        <div class="summary">
            <p><strong>As-tu un code promo ?</strong> ✓</p>
            <p><strong>Sous-total</strong> 45,99 €</p>
      
            <p class="total">Total: 45,99 €</p>
        </div>

<br>
        <div class="payment">

            <button class="button">Payer maintenant</button>
        </div>
<br>
       

      
    </div>
</body>
</html>