<footer class="bg-[#252422] text-[#CCC5B9] py-10 border-t border-[#D90429]">
    <div class="container mx-auto px-4">
        <!-- Top Section with Logo and Description -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div class="text-center md:text-left mb-6 md:mb-0">
                <div class="text-2xl font-bold tracking-wide text-[#ce0006] mb-3">Fast Sushi</div>
                <p class="max-w-md text-sm leading-relaxed">
                    Chez FastSushi, chaque bouchée est une invitation au voyage.
                    Entre tradition et créativité, nos sushis sont préparés avec passion
                    et des ingrédients d'exception. Installez-vous, savourez, et laissez la magie opérer.
                </p>
            </div>
            
            <!-- Newsletter Section -->
            <div class="w-full md:w-auto">
                <div class="bg-[#403D39] rounded-lg p-5 max-w-md mx-auto md:mx-0">
                    <h3 class="text-[#FFFCF2] font-semibold mb-3">Restez informé</h3>
                    <div class="flex">
                        <input type="email" placeholder="Votre email" class="bg-[#252422] text-[#FFFCF2] px-4 py-2 rounded-l-lg flex-grow border border-[#403D39] focus:outline-none focus:border-[#D90429]">
                        <button class="bg-[#D90429] hover:bg-[#ce0006] text-white px-4 py-2 rounded-r-lg transition duration-300">
                            S'inscrire
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Middle Section with Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Navigation Links -->
            <div class="bg-[#2a2926] rounded-lg p-5 shadow-md">
                <h3 class="text-[#FFFCF2] font-semibold mb-3 border-b border-[#D90429] pb-2">Navigation</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="hover:text-[#FFFCF2] transition duration-300 flex items-center"><i class="fa-solid fa-home mr-2"></i> Accueil</a></li>
                    <li><a href="/panier" class="hover:text-[#FFFCF2] transition duration-300 flex items-center"><i class="fa-solid fa-shopping-cart mr-2"></i> Panier</a></li>
                    <li><a href="/menu" class="hover:text-[#FFFCF2] transition duration-300 flex items-center"><i class="fa-solid fa-utensils mr-2"></i> Menu</a></li>
                </ul>
            </div>

            <!-- Legal Links -->
            <div class="bg-[#2a2926] rounded-lg p-5 shadow-md">
                <h3 class="text-[#FFFCF2] font-semibold mb-3 border-b border-[#D90429] pb-2">Informations légales</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300 flex items-center"><i class="fa-solid fa-file-contract mr-2"></i> Conditions générales</a></li>
                    <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300 flex items-center"><i class="fa-solid fa-shield-alt mr-2"></i> Politique de confidentialité</a></li>
                    <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300 flex items-center"><i class="fa-solid fa-cookie mr-2"></i> Gestion des cookies</a></li>
                    <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300 flex items-center"><i class="fa-solid fa-scale-balanced mr-2"></i> Mentions légales</a></li>
                </ul>
            </div>

            <!-- Social Media Links -->
            <div class="bg-[#2a2926] rounded-lg p-5 shadow-md">
                <h3 class="text-[#FFFCF2] font-semibold mb-3 border-b border-[#D90429] pb-2">Suivez-nous</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="hover:text-[#FFFCF2] transition duration-300 bg-[#403D39] hover:bg-[#D90429] p-3 rounded-lg flex flex-col items-center">
                        <i class="fa-brands fa-instagram fa-lg mb-1"></i>
                    </a>
                    <a href="#" class="hover:text-[#FFFCF2] transition duration-300 bg-[#403D39] hover:bg-[#D90429] p-3 rounded-lg flex flex-col items-center">
                        <i class="fa-brands fa-facebook fa-lg mb-1"></i>
                    </a>
                    <a href="#" class="hover:text-[#FFFCF2] transition duration-300 bg-[#403D39] hover:bg-[#D90429] p-3 rounded-lg flex flex-col items-center">
                        <i class="fa-brands fa-tiktok fa-lg mb-1"></i>
                    </a>
                    <a href="#" class="hover:text-[#FFFCF2] transition duration-300 bg-[#403D39] hover:bg-[#D90429] p-3 rounded-lg flex flex-col items-center">
                        <i class="fa-brands fa-twitter fa-lg mb-1"></i>
                    </a>
                </div>
                
                <div class="mt-4">
                    <h4 class="text-[#FFFCF2] text-sm mb-2">Contact</h4>
                    <p class="text-sm flex items-center"><i class="fa-solid fa-phone mr-2"></i> +33 1 23 45 67 89</p>
                    <p class="text-sm flex items-center"><i class="fa-solid fa-envelope mr-2"></i> contact@fastsushi.com</p>
                </div>
            </div>
        </div>

    </div>
    <!-- <div class="w-full mt-[-5px] mb-[-20px]">
        <img src="{{asset('media/FS_Footer.png')}}" alt="" class="w-full">
    </div> -->
</footer>
