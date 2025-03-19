<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastSushi Composition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> 
    
    .parent {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    grid-template-rows: repeat(6, 1fr);
    gap: 8px;
    
    max-width: 80%; /* Largeur max pour éviter que ça prenne tout l'écran */
    margin: auto; /* Centre la grille */
    padding: 20px; /* Ajoute de l’espace autour */
}

/* 🍣 Plats principaux */
.div1 {
    grid-column: 1 / span 2;
    grid-row: 1 / span 3;
    background-color: #ffcc80;
}

.div2 {
    grid-column: 3 / span 2;
    grid-row: 1 / span 3;
    background-color: #80cbc4;
}

.div3 {
    grid-column: 5 / span 2;
    grid-row: 1 / span 3;
    background-color: #ff8a80;
}

/* 🥑 Ingrédients customisables */
.div4 { grid-column: 1 / span 2; grid-row: 4 / span 1; }
.div5 { grid-column: 3 / span 2; grid-row: 4 / span 1; }
.div6 { grid-column: 5 / span 2; grid-row: 4 / span 1; }
.div7 { grid-column: 1 / span 2; grid-row: 5 / span 1; }
.div8 { grid-column: 3 / span 2; grid-row: 5 / span 1; }
.div9 { grid-column: 5 / span 2; grid-row: 5 / span 1; }
.div10 { grid-column: 1 / span 2; grid-row: 6 / span 1; }
.div11 { grid-column: 3 / span 2; grid-row: 6 / span 1; }
.div12 { grid-column: 5 / span 2; grid-row: 6 / span 1; }

/* 🌟 Visibilité et esthétique */
.parent > div {
    text-align: center;
    font-size: 1.2rem;
    padding: 15px;
    border: 1px solid #aaa;
    background-color: #eee;
}

/* 📱 Responsive pour petits écrans */
@media (max-width: 768px) {
    .parent {
        max-width: 95%; /* Adapte la largeur pour les écrans plus petits */
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: auto;
    }
    
    .div1, .div2, .div3 {
        grid-column: span 3; /* Les plats prennent toute la largeur */
    }
    
    .div4, .div5, .div6, .div7, .div8, .div9, .div10, .div11, .div12 {
        grid-column: span 3; /* Ingrédients aussi */
    }
}
 
    </style>
</head>
<body>
    
<div class="parent">
    <div class="div1">1</div>
    <div class="div2">2</div>
    <div class="div3">3</div>
    <div class="div4">4</div>
    <div class="div5">5</div>
    <div class="div6">6</div>
    <div class="div7">7</div>
    <div class="div8">8</div>
    <div class="div9">9</div>
    <div class="div10">10</div>
    <div class="div11">11</div>
    <div class="div12">12</div>
</div>
    
</body>
