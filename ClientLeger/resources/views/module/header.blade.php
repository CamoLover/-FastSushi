<header id="floatingHeader" class="fixed top-4 left-1/2 transform -translate-x-1/2 w-[90%] bg-[#252422] py-4 px-8 flex justify-between items-center shadow-lg border-b border-[#D90429] rounded-xl transition-transform duration-300">
    <!-- Logo -->
    <div class="text-2xl font-bold tracking-wide transition-transform transform hover:scale-110 text-[#ce0006]">
        <a href="/" class="hover:text-[#ce0006] transition duration-300">Fast Sushi</a>
    </div>

    <!-- Navigation -->
    <nav id="navMenu" class="hidden lg:flex space-x-8 text-lg font-semibold text-[#CCC5B9]">
        <ul class="lg:flex space-x-8 hidden">
            <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300">Accueil</a></li>
            <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300">Menu</a></li>
            <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300">A propos</a></li>
            <li><a href="#" class="hover:text-[#FFFCF2] transition duration-300">Contact</a></li>
        </ul>
    </nav>

    <!-- Profile Section -->
    <div class="relative">
        <button id="profileButton" onclick="toggleMenu()" class="w-10 h-10 rounded-full bg-[#403D39] flex items-center justify-center border-2 border-[#D90d29] hover:scale-110 transition-transform">
            <i class="fa-solid fa-user fa-lg" style="color: #CCC5B9;"></i>
        </button>

        <!-- Context Menu -->
        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-40 bg-[#252422] text-[#FFFCF2] rounded-lg shadow-lg overflow-hidden">
            <a href="#" class="block px-4 py-2 hover:bg-[#660708]">Profil</a>
            <a href="#" class="block px-4 py-2 hover:bg-[#660708]">Paramètre</a>
            <a href="#" class="block px-4 py-2 hover:bg-[#660708]">Se Déconnecté</a>
        </div>
    </div>

    <!-- Hamburger Menu Button -->
    <button id="menuToggle" class="lg:hidden text-[#CCC5B9] focus:outline-none">
        <i class="fa-solid fa-bars fa-2x"></i>
    </button>
</header>

<!-- Mobile Menu -->
<div id="mobileMenu" class="fixed top-0 left-0 w-full h-full bg-[#252422] flex flex-col items-center pt-20 space-y-6 text-[#CCC5B9] text-2xl font-semibold z-50 transform translate-x-full transition-transform duration-300">
    <a href="#" class="hover:text-[#FFFCF2] transition duration-300">Accueil</a>
    <a href="#" class="hover:text-[#FFFCF2] transition duration-300">Menu</a>
    <a href="#" class="hover:text-[#FFFCF2] transition duration-300">A propos</a>
    <a href="#" class="hover:text-[#FFFCF2] transition duration-300">Contact</a>
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
        if (!event.target.closest("#profileButton") && !event.target.closest("#profileMenu")) {
            document.getElementById("profileMenu").classList.add("hidden");
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
<!-- Extra padding so content doesn’t go under the header -->
<div class="h-20"></div>
