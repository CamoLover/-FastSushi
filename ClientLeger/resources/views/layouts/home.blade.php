<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Fast Sushi')</title>


        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <script src="{{ asset('js/panier.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />

        <!-- Setup CSRF token for all AJAX requests -->
        <script>
            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Add CSRF token to all fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                // Only add for same-origin requests
                if (url && (url.startsWith('/') || url.startsWith(window.location.origin))) {
                    options = options || {};
                    options.headers = options.headers || {};
                    
                    // Don't override if already set
                    if (!options.headers['X-CSRF-TOKEN'] && !options.headers['x-csrf-token']) {
                        options.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    }
                }
                
                return originalFetch(url, options);
            };
        </script>

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-[#403D39] text-[#FFFCF2] min-h-screen flex flex-col">
        <!-- Global notification container -->
        <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

        <script>
            function showNotification(message, type = 'success') {
                const container = document.getElementById('notification-container');
                
                const notification = document.createElement('div');
                notification.className = `notification-item mb-4 ${type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700'} border-l-4 p-4 rounded shadow-lg transform transition-all duration-500 ease-in-out`;
                
                notification.innerHTML = `
                    <div class="flex items-center">
                        <div class="mr-2">
                            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                        </div>
                        <p>${message}</p>
                        <button onclick="this.closest('.notification-item').remove()" class="ml-4 ${type === 'success' ? 'text-green-700 hover:text-green-900' : 'text-red-700 hover:text-red-900'}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                container.appendChild(notification);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Handle session flash messages on page load
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    showNotification("{{ session('success') }}", 'success');
                @endif
                
                @if(session('error'))
                    showNotification("{{ session('error') }}", 'error');
                @endif
            });
        </script>

        @include('module.header')

        <main class="flex-grow main-class">
            @yield('content')
        </main>

        @include('module.footer')

        @stack('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Update cart count from cookie on page load
                const panierCookie = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('panier='));
                
                if (panierCookie) {
                    try {
                        const decodedCookie = decodeURIComponent(panierCookie.split('=')[1]);
                        let cookieData;
                        
                        // Try to parse as JSON
                        try {
                            cookieData = JSON.parse(decodedCookie);
                        } catch (jsonError) {
                            // If parsing fails, it might be an encrypted cookie
                            console.warn('Could not parse cookie as JSON, might be encrypted:', jsonError);
                            
                            // Stop processing this cookie - we'll rely on the server-side count
                            return;
                        }
                        
                        const cartCount = document.getElementById('cart-count');
                        
                        if (cartCount && Array.isArray(cookieData)) {
                            // Calculate total quantity
                            let totalQuantity = 0;
                            cookieData.forEach(item => {
                                totalQuantity += Number(item.quantite || 0);
                            });
                            
                            // Update cart count in header
                            cartCount.textContent = totalQuantity;
                            console.log('Updated cart count from cookie:', totalQuantity);
                        }
                    } catch (e) {
                        console.error('Error processing panier cookie:', e);
                    }
                }
            });
        </script>
    </body>
</html> 