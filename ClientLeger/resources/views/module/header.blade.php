<header id="floatingHeader" class="fixed top-4 left-1/2 transform -translate-x-1/2 w-full max-w-6xl mx-auto px-4 bg-[#252422] py-4 flex justify-between items-center shadow-lg border-b border-[#D90429] rounded-xl transition-transform duration-300">
    <!-- Logo -->
    <div class="text-2xl font-bold tracking-wide transition-transform transform hover:scale-110 text-[#ce0006]">
        <a href="/" class="hover:text-[#ce0006] transition duration-300 flex items-center">
            <img src="{{asset('media/fastsushi.png')}}" alt="Fast Sushi Logo" class="w-10 h-10 mr-2">
            <span>Fast Sushi</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav id="navMenu" class="hidden lg:flex justify-center flex-1 text-lg font-semibold text-[#CCC5B9]">
        <ul class="flex space-x-8">
            <li><a href="/" class="hover:text-[#FFFCF2] transition duration-300">Accueil</a></li>
            <li><a href="{{ url('/menu') }}" class="hover:text-[#FFFCF2] transition duration-300">Menu</a></li>
            <li><a href="{{ url('/about') }}" class="hover:text-[#FFFCF2] transition duration-300">À propos</a></li>
            <li><a href="{{ url('/contact') }}" class="hover:text-[#FFFCF2] transition duration-300">Contact</a></li>
        </ul>
    </nav>

    <!-- Right Section -->
    <div class="flex items-center space-x-4">
        <!-- Cart Icon -->
        <div class="relative">
            <a href="{{ url('/panier') }}" class="relative flex items-center justify-center text-[#CCC5B9] hover:text-[#FFFCF2] transition-all hover:scale-110">
                <i class="fa-solid fa-shopping-cart fa-lg"></i>
                <span id="cart-count" class="absolute -top-2 -right-2 bg-[#D90429] text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                    @php
                        $totalItems = 0;
                        if (session('client')) {
                            // Get cart for authenticated user
                            $panier = \App\Models\Panier::where('id_client', session('client')->id_client)->first();
                            if ($panier) {
                                $lignes = \App\Models\Panier_ligne::where('id_panier', $panier->id_panier)->get();
                                $totalItems = $lignes->sum('quantite');
                            }
                        } else {
                            // Try to get cart from cookie
                            $panierCookie = request()->cookie('panier');
                            if (!empty($panierCookie)) {
                                try {
                                    $cookieCart = json_decode($panierCookie, true);
                                    if (is_array($cookieCart)) {
                                        foreach ($cookieCart as $item) {
                                            $totalItems += $item['quantite'] ?? 0;
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Error parsing cookie, keep total at 0
                                }
                            }
                        }
                    @endphp
                    {{ $totalItems }}
                </span>
            </a>
        </div>

        @if(Session::has('client'))
        <!-- Profile Section for logged in users -->
        <div class="relative">
            <button id="profileButton" onclick="toggleMenu()" class="w-10 h-10 rounded-full bg-[#403D39] flex items-center justify-center border-2 border-[#D90d29] hover:scale-110 transition-transform">
                <i class="fa-solid fa-user fa-lg" style="color: #CCC5B9;"></i>
            </button>

            <!-- Context Menu -->
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-40 bg-[#252422] text-[#FFFCF2] rounded-lg shadow-lg overflow-hidden">
                <div class="block px-4 py-2 text-sm border-b border-[#660708]">
                    Bonjour, {{ Session::get('client')->prenom }}
                </div>
                <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-[#660708]">Profil</a>
                <a href="#" class="block px-4 py-2 hover:bg-[#660708]">Paramètre</a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-[#660708]">Se Déconnecter</button>
                </form>
            </div>
        </div>
        @else
        <!-- Login/Signup Button for guests -->
        <a href="/sign" class="inline-flex items-center px-4 py-2 bg-[#D90429] text-white font-medium rounded-lg hover:bg-[#660708] transition-colors duration-300">
            <i class="fa-solid fa-sign-in-alt mr-2"></i> Connexion
        </a>
        @endif

        <!-- Hamburger Menu Button -->
        <button id="menuToggle" class="lg:hidden text-[#CCC5B9] focus:outline-none">
            <i class="fa-solid fa-bars fa-2x"></i>
        </button>
    </div>
</header>

<!-- Mobile Menu -->
<div id="mobileMenu" class="fixed top-0 left-0 w-full h-full bg-[#252422] flex flex-col items-center pt-20 space-y-6 text-[#CCC5B9] text-2xl font-semibold z-50 transform translate-x-full transition-transform duration-300">
    <a href="/" class="hover:text-[#FFFCF2] transition duration-300">Accueil</a>
    <a href="#" class="hover:text-[#FFFCF2] transition duration-300">Menu</a>
    <a href="{{ url('/about') }}" class="hover:text-[#FFFCF2] transition duration-300">À propos</a>
    <a href="{{ url('/contact') }}" class="hover:text-[#FFFCF2] transition duration-300">Contact</a>
    
    <!-- Cart Link for Mobile -->
    <a href="/panier" class="hover:text-[#FFFCF2] transition duration-300 flex items-center">
        <i class="fa-solid fa-shopping-cart mr-2"></i> Panier
        <span class="ml-2 bg-[#D90429] text-white text-sm font-bold rounded-full h-5 w-5 flex items-center justify-center">
            {{ $totalItems }}
        </span>
    </a>
    
    @if(!Session::has('client'))
    <a href="/sign" class="hover:text-[#FFFCF2] transition duration-300 flex items-center">
        <i class="fa-solid fa-sign-in-alt mr-2"></i> Connexion / S'inscrire
    </a>
    @endif
    
    <button id="closeMenu" class="text-[#FFFCF2] mt-10"><i class="fa-solid fa-times fa-2x"></i></button>
</div>

<script>
    document.getElementById('menuToggle').addEventListener('click', function () {
        document.getElementById('mobileMenu').classList.toggle('translate-x-full');
    });
    document.getElementById('closeMenu').addEventListener('click', function () {
        document.getElementById('mobileMenu').classList.add('translate-x-full');
    });

    function toggleMenu() {
        document.getElementById("profileMenu").classList.toggle("hidden");
    }

    function closeMenu(event) {
        const profileMenu = document.getElementById("profileMenu");
        if (profileMenu && !event.target.closest("#profileButton") && !event.target.closest("#profileMenu")) {
            profileMenu.classList.add("hidden");
        }
    }

    document.addEventListener("click", closeMenu);

    // Header Scroll Effect
    window.addEventListener("scroll", function () {
        const header = document.getElementById("floatingHeader");
        if (window.scrollY > 10) {
            header.classList.add("translate-y-[-10px]", "shadow-xl");
        } else {
            header.classList.remove("translate-y-[-10px]", "shadow-xl");
        }
    });
</script>
<!-- Extra padding so content doesn't go under the header -->
<div class="h-20"></div>
