<!-- Best Sellers Carousel -->
<div class="w-full relative bg-[#252422] py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-center mb-10">
            <div class="h-0.5 w-12 bg-[#D90429] mr-4"></div>
            <h2 class="text-3xl md:text-4xl font-bold text-center text-[#FFFCF2]">Nos Best-Sellers</h2>
            <div class="h-0.5 w-12 bg-[#D90429] ml-4"></div>
        </div>
        
        <!-- Carousel Container -->
        <div id="bestsellers-carousel" class="carousel-container relative overflow-hidden">
            <!-- Slides Container -->
            <div class="carousel-slides flex transition-all duration-700 ease-in-out">
                <!-- Slide 1 - Best Sellers -->
                <div class="carousel-slide w-full flex-shrink-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="best-sellers-container">
                        <!-- Best sellers will be dynamically inserted here -->
                    </div>
                </div>
                
                <!-- Slide 2 - Latest Products -->
                <div class="carousel-slide w-full flex-shrink-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="latest-products-container">
                        <!-- Latest products will be dynamically inserted here -->
                    </div>
                </div>
            </div>
            
            <!-- Navigation Arrows -->
            <button id="prevSlide" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-[#D90429]/80 hover:bg-[#D90429] text-white w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center shadow-lg z-10 focus:outline-none transition duration-300">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            
            <button id="nextSlide" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-[#D90429]/80 hover:bg-[#D90429] text-white w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center shadow-lg z-10 focus:outline-none transition duration-300">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            
            <!-- Slide Indicators -->
            <div class="flex justify-center mt-8">
                <div class="carousel-indicators flex space-x-2">
                    <button class="carousel-indicator w-8 h-2 md:w-10 md:h-2 rounded-full bg-[#FFFCF2] mx-1 transition-all duration-300 active" data-slide="0"></button>
                    <button class="carousel-indicator w-2 h-2 rounded-full bg-[#FFFCF2] opacity-50 mx-1 transition-all duration-300" data-slide="1"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variable declarations
        let autoSlideTimer = null;
        let isAutoslidePaused = false;
        
        // Function to start auto slide
        function startAutoSlide() {
            // Clear any existing timer first to prevent multiple timers
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
            }
            
            autoSlideTimer = setInterval(() => {
                if (!isTransitioning && !isDragging) {
                    goToSlide(currentSlide + 1);
                }
            }, 5000); // Change slide every 5 seconds
        }
        
        // Function to restart auto slide
        function restartAutoSlide() {
            // Make sure to clear the timer first
            clearInterval(autoSlideTimer);
            autoSlideTimer = null;
            
            // Start a new timer after a short delay
            setTimeout(() => {
                startAutoSlide();
            }, 300);
        }
    
        // Fetch carousel products
        fetch('/api/carousel-products')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Remove console debug logging
                
                // Check if the data structure is as expected
                if (!data.bestSellers || !data.latestProducts) {
                    throw new Error('Data structure is not as expected');
                }
                
                // Populate best sellers
                const bestSellersContainer = document.getElementById('best-sellers-container');
                if (data.bestSellers.length === 0) {
                    bestSellersContainer.innerHTML = '<div class="col-span-3 text-center text-[#FFFCF2]">Aucun best-seller disponible pour le moment.</div>';
                } else {
                    data.bestSellers.forEach(product => {
                        bestSellersContainer.innerHTML += `
                            <div class="bg-[#403D39] rounded-lg overflow-hidden shadow-lg group hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 flex flex-col h-full">
                                <div class="relative h-64 overflow-hidden">
                                    <img src="/media/${product.photo}" alt="${product.nom}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='/media/concombre.png'">
                                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-black/0 to-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    <div class="absolute top-3 right-3 bg-[#D90429] text-white rounded-full px-3 py-1 text-sm font-bold shadow-lg">
                                        Best-seller
                                    </div>
                                </div>
                                <div class="p-5 flex-grow flex flex-col">
                                    <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">${product.nom}</h3>
                                    <div class="h-12 mb-4 overflow-hidden">
                                        <p class="text-[#CCC5B9] line-clamp-2">${product.description || 'Délicieux plat de notre menu'}</p>
                                    </div>
                                    <div class="flex justify-between items-center mt-auto">
                                        <span class="text-[#FFFCF2] font-bold text-lg">${parseFloat(product.prix_ttc).toFixed(2)} €</span>
                                        <button class="bg-[#D90429] hover:bg-[#ce0006] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center add-to-cart-btn"
                                                data-id="${product.id_produit}"
                                                data-name="${product.nom}"
                                                data-price-ht="${product.prix_ht || (product.prix_ttc ? (product.prix_ttc / 1.1).toFixed(4) : 0)}"
                                                data-price-ttc="${product.prix_ttc || 0}">
                                            <i class="fa-solid fa-cart-plus mr-2"></i> Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }

                // Populate latest products
                const latestProductsContainer = document.getElementById('latest-products-container');
                if (data.latestProducts.length === 0) {
                    latestProductsContainer.innerHTML = '<div class="col-span-3 text-center text-[#FFFCF2]">Aucun nouveau produit disponible pour le moment.</div>';
                } else {
                    data.latestProducts.forEach(product => {
                        latestProductsContainer.innerHTML += `
                            <div class="bg-[#403D39] rounded-lg overflow-hidden shadow-lg group hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 flex flex-col h-full">
                                <div class="relative h-64 overflow-hidden">
                                    <img src="/media/${product.photo}" alt="${product.nom}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='/media/concombre.png'">
                                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-black/0 to-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    <div class="absolute top-3 right-3 bg-[#D90429] text-white rounded-full px-3 py-1 text-sm font-bold shadow-lg">
                                        Nouveauté
                                    </div>
                                </div>
                                <div class="p-5 flex-grow flex flex-col">
                                    <h3 class="text-xl font-semibold mb-2 text-[#FFFCF2]">${product.nom}</h3>
                                    <div class="h-12 mb-4 overflow-hidden">
                                        <p class="text-[#CCC5B9] line-clamp-2">${product.description || 'Délicieux plat de notre menu'}</p>
                                    </div>
                                    <div class="flex justify-between items-center mt-auto">
                                        <span class="text-[#FFFCF2] font-bold text-lg">${parseFloat(product.prix_ttc).toFixed(2)} €</span>
                                        <button class="bg-[#D90429] hover:bg-[#ce0006] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center add-to-cart-btn"
                                                data-id="${product.id_produit}"
                                                data-name="${product.nom}"
                                                data-price-ht="${product.prix_ht || (product.prix_ttc ? (product.prix_ttc / 1.1).toFixed(4) : 0)}"
                                                data-price-ttc="${product.prix_ttc || 0}">
                                            <i class="fa-solid fa-cart-plus mr-2"></i> Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                
                // After both slides have been populated, initialize the carousel
                initializeCarousel();
            })
            .catch(error => {
                console.error('Error fetching carousel products:', error);
                document.getElementById('best-sellers-container').innerHTML = '<div class="col-span-3 text-center text-[#FFFCF2]">Erreur lors du chargement des produits.</div>';
                document.getElementById('latest-products-container').innerHTML = '<div class="col-span-3 text-center text-[#FFFCF2]">Erreur lors du chargement des produits.</div>';
                
                // Even if there's an error, initialize the carousel
                initializeCarousel();
            });

        // Function to initialize carousel once data is loaded
        function initializeCarousel() {
            // Carousel functionality
            const carousel = document.getElementById('bestsellers-carousel');
            const slides = carousel.querySelector('.carousel-slides');
            const prevButton = document.getElementById('prevSlide');
            const nextButton = document.getElementById('nextSlide');
            const indicators = carousel.querySelectorAll('.carousel-indicator');
            const slideItems = carousel.querySelectorAll('.carousel-slide');
            
            let currentSlide = 0;
            const slideCount = slideItems.length;
            let slidesWidth;
            let isDragging = false;
            let startPos = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let animationID = 0;
            let isTransitioning = false;
            
            // Clone first and last slides for infinite effect
            const firstSlideClone = slideItems[0].cloneNode(true);
            const lastSlideClone = slideItems[slideCount - 1].cloneNode(true);
            slides.appendChild(firstSlideClone);
            slides.insertBefore(lastSlideClone, slideItems[0]);
            
            // Initialize carousel dimensions
            function setSlidePosition(transition = true) {
                slidesWidth = carousel.querySelector('.carousel-slide').offsetWidth;
                
                if (!transition) {
                    slides.style.transition = 'none';
                } else {
                    slides.style.transition = 'transform 700ms ease-in-out';
                }
                
                slides.style.transform = `translateX(-${(currentSlide + 1) * slidesWidth}px)`;
                
                // Force reflow
                if (!transition) {
                    void slides.offsetHeight;
                }
            }
            
            // Update indicators appearance
            function updateIndicators() {
                // Make sure we're using the correct index for indicators
                let indicatorIndex = currentSlide;
                
                // Handle edge cases for infinite loop
                if (currentSlide < 0) {
                    indicatorIndex = slideCount - 1;
                } else if (currentSlide >= slideCount) {
                    indicatorIndex = 0;
                }
                
                indicators.forEach((indicator, index) => {
                    if (index === indicatorIndex) {
                        indicator.classList.add('active');
                        indicator.classList.remove('opacity-50');
                        indicator.classList.add('w-8', 'md:w-10');
                        indicator.classList.remove('w-2');
                    } else {
                        indicator.classList.remove('active');
                        indicator.classList.add('opacity-50');
                        indicator.classList.remove('w-8', 'md:w-10');
                        indicator.classList.add('w-2');
                    }
                });
            }
            
            // Navigate to specific slide
            function goToSlide(index) {
                if (isTransitioning) return;
                
                isTransitioning = true;
                currentSlide = index;
                
                // Update indicators immediately for better user feedback
                updateIndicators();
                
                slides.style.transition = 'transform 700ms ease-in-out';
                
                if (currentSlide === slideCount) {
                    slides.style.transform = `translateX(-${(currentSlide + 1) * slidesWidth}px)`;
                    
                    setTimeout(() => {
                        slides.style.transition = 'none';
                        currentSlide = 0;
                        slides.style.transform = `translateX(-${(currentSlide + 1) * slidesWidth}px)`;
                        
                        // Force reflow
                        void slides.offsetHeight;
                        
                        // Update indicators again after reset
                        updateIndicators();
                        
                        isTransitioning = false;
                    }, 700);
                } else if (currentSlide === -1) {
                    slides.style.transform = `translateX(-${(currentSlide + 1) * slidesWidth}px)`;
                    
                    setTimeout(() => {
                        slides.style.transition = 'none';
                        currentSlide = slideCount - 1;
                        slides.style.transform = `translateX(-${(currentSlide + 1) * slidesWidth}px)`;
                        
                        // Force reflow
                        void slides.offsetHeight;
                        
                        // Update indicators again after reset
                        updateIndicators();
                        
                        isTransitioning = false;
                    }, 700);
                } else {
                    slides.style.transform = `translateX(-${(currentSlide + 1) * slidesWidth}px)`;
                    setTimeout(() => {
                        isTransitioning = false;
                    }, 700);
                }
            }
            
            // Touch events for mobile swipe
            function touchStart(event) {
                if (isTransitioning) return;
                
                event.stopPropagation();
                isDragging = true;
                startPos = getPositionX(event);
                animationID = requestAnimationFrame(animation);
                slides.style.transition = 'none';
                
                // Pause auto-slide during touch interaction
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
                isAutoslidePaused = true;
            }
            
            function touchMove(event) {
                if (isDragging) {
                    event.stopPropagation();
                    const currentPosition = getPositionX(event);
                    currentTranslate = prevTranslate + currentPosition - startPos;
                }
            }
            
            function touchEnd(event) {
                if (event) {
                    event.stopPropagation();
                }
                
                if (!isDragging) return;
                
                isDragging = false;
                cancelAnimationFrame(animationID);
                
                const movedBy = currentTranslate - prevTranslate;
                
                // Reset transition for slide movement
                slides.style.transition = 'transform 700ms ease-in-out';
                
                // Determine if should advance slide based on swipe distance
                if (movedBy < -100) {
                    goToSlide(currentSlide + 1);
                } else if (movedBy > 100) {
                    goToSlide(currentSlide - 1);
                } else {
                    goToSlide(currentSlide);
                }
                
                prevTranslate = currentTranslate;
                
                // Restart auto-slide after touch interaction
                setTimeout(() => {
                    isAutoslidePaused = false;
                    restartAutoSlide();
                }, 700); // Wait for transition to complete
            }
            
            function getPositionX(event) {
                return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
            }
            
            function animation() {
                const currentPosition = (currentSlide + 1) * slidesWidth + (currentTranslate - prevTranslate);
                slides.style.transform = `translateX(-${currentPosition}px)`;
                if (isDragging) requestAnimationFrame(animation);
            }
            
            // Initialize carousel
            setTimeout(() => {
                setSlidePosition(false);
                updateIndicators();
                
                // Enable transitions after initial setup
                setTimeout(() => {
                    slides.style.transition = 'transform 700ms ease-in-out';
                }, 50);
                
                // Add event listeners
                prevButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (!isTransitioning) {
                        // Stop any existing auto-slide before manually advancing
                        clearInterval(autoSlideTimer);
                        autoSlideTimer = null;
                        
                        goToSlide(currentSlide - 1);
                        
                        // Restart auto-slide timer when user interacts
                        restartAutoSlide();
                    }
                });
                
                nextButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (!isTransitioning) {
                        // Stop any existing auto-slide before manually advancing
                        clearInterval(autoSlideTimer);
                        autoSlideTimer = null;
                        
                        goToSlide(currentSlide + 1);
                        
                        // Restart auto-slide timer when user interacts
                        restartAutoSlide();
                    }
                });
                
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (!isTransitioning) {
                            // Stop any existing auto-slide before manually changing slide
                            clearInterval(autoSlideTimer);
                            autoSlideTimer = null;
                            
                            goToSlide(index);
                            
                            // Restart auto-slide timer when user interacts
                            restartAutoSlide();
                        }
                    });
                });
                
                // Add touch event listeners
                slides.addEventListener('touchstart', touchStart);
                slides.addEventListener('touchmove', touchMove);
                slides.addEventListener('touchend', touchEnd);
                slides.addEventListener('mousedown', touchStart);
                slides.addEventListener('mousemove', touchMove);
                slides.addEventListener('mouseup', touchEnd);
                slides.addEventListener('mouseleave', touchEnd);
                
                // Prevent context menu on long press
                carousel.addEventListener('contextmenu', e => {
                    if (e.target.closest('.carousel-slides')) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });
                
                // Start auto-slide on page load
                startAutoSlide();
                
                // Pause auto-slide when user interacts with carousel
                carousel.addEventListener('mouseenter', () => {
                    isAutoslidePaused = true;
                    clearInterval(autoSlideTimer);
                    autoSlideTimer = null;
                });
                
                // Resume auto-slide when user leaves the carousel
                carousel.addEventListener('mouseleave', () => {
                    if (!isDragging && isAutoslidePaused) {
                        isAutoslidePaused = false;
                        startAutoSlide();
                    }
                });
            }, 100);
            
            // Handle resize events
            window.addEventListener('resize', () => {
                setSlidePosition(false);
            });

            // Add event listener for add to cart buttons after data is loaded
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Debug logging to see what data attributes are available
                    console.log('Button clicked:', this);
                    console.log('Button data attributes:', {
                        id: this.getAttribute('data-id'),
                        name: this.getAttribute('data-name'),
                        priceHt: this.getAttribute('data-price-ht'),
                        priceTtc: this.getAttribute('data-price-ttc')
                    });
                    
                    const productData = {
                        id_produit: parseInt(this.getAttribute('data-id')) || 0,
                        nom: this.getAttribute('data-name') || 'Produit',
                        prix_ht: parseFloat(this.getAttribute('data-price-ht')) || 0,
                        prix_ttc: parseFloat(this.getAttribute('data-price-ttc')) || 0,
                        quantite: 1
                    };
                    
                    addToCart(productData);
                });
            });
        }

        // Add to cart function
        function addToCart(productData) {
            // Format the data properly - ensure numbers are sent as numbers, not strings
            const formattedData = {
                id_produit: parseInt(productData.id_produit) || 0,
                nom: productData.nom || 'Produit',
                prix_ht: parseFloat(productData.prix_ht) || 0,
                prix_ttc: parseFloat(productData.prix_ttc) || 0,
                quantite: parseInt(productData.quantite) || 1
            };
            
            // Log formatted data for debugging
            console.log('Adding product to cart:', formattedData);
            
            // Add CSRF token to headers if available
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            // Try the first route with improved error handling
            tryAddToCart('/mock-add-to-cart', formattedData, headers)
                .catch(error => {
                    console.log('First route failed, trying backup route:', error);
                    return tryAddToCart('/simple-add-to-cart', formattedData, headers);
                })
                .catch(error => {
                    console.log('Backup route failed, trying final route:', error);
                    return tryAddToCart('/api/panier-update', formattedData, headers);
                })
                .then(data => {
                    // Update the cart counter in the header
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement && data) {
                        // Use the count from the server response if available
                        if (data.count !== undefined) {
                            cartCountElement.textContent = data.count;
                        } else {
                            // Fallback to the old way if count not provided
                            let currentCount = parseInt(cartCountElement.textContent.trim()) || 0;
                            currentCount += formattedData.quantite;
                            cartCountElement.textContent = currentCount;
                        }
                    }
                    
                    // Show success message
                    showToast(`${productData.nom} ajouté au panier`, 'success');
                })
                .catch(error => {
                    console.error('All cart routes failed:', error);
                    showToast(error.message || 'Erreur lors de l\'ajout au panier', 'error');
                    
                    // Last resort - submit as a form directly
                    submitCartAsForm(formattedData);
                });
        }
        
        // Function to try adding to cart using a specific URL
        function tryAddToCart(url, formattedData, headers) {
            return new Promise((resolve, reject) => {
                fetch(url, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(formattedData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            try {
                                const jsonError = JSON.parse(text);
                                console.error(`Error from ${url}:`, jsonError);
                                reject(new Error(jsonError.message || `Failed with status ${response.status}`));
                            } catch (e) {
                                console.error(`Text response from ${url}:`, text);
                                reject(new Error(`Failed with status ${response.status}`));
                            }
                        });
                    }
                    return response.json();
                })
                .then(data => resolve(data))
                .catch(error => reject(error));
            });
        }
        
        // Function to submit cart as a form as last resort
        function submitCartAsForm(formattedData) {
            console.log('Using form submission as last resort');
            
            // Create a form element
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/api/panier-update';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
            
            // Add product data fields
            Object.entries(formattedData).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });
            
            // Append form to body and submit it
            document.body.appendChild(form);
            form.submit();
        }
        
        // Function to show toast messages
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            
            // Add icon based on message type
            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            toast.innerHTML = `<i class="fas fa-${icon} mr-2"></i> ${message}`;
            
            // Add to DOM
            document.body.appendChild(toast);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    });
</script> 