@extends('layouts.home')

@section('title', 'Fast Sushi - Contact')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 text-[#FFFCF2]">Contactez-nous</h1>
            <p class="text-[#CCC5B9] max-w-2xl mx-auto">
                Des questions, des suggestions ou besoin d'aide ? Nous sommes là pour vous. 
                Complétez le formulaire ci-dessous et notre équipe vous répondra dans les plus brefs délais.
            </p>
        </div>
        
        <!-- Contact Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Contact Form -->
            <div class="md:col-span-2 bg-[#252422] rounded-lg p-6 shadow-lg border-t border-[#D90429]">
                <h2 class="text-2xl font-semibold mb-6 text-[#FFFCF2]">Envoyez-nous un message</h2>
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-[#CCC5B9] mb-2">Nom</label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-2 bg-[#403D39] text-[#FFFCF2] border border-[#403D39] rounded-lg focus:outline-none focus:border-[#D90429]">
                        </div>
                        <div>
                            <label for="email" class="block text-[#CCC5B9] mb-2">Email</label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-2 bg-[#403D39] text-[#FFFCF2] border border-[#403D39] rounded-lg focus:outline-none focus:border-[#D90429]">
                        </div>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-[#CCC5B9] mb-2">Sujet</label>
                        <input type="text" id="subject" name="subject" required
                            class="w-full px-4 py-2 bg-[#403D39] text-[#FFFCF2] border border-[#403D39] rounded-lg focus:outline-none focus:border-[#D90429]">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-[#CCC5B9] mb-2">Message</label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full px-4 py-2 bg-[#403D39] text-[#FFFCF2] border border-[#403D39] rounded-lg focus:outline-none focus:border-[#D90429]"></textarea>
                    </div>
                    
                    <button type="submit" 
                        class="bg-[#D90429] hover:bg-[#ce0006] text-white px-6 py-3 rounded-lg transition duration-300 font-semibold flex items-center">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Envoyer
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="bg-[#252422] rounded-lg p-6 shadow-lg border-t border-[#D90429] flex flex-col">
                <h2 class="text-2xl font-semibold mb-6 text-[#FFFCF2]">Informations</h2>
                
                <div class="space-y-6 flex-grow">
                    <div>
                        <h3 class="text-[#FFFCF2] font-medium mb-2 flex items-center">
                            <i class="fa-solid fa-envelope text-[#D90429] mr-3"></i> Email
                        </h3>
                        <p class="text-[#CCC5B9] ml-8">contact@fastsushi.com</p>
                    </div>
                    
                    <div>
                        <h3 class="text-[#FFFCF2] font-medium mb-2 flex items-center">
                            <i class="fa-solid fa-clock text-[#D90429] mr-3"></i> Horaires
                        </h3>
                        <div class="text-[#CCC5B9] ml-8">
                            <p>Lundi - Vendredi: 11h - 22h</p>
                            <p>Samedi - Dimanche: 12h - 23h</p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-[#FFFCF2] font-medium mb-2 flex items-center">
                            <i class="fa-solid fa-share-nodes text-[#D90429] mr-3"></i> Suivez-nous
                        </h3>
                        <div class="flex space-x-4 ml-8">
                            <a href="#" class="text-[#CCC5B9] hover:text-[#FFFCF2] transition duration-300">
                                <i class="fa-brands fa-instagram fa-lg"></i>
                            </a>
                            <a href="#" class="text-[#CCC5B9] hover:text-[#FFFCF2] transition duration-300">
                                <i class="fa-brands fa-facebook fa-lg"></i>
                            </a>
                            <a href="#" class="text-[#CCC5B9] hover:text-[#FFFCF2] transition duration-300">
                                <i class="fa-brands fa-tiktok fa-lg"></i>
                            </a>
                            <a href="#" class="text-[#CCC5B9] hover:text-[#FFFCF2] transition duration-300">
                                <i class="fa-brands fa-twitter fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-[#403D39]">
                    <h3 class="text-[#FFFCF2] font-medium mb-4">Besoin d'une réponse rapide?</h3>
                    <a href="tel:+33123456789" class="bg-[#403D39] hover:bg-[#D90429] text-white px-4 py-3 rounded-lg transition duration-300 font-medium flex items-center justify-center">
                        <i class="fa-solid fa-phone mr-2"></i> Appelez-nous
                    </a>
                </div>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="bg-[#252422] rounded-lg p-6 shadow-lg border-t border-[#D90429] mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-[#FFFCF2]">Questions fréquentes</h2>
            
            <div class="space-y-4">
                <div class="border-b border-[#403D39] pb-4">
                    <h3 class="text-[#FFFCF2] font-medium mb-2">Comment puis-je suivre ma commande ?</h3>
                    <p class="text-[#CCC5B9]">Une fois votre commande passée, vous recevrez un email de confirmation avec un lien de suivi. Vous pouvez également suivre votre commande depuis votre compte dans la section "Mes commandes".</p>
                </div>
                
                <div class="border-b border-[#403D39] pb-4">
                    <h3 class="text-[#FFFCF2] font-medium mb-2">Quels sont les délais de livraison ?</h3>
                    <p class="text-[#CCC5B9]">Nos délais de livraison varient entre 30 et 45 minutes selon votre localisation et l'affluence. Un délai estimé vous sera communiqué lors de la validation de votre commande.</p>
                </div>
                
                <div class="border-b border-[#403D39] pb-4">
                    <h3 class="text-[#FFFCF2] font-medium mb-2">Comment puis-je annuler ma commande ?</h3>
                    <p class="text-[#CCC5B9]">Vous pouvez annuler votre commande dans les 5 minutes suivant sa validation en contactant notre service client. Au-delà de ce délai, la commande est en préparation et ne peut plus être annulée.</p>
                </div>
                
                <div>
                    <h3 class="text-[#FFFCF2] font-medium mb-2">Proposez-vous des options végétariennes ?</h3>
                    <p class="text-[#CCC5B9]">Oui, nous proposons une sélection de sushis et de plats végétariens. Consultez la section "Végétarien" de notre menu pour découvrir toutes nos options.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
