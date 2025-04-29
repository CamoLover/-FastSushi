# Documentation Fast Sushi

## Structure du Projet
Le projet Fast Sushi est une application Laravel qui suit l'architecture MVC (Modèle-Vue-Contrôleur). Voici la structure principale du projet :

```
FastSushi/
├── app/                 # Logique métier (Controllers, Models, etc.)
├── resources/           # Vues et assets
│   ├── views/           # Templates Blade
│   │   ├── layouts/     # Layouts principaux
│   │   └── module/      # Composants réutilisables
├── routes/              # Configuration des routes
│   ├── web.php          # Routes web
│   └── api.php          # Routes API
├── public/              # Fichiers publics (images, css, js)
└── database/            # Migrations et seeds
```

## Guide de Création de Nouvelles Pages

### 1. Structure de Base d'une Page
Chaque nouvelle page doit étendre le layout principal `home.blade.php`. Voici le template de base :

```blade
@extends('layouts.home')

@section('title', 'Fast Sushi - [Nom de la Page]')

@push('styles')
<style>
    /* CSS spécifique à la page */
</style>
@endpush

@section('content')
    <!-- Contenu HTML de la page -->
@endsection

@push('scripts')
<script>
    // JavaScript spécifique à la page
</script>
@endpush
```

### 2. Étapes pour Créer une Nouvelle Page

1. **Créer la Vue** :
   - Créer un nouveau fichier dans `resources/views/` avec l'extension `.blade.php`
   - Utiliser le template de base ci-dessus

2. **Ajouter la Route** :
   - Ouvrir `routes/web.php`
   - Ajouter une nouvelle route :
     ```php
     Route::get('/nom-de-la-page', [NomController::class, 'nomMethode'])->name('nom.page');     # si besoin de controller

     Route::get('/signup', function () { return view('signup'); });                             # si pas besoin de controller
     ```

3. **Créer le Controller** (si nécessaire) :
   - Créer un nouveau controller dans `app/Http/Controllers/`
   - Utiliser la commande : `php artisan make:controller NomController`

## Guide de Modification

### Layout Principal (home.blade.php)
**Fichier** : `resources/views/layouts/home.blade.php`
- **En-tête et Navigation** : Lignes 1-50
- **Footer** : Lignes 100-150
- **Scripts Globaux** : Lignes 130-152

### Pages Principales

1. **Page d'Accueil**
   - **Vue** : `resources/views/welcome.blade.php`
   - **Controller** : `app/Http/Controllers/HomeController.php`
   - **Route** : Définie dans `routes/web.php` (ligne ~10)

2. **Menu**
   - **Vue** : `resources/views/menu.blade.php`
   - **Controller** : `app/Http/Controllers/MenuController.php`
   - **Modifications Produits** : Lignes 50-150

3. **Panier**
   - **Vue** : `resources/views/_panier.blade.php`
   - **Controller** : `app/Http/Controllers/CartController.php`
   - **Popup** : `resources/views/panierpopup.blade.php`

4. **Profil Utilisateur**
   - **Vue** : `resources/views/profil.blade.php`
   - **Controller** : `app/Http/Controllers/ProfileController.php`

### Composants Réutilisables
Les composants réutilisables se trouvent dans `resources/views/module/`. Pour les modifier :

1. **Header**
   - Fichier : `resources/views/module/header.blade.php`
   - Navigation : Lignes 10-30
   - Logo : Lignes 5-8

2. **Footer**
   - Fichier : `resources/views/module/footer.blade.php`
   - Liens : Lignes 15-40
   - Copyright : Lignes 45-50

## Styles et Assets

1. **CSS Global**
   - Fichier principal : `resources/css/app.css`
   - Configuration Tailwind : `tailwind.config.js`

2. **JavaScript**
   - Fichier principal : `resources/js/app.js`
   - Scripts spécifiques : Dans chaque vue sous la section `@push('scripts')`

3. **Images**
   - Dossier : `public/images/`
   - Pour ajouter : Placer dans le dossier et référencer via `/images/nom-image.ext`

## Base de Données

1. **Migrations**
   - Dossier : `database/migrations/`
   - Pour créer : `php artisan make:migration nom_migration`

2. **Models**
   - Dossier : `app/Models/`
   - Modifications relations : Dans les méthodes des models

## API Routes
Pour les modifications API :
- Fichier : `routes/api.php`
- Controllers : `app/Http/Controllers/Api/`

## Conseils de Développement

1. **Avant de Commencer**
   - Lire la documentation Laravel : [https://laravel.com/docs]
   - Comprendre Blade : [https://laravel.com/docs/blade]
   - Installer les dépendances : `composer install` et `npm install`

2. **Environnement Local**
   - Copier `.env.example` vers `.env`
   - Générer la clé : `php artisan key:generate`
   - Configurer la base de données dans `.env`

3. **Commandes Utiles**
   ```bash
   php artisan serve            # Lancer le serveur
   npm run dev                  # Compiler les assets
   php artisan cache:clear      # Nettoyer le cache
   ```

4. **Bonnes Pratiques**
   - Toujours utiliser les layouts existants
   - Suivre les conventions de nommage Laravel
   - Commenter le code complexe
   - Utiliser les composants Blade pour le code réutilisable

## Support et Ressources
- Documentation Laravel : [https://laravel.com/docs]
- Documentation Tailwind : [https://tailwindcss.com/docs]
- Pour les questions : Contacter l'équipe de développement




<!-- 
▒█▀▀▀ ▀▄▒▄▀ ░█▀▀█ ▒█▀▄▀█ ▒█▀▀█ ▒█░░░ ▒█▀▀▀ 
▒█▀▀▀ ░▒█░░ ▒█▄▄█ ▒█▒█▒█ ▒█▄▄█ ▒█░░░ ▒█▀▀▀ 
▒█▄▄▄ ▄▀▒▀▄ ▒█░▒█ ▒█░░▒█ ▒█░░░ ▒█▄▄█ ▒█▄▄▄  
-->

## Exemples Pratiques

### Exemple 1: Gestion des États des Commandes
**Tâche** : Modifier l'affichage des états des commandes dans le profil utilisateur
- **Fichier** : `resources/views/profil.blade.php`
- **Lignes** : 121-128
```php
<span class="px-2 py-1 text-xs font-semibold rounded-full 
    @if($commande->statut == 'En attente') bg-yellow-200 text-yellow-800
    @elseif($commande->statut == 'livré') bg-green-200 text-green-800
    @elseif($commande->statut == 'Annulée') bg-red-200 text-red-800
    @else bg-blue-200 text-blue-800
    @endif">
    {{ $commande->statut }}
</span>
```

<!-- ////////////////////////////////
////////////////////////////////
//////////////////////////////// -->

### Exemple 2: Système de Panier
**Tâche** : Comprendre et modifier le système de panier
1. **Vue Principale** :
   - **Fichier** : `resources/views/_panier.blade.php`
   - **Fonctionnalités clés** :
     - Affichage par catégories (Entrée, Plats, Desserts, etc.)
     - Gestion des quantités (lignes 150-200)
     - Calcul des totaux (lignes 600-650)
     - Gestion des produits personnalisés (lignes 50-100)

2. **Intégration** :
   - **Fichier** : `resources/views/panier.blade.php`
   ```php
   @extends('layouts.home')
   @section('content')
       @include('_panier')
   @endsection
   ```

<!-- ////////////////////////////////
////////////////////////////////
//////////////////////////////// -->

### Exemple 3: Page Menu et Produits
**Tâche** : Modifier l'affichage des produits dans le menu
- **Fichier** : `resources/views/menu.blade.php`
- **Sections principales** :
  ```php
  <!-- Entrées -->
  <div class="mb-12" id="entrees">
      <h2 class="text-2xl font-bold mb-8 text-red-600">
          <i class="fas fa-utensils mr-3"></i>Entrée
      </h2>
      <!-- ... -->
  </div>

  <!-- Plats -->
  <div class="mb-12" id="plats">
      <!-- ... -->
  </div>
  ```

<!-- ////////////////////////////////
////////////////////////////////
//////////////////////////////// -->

### Exemple 4: Système d'Authentification
**Tâche** : Comprendre et modifier le système de connexion/inscription
- **Vue** : `resources/views/sign.blade.php`
- **Style** : Utilise une interface divisée avec animation
- **Formulaires** :
  ```php
  <!-- Inscription -->
  <form id="signup-form" action="{{ url('/signup-bdd') }}" method="POST">
      @csrf
      <input type="text" name="nom" placeholder="Nom" required />
      <!-- ... -->
  </form>

  <!-- Connexion -->
  <form id="signin-form" action="{{ url('/signin-bdd') }}" method="POST">
      @csrf
      <input type="email" name="email" placeholder="Email" required />
      <!-- ... -->
  </form>
  ```

<!-- ////////////////////////////////
////////////////////////////////
//////////////////////////////// -->

### Exemple 6: Système de Notifications
**Tâche** : Personnaliser les notifications utilisateur
- **Fichier** : `resources/views/layouts/home.blade.php`
- **Ligne** : 59
- **Fonction** :
  ```javascript
  function showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `notification-item mb-4 ${
          type === 'success' ? 'bg-green-100 border-green-500' : 'bg-red-100 border-red-500'
      }`;
      // ...
  }
  ```

<!-- ////////////////////////////////
////////////////////////////////
//////////////////////////////// -->