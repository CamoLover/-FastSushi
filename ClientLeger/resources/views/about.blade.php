@extends('layouts.home')

@section('title', 'Fast Sushi - À propos')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 text-[#FFFCF2]">À propos de Fast Sushi</h1>
            <p class="text-[#CCC5B9] max-w-2xl mx-auto">
                Découvrez notre histoire, notre passion pour les sushis et notre engagement envers la qualité.
            </p>
        </div>
        
        <!-- Notre Histoire Section -->
        <div class="bg-[#252422] rounded-lg p-8 shadow-lg border-t border-[#D90429] mb-12">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-semibold mb-4 text-[#FFFCF2]">Notre Histoire</h2>
                    <div class="space-y-4 text-[#CCC5B9]">
                        <p>
                            Fast Sushi est né en 2018 d'une passion partagée pour la cuisine japonaise et d'une vision simple : 
                            rendre accessible la saveur authentique des sushis, sans compromis sur la qualité ni sur la rapidité.
                        </p>
                        <p>
                            Fondé par deux amis passionnés de gastronomie asiatique, notre restaurant a commencé comme un petit comptoir 
                            avant de se développer pour devenir l'enseigne que vous connaissez aujourd'hui.
                        </p>
                        <p>
                            Notre concept combine les techniques traditionnelles japonaises avec une approche moderne 
                            de la restauration rapide, offrant ainsi une expérience culinaire unique.
                        </p>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="rounded-lg overflow-hidden shadow-xl">
                        <img src="{{ asset('media/about/restaurant.jpg') }}" alt="Notre restaurant" class="w-full h-auto object-cover" 
                             onerror="this.src='https://images.unsplash.com/photo-1617196034183-421b4917c92d?q=80&w=1000&auto=format&fit=crop';this.onerror=null;">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Nos Valeurs Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-[#252422] rounded-lg p-6 shadow-lg border-t border-[#D90429] flex flex-col">
                <div class="text-[#D90429] text-4xl mb-4 flex justify-center">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-[#FFFCF2] text-center">Fraîcheur</h3>
                <p class="text-[#CCC5B9] text-center flex-grow">
                    Nous sélectionnons rigoureusement nos ingrédients chaque matin pour vous garantir une fraîcheur incomparable.
                    Nos poissons sont livrés quotidiennement par des fournisseurs de confiance.
                </p>
            </div>
            
            <div class="bg-[#252422] rounded-lg p-6 shadow-lg border-t border-[#D90429] flex flex-col">
                <div class="text-[#D90429] text-4xl mb-4 flex justify-center">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-[#FFFCF2] text-center">Savoir-faire</h3>
                <p class="text-[#CCC5B9] text-center flex-grow">
                    Nos chefs sont formés aux techniques traditionnelles japonaises. Chaque sushi est préparé à la commande avec précision et dévouement.
                </p>
            </div>
            
            <div class="bg-[#252422] rounded-lg p-6 shadow-lg border-t border-[#D90429] flex flex-col">
                <div class="text-[#D90429] text-4xl mb-4 flex justify-center">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-[#FFFCF2] text-center">Rapidité</h3>
                <p class="text-[#CCC5B9] text-center flex-grow">
                    Nous avons optimisé notre processus de préparation et de livraison pour vous offrir des sushis d'exception en un temps record.
                </p>
            </div>
        </div>
        
        <!-- Notre Équipe Section -->
        <div class="bg-[#252422] rounded-lg p-8 shadow-lg border-t border-[#D90429] mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-[#FFFCF2] text-center">Notre Équipe</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Chef 1 -->
                <div class="text-center">
                    <div class="rounded-full overflow-hidden h-32 w-32 mx-auto mb-4 border-2 border-[#D90429]">
                        <img src="{{ asset('media/about/chef1.jpg') }}" alt="Chef Takashi" class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1607631568211-33798ab7f929?q=80&w=1000&auto=format&fit=crop';this.onerror=null;">
                    </div>
                    <h3 class="text-lg font-semibold text-[#FFFCF2]">Chef Takashi</h3>
                    <p class="text-[#D90429] text-sm mb-2">Chef Exécutif</p>
                    <p class="text-[#CCC5B9] text-sm">
                        Formé à Tokyo, 15 ans d'expérience dans la préparation de sushis traditionnels.
                    </p>
                </div>
                
                <!-- Chef 2 -->
                <div class="text-center">
                    <div class="rounded-full overflow-hidden h-32 w-32 mx-auto mb-4 border-2 border-[#D90429]">
                        <img src="{{ asset('media/about/chef2.jpg') }}" alt="Chef Sophie" class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1583394293214-28ded15ee548?q=80&w=1000&auto=format&fit=crop';this.onerror=null;">
                    </div>
                    <h3 class="text-lg font-semibold text-[#FFFCF2]">Chef Sophie</h3>
                    <p class="text-[#D90429] text-sm mb-2">Chef Créative</p>
                    <p class="text-[#CCC5B9] text-sm">
                        Spécialiste des fusions culinaires et créatrice de nos rouleaux signature.
                    </p>
                </div>
                
                <!-- Chef 3 -->
                <div class="text-center">
                    <div class="rounded-full overflow-hidden h-32 w-32 mx-auto mb-4 border-2 border-[#D90429]">
                        <img src="{{ asset('media/about/chef3.jpg') }}" alt="Chef Alexandre" class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1583394838336-acd977736f90?q=80&w=1000&auto=format&fit=crop';this.onerror=null;">
                    </div>
                    <h3 class="text-lg font-semibold text-[#FFFCF2]">Chef Alexandre</h3>
                    <p class="text-[#D90429] text-sm mb-2">Chef Pâtissier</p>
                    <p class="text-[#CCC5B9] text-sm">
                        Expert en desserts japonais et créations sucrées inspirées de l'Asie.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Nos Engagements Section -->
        <div class="bg-[#252422] rounded-lg p-8 shadow-lg border-t border-[#D90429] mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-[#FFFCF2]">Nos Engagements</h2>
            
            <div class="space-y-6">
                <div class="flex items-start">
                    <div class="text-[#D90429] text-xl mr-4 mt-1">
                        <i class="fa-solid fa-fish"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-[#FFFCF2] mb-2">Pêche Responsable</h3>
                        <p class="text-[#CCC5B9]">
                            Nous travaillons exclusivement avec des fournisseurs engagés dans une pêche durable et responsable.
                            Toutes nos espèces de poissons sont sélectionnées en fonction de la saisonnalité et des stocks disponibles.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="text-[#D90429] text-xl mr-4 mt-1">
                        <i class="fa-solid fa-recycle"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-[#FFFCF2] mb-2">Emballages Écologiques</h3>
                        <p class="text-[#CCC5B9]">
                            Tous nos emballages sont biodégradables ou recyclables. Nous avons supprimé le plastique à usage unique
                            de nos opérations et privilégions les matériaux compostables.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="text-[#D90429] text-xl mr-4 mt-1">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-[#FFFCF2] mb-2">Partenaires Locaux</h3>
                        <p class="text-[#CCC5B9]">
                            Nous collaborons avec des agriculteurs locaux pour nos légumes et ingrédients frais,
                            soutenant ainsi l'économie locale et réduisant notre empreinte carbone.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div class="bg-[#D90429] rounded-lg p-8 shadow-lg text-center mb-12">
            <h2 class="text-2xl font-bold mb-4 text-white">Prêt à découvrir l'expérience Fast Sushi ?</h2>
            <p class="text-white mb-6">Commandez en ligne ou visitez notre restaurant pour déguster nos créations</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ url('/menu') }}" class="bg-white text-[#D90429] px-6 py-3 rounded-lg font-semibold hover:bg-[#FFFCF2] transition duration-300">
                    Commander en ligne
                </a>
                <a href="{{ url('/contact') }}" class="bg-[#252422] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#403D39] transition duration-300">
                    Nous contacter
                </a>
            </div>
        </div>
    </div>
@endsection
