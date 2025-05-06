@extends('layouts.home')

@section('title', 'Fast Sushi - 404')

@push('styles')
<style>
    .error-container {
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #FFFCF2;
        padding: 2rem;
    }
    
    .error-code {
        font-size: 8rem;
        font-weight: bold;
        color: #D90429;
        margin-bottom: 1rem;
        position: relative;
        z-index: -1;
    }
    
    .satellite {
        position: absolute;
        top: -30px;
        left: 0;
        font-size: 2rem;
        animation: flyingSatellite 8s infinite linear;
        transform-origin: center;
        z-index: -1;
    }
    
    @keyframes flyingSatellite {
        0% {
            transform: translateX(-50px) rotate(0deg);
        }
        25% {
            transform: translateX(50px) rotate(90deg);
        }
        50% {
            transform: translateX(150px) rotate(180deg);
        }
        75% {
            transform: translateX(50px) rotate(270deg);
        }
        100% {
            transform: translateX(-50px) rotate(360deg);
        }
    }
    
    .error-message {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .error-description {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        max-width: 600px;
        text-align: center;
        color: #CCC5B9;
    }
</style>
@endpush

@section('content')
    <div class="error-container">
        <div class="error-code">
            <span class="satellite">🛰️</span>
            404
        </div>
        <h1 class="error-message">Oups ! Page introuvable</h1>
        <p class="error-description">
            Notre satellite a cherché partout, mais impossible de trouver cette page.
            Elle a peut-être été déplacée ou supprimée.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="/" class="bg-[#D90429] hover:bg-[#ce0006] text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-300 transform hover:scale-105">
                Retour à l'accueil
            </a>
            <a href="/menu" class="bg-white hover:bg-gray-100 text-[#D90429] px-8 py-3 rounded-lg font-bold text-lg transition duration-300 transform hover:scale-105">
                Voir notre menu
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation supplémentaire pour la page 404
        const errorCode = document.querySelector('.error-code');
        errorCode.addEventListener('mouseover', function() {
            const satellite = document.querySelector('.satellite');
            satellite.style.animationDuration = '2s';
            
            setTimeout(() => {
                satellite.style.animationDuration = '8s';
            }, 2000);
        });
    });
</script>
@endpush

