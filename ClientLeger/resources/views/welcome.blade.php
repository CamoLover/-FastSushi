@extends('layouts.home')

@section('title', 'Fast Sushi - Accueil')

@push('styles')
<style>
    .main-class{
        top: -5rem;
        position: relative;
    }
    .hero-video-container {
        height: 100vh;
        width: 100%;
        overflow: hidden;
        position: relative;
    }
    
    .hero-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 0 1rem;
    }
    
    .chevron-down {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-size: 2rem;
        animation: bounce 2s infinite;
        cursor: pointer;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0) translateX(-50%);
        }
        40% {
            transform: translateY(-20px) translateX(-50%);
        }
        60% {
            transform: translateY(-10px) translateX(-50%);
        }
    }
    
    /* Ensure header is above everything else */
    #floatingHeader {
        z-index: 999999 !important;
    }
    
    /* Make scrolling smooth */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush

@section('content')
    <!-- Hero Video Section -->
    <div class="hero-video-container">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="{{asset('media/homevideo.mp4')}}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
        <div class="hero-overlay">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 tracking-tight">Fast Sushi</h1>
            <p class="text-xl md:text-2xl text-white mb-10 max-w-2xl">L'authenticité japonaise livrée en un temps record</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#menu" class="bg-[#D90429] hover:bg-[#ce0006] text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-300 transform hover:scale-105">
                    Découvrir le menu
                </a>
                <a href="/panier" class="bg-white hover:bg-gray-100 text-[#D90429] px-8 py-3 rounded-lg font-bold text-lg transition duration-300 transform hover:scale-105">
                    Commander maintenant
                </a>
            </div>
        </div>
        
        <a href="#intro" class="chevron-down">
            <i class="fa-solid fa-chevron-down"></i>
        </a>
    </div>
    
    <!-- Intro Section -->
    <div id="intro" class="bg-[#252422] py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-8 text-[#FFFCF2]">Bienvenue chez Fast Sushi</h2>
                <p class="text-xl text-[#CCC5B9] mb-12 leading-relaxed">
                    Chez Fast Sushi, nous combinons la tradition culinaire japonaise avec une efficacité moderne.
                    Nos chefs qualifiés préparent chaque pièce avec passion et précision, utilisant uniquement
                    les ingrédients les plus frais pour vous offrir une expérience gustative exceptionnelle.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-[#D90429] flex items-center justify-center mb-4">
                            <i class="fa-solid fa-fish fa-2x text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">Fraîcheur Garantie</h3>
                        <p class="text-[#CCC5B9]">Des ingrédients sélectionnés quotidiennement pour une qualité inégalée.</p>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-[#D90429] flex items-center justify-center mb-4">
                            <i class="fa-solid fa-truck fa-2x text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">Livraison Rapide</h3>
                        <p class="text-[#CCC5B9]">Vos sushis préférés livrés en 30 minutes ou moins.</p>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-[#D90429] flex items-center justify-center mb-4">
                            <i class="fa-solid fa-utensils fa-2x text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">Chefs Experts</h3>
                        <p class="text-[#CCC5B9]">Des sushis préparés par des chefs formés aux techniques traditionnelles.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menu Preview Section -->
    <div id="menu" class="py-16 bg-[#403D39]">
        <div class="container mx-auto px-4 mb-16">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-[#FFFCF2]">Notre Menu</h2>
                <p class="text-xl text-[#CCC5B9] max-w-3xl mx-auto">
                    Découvrez notre sélection de sushis, sashimis, makis et autres spécialités japonaises, 
                    préparés quotidiennement avec les ingrédients les plus frais.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-[#252422] rounded-lg overflow-hidden shadow-lg group hover:shadow-xl transition-all duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1580822184713-fc5400e7fe10?q=80&w=2070&auto=format&fit=crop" 
                             alt="Sushi" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">Sushis</h3>
                        <p class="text-[#CCC5B9] mb-4">Des pièces délicatement préparées sur un lit de riz vinaigré.</p>
                        <a href="#" class="text-[#D90429] font-semibold hover:text-[#ce0006] transition-colors flex items-center">
                            Voir la sélection <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <div class="bg-[#252422] rounded-lg overflow-hidden shadow-lg group hover:shadow-xl transition-all duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?q=80&w=1974&auto=format&fit=crop" 
                             alt="Makis" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">Makis</h3>
                        <p class="text-[#CCC5B9] mb-4">Des rouleaux enveloppés dans une feuille d'algue nori croustillante.</p>
                        <a href="#" class="text-[#D90429] font-semibold hover:text-[#ce0006] transition-colors flex items-center">
                            Voir la sélection <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <div class="bg-[#252422] rounded-lg overflow-hidden shadow-lg group hover:shadow-xl transition-all duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1534482421-64566f976cfa?q=80&w=1969&auto=format&fit=crop" 
                             alt="Sashimis" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">Sashimis</h3>
                        <p class="text-[#CCC5B9] mb-4">De fines tranches de poisson cru d'une fraîcheur exceptionnelle.</p>
                        <a href="#" class="text-[#D90429] font-semibold hover:text-[#ce0006] transition-colors flex items-center">
                            Voir la sélection <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <div class="bg-[#252422] rounded-lg overflow-hidden shadow-lg group hover:shadow-xl transition-all duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?q=80&w=1780&auto=format&fit=crop" 
                             alt="Plats chauds" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">Plats Chauds</h3>
                        <p class="text-[#CCC5B9] mb-4">Des ramens aux gyozas, découvrez nos spécialités chaudes.</p>
                        <a href="#" class="text-[#D90429] font-semibold hover:text-[#ce0006] transition-colors flex items-center">
                            Voir la sélection <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block bg-[#D90429] hover:bg-[#ce0006] text-white px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                    Voir tout le menu
                </a>
            </div>
        </div>
    </div>
    
    <!-- Best Sellers Section (Carousel) -->
    @include('module.carousel')
    
    <!-- App Promo Section -->
    <div class="py-20 bg-[#252422]">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-10">
                <div class="lg:w-1/2">
                    <h2 class="text-4xl font-bold mb-6 text-[#FFFCF2]">Téléchargez notre application</h2>
                    <p class="text-xl text-[#CCC5B9] mb-8 leading-relaxed">
                        Commandez vos sushis préférés en quelques clics, suivez votre livraison en temps réel 
                        et bénéficiez d'offres exclusives avec notre application mobile.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="#" class="flex items-center bg-black text-white rounded-lg px-4 py-3 hover:bg-gray-900 transition duration-300">
                            <i class="fa-brands fa-apple text-3xl mr-3"></i>
                            <div>
                                <div class="text-xs">Télécharger sur l'</div>
                                <div class="text-xl font-semibold">App Store</div>
                            </div>
                        </a>
                        
                        <a href="#" class="flex items-center bg-black text-white rounded-lg px-4 py-3 hover:bg-gray-900 transition duration-300">
                            <i class="fa-brands fa-google-play text-3xl mr-3"></i>
                            <div>
                                <div class="text-xs">Disponible sur</div>
                                <div class="text-xl font-semibold">Google Play</div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div class="flex">
                            <div class="flex -space-x-4">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-10 h-10 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-10 h-10 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/women/58.jpg" class="w-10 h-10 rounded-full border-2 border-white">
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center mb-1">
                                <div class="flex">
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star-half-alt text-yellow-400"></i>
                                </div>
                                <span class="text-[#FFFCF2] ml-2 font-semibold">4.8/5</span>
                            </div>
                            <p class="text-[#CCC5B9] text-sm">Plus de 100 000 téléchargements</p>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2 flex justify-center">
                    <img src="{{asset('media/googleplayslide.png')}}"
                         alt="Application Mobile Fast Sushi" class="max-w-full h-auto lg:max-h-96 object-contain">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials Section -->
    <div class="py-20 bg-[#403D39]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-[#FFFCF2]">Ce que nos clients disent</h2>
                <p class="text-xl text-[#CCC5B9] max-w-3xl mx-auto">
                    Des milliers de clients satisfaits ont partagé leur expérience avec Fast Sushi.
                    Voici quelques-uns de leurs témoignages.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#252422] rounded-lg p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-[#CCC5B9] mb-6">
                        "Les meilleurs sushis que j'ai jamais mangés ! La livraison a été extrêmement rapide et les sushis étaient d'une fraîcheur incomparable. Je recommande vivement !"
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/42.jpg" alt="Sophie D." class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="text-[#FFFCF2] font-semibold">Sophie D.</h3>
                            <p class="text-[#CCC5B9] text-sm">Cliente fidèle depuis 2020</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#252422] rounded-lg p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-[#CCC5B9] mb-6">
                        "Application super intuitive, commande facile à passer et livraison rapide. Les California Rolls sont tout simplement délicieux. Je commande au moins une fois par semaine !"
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Thomas L." class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="text-[#FFFCF2] font-semibold">Thomas L.</h3>
                            <p class="text-[#CCC5B9] text-sm">Client régulier</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#252422] rounded-lg p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <i class="fa-solid fa-star-half-alt text-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-[#CCC5B9] mb-6">
                        "J'ai organisé une soirée avec des amis et j'ai commandé chez Fast Sushi. Tout le monde a adoré ! Le plateau royal est parfait pour un groupe. Service client très réactif."
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/63.jpg" alt="Emma R." class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="text-[#FFFCF2] font-semibold">Emma R.</h3>
                            <p class="text-[#CCC5B9] text-sm">Nouvelle cliente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for the chevron down
        document.querySelector('.chevron-down').addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            window.scrollTo({
                top: target.offsetTop,
                behavior: 'smooth'
            });
        });
    });
</script>
@endpush
