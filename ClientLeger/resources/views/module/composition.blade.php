<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastSushi - Customisation</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #fdf7f1;
        }
        .container {
            width: 80%;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            grid-template-rows: repeat(6, 1fr);
            gap: 10px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
        }
        .box {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #333;
            border-radius: 8px;
            background: #fafafa;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 15px;
            position: relative;
            transition: background 0.3s, color 0.3s;
        }
        .dropzone {
            background: #ddd;
            border: 2px dashed #333;
            position: relative;
        }
        .hidden {
            display: none !important;
        }
        
        /* Effet hover rouge avec icône poubelle */
        .selected-ingredient {
            position: relative;
        }
        .selected-ingredient:hover {
            background: red;
            color: transparent; /* Cache uniquement le texte en rendant la couleur transparente */
        }
        .delete-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            color: white;
            display: none;
        }
        .selected-ingredient:hover .delete-icon {
            display: block;
            color: white; /* L'icône poubelle reste visible */
        }

        .reset-button {
            grid-column: 1 / span 6;
            grid-row: 6;
            background: #ff5e57;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container" id="main-container">
        <!-- Sélection des plats -->
        <div class="box plat" data-plat="1" style="grid-column: 1 / span 2; grid-row: 1 / span 3;">Maki</div>
        <div class="box plat" data-plat="2" style="grid-column: 3 / span 2; grid-row: 1 / span 3;">California Rolls</div>
        <div class="box plat" data-plat="3" style="grid-column: 5 / span 2; grid-row: 1 / span 3;">Spring Rolls</div>
        
        <!-- Zone où s'affiche le plat sélectionné -->
        <div class="box hidden" id="selected-plat" style="grid-column: 1 / span 2; grid-row: 1 / span 3;"></div>
        <!-- Zone de drop pour les ingrédients -->
        <div class="box dropzone hidden" id="custom-zone" style="grid-column: 3 / span 4; grid-row: 1 / span 3;">
            <span id="custom-zone-text">Glissez vos ingrédients ici (3 maximum )</span>
        </div>

        <!-- Liste des ingrédients disponibles -->
        <div class="box ingredient" draggable="true">Ingrédient 1</div>
        <div class="box ingredient" draggable="true">Ingrédient 2</div>
        <div class="box ingredient" draggable="true">Ingrédient 3</div>
        <div class="box ingredient" draggable="true">Ingrédient 4</div>
        <div class="box ingredient" draggable="true">Ingrédient 5</div>
        <div class="box ingredient" draggable="true">Ingrédient 6</div>
        <div class="box ingredient" draggable="true">Ingrédient 7</div>
        <div class="box ingredient" draggable="true">Ingrédient 8</div>
        <div class="box ingredient" draggable="true">Ingrédient 9</div>

        <!-- Bouton de réinitialisation -->
        <button class="reset-button" id="reset-button">Réinitialiser</button>
    </div>
    
    <script>
        const plats = document.querySelectorAll('.plat');
        const selectedPlat = document.getElementById('selected-plat');
        const customZone = document.getElementById('custom-zone');
        const customZoneText = document.getElementById('custom-zone-text');
        const ingredients = document.querySelectorAll('.ingredient');
        const resetButton = document.getElementById('reset-button');

        // Gérer la sélection du plat ou l'annulation
plats.forEach(plat => {
    plat.addEventListener('click', () => {
        if (selectedPlat.textContent === plat.textContent) {
            // Réafficher tous les plats et réinitialiser
            plats.forEach(p => p.classList.remove('hidden'));
            selectedPlat.classList.add('hidden');
            customZone.classList.add('hidden');

            // Vider la zone de drop des ingrédients
            customZone.innerHTML = '<span id="custom-zone-text">Glissez vos ingrédients ici</span>';
            customZoneText = document.getElementById('custom-zone-text'); // Récupérer à nouveau l'élément
            return;
        }

        // Sélection normale du plat
        selectedPlat.classList.remove('hidden');
        customZone.classList.remove('hidden');
        selectedPlat.textContent = plat.textContent;
        plats.forEach(p => p.classList.add('hidden'));
        updateCustomZoneMessage();
    });
});

// Fonction pour gérer l'affichage du texte dans la zone de drop
function updateCustomZoneMessage() {
    if (customZone.querySelectorAll('.selected-ingredient').length === 0) {
        customZoneText.style.display = "block";
    } else {
        customZoneText.style.display = "none";
    }
}

// Activer le drag & drop des ingrédients
ingredients.forEach(ingredient => {
    ingredient.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text/plain', ingredient.textContent);
    });
});

// Autoriser le drop sur la zone de customisation
customZone.addEventListener('dragover', (e) => {
    e.preventDefault();
});

// Gestion du drop des ingrédients avec limite de 3
customZone.addEventListener('drop', (e) => {
    e.preventDefault();

    if (customZone.querySelectorAll('.selected-ingredient').length >= 3) {
        return;
    }

    const ingredientName = e.dataTransfer.getData('text/plain');
    const newIngredient = document.createElement('div');
    newIngredient.classList.add('box', 'selected-ingredient');
    newIngredient.textContent = ingredientName;
    
    const deleteIcon = document.createElement('i');
    deleteIcon.classList.add('fas', 'fa-trash', 'delete-icon');
    newIngredient.appendChild(deleteIcon);

    // Supprimer l'ingrédient au clic et mettre à jour l'affichage
    newIngredient.addEventListener('click', () => {
        newIngredient.remove();
        updateCustomZoneMessage();
    });

    customZone.appendChild(newIngredient);
    updateCustomZoneMessage();
});

    </script>
</body>
</html>
