/**
 * HelixRP - Property Management JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Property filter form
    const filterForm = document.querySelector('.filter-form');
    
    if (filterForm) {
        // Validate price range
        const minPriceField = document.getElementById('min_price');
        const maxPriceField = document.getElementById('max_price');
        
        if (minPriceField && maxPriceField) {
            filterForm.addEventListener('submit', function(e) {
                const minPrice = parseInt(minPriceField.value) || 0;
                const maxPrice = parseInt(maxPriceField.value) || 0;
                
                if (maxPrice > 0 && minPrice > maxPrice) {
                    e.preventDefault();
                    alert('Minimum price cannot be greater than maximum price');
                }
            });
        }
    }
    
    // Property purchase form
    const purchaseForm = document.querySelector('.purchase-form');
    
    if (purchaseForm) {
        const purchaseTypeRadios = purchaseForm.querySelectorAll('input[name="purchase_type"]');
        const priceDisplay = document.querySelector('.property-price-display');
        const originalPrice = priceDisplay ? parseFloat(priceDisplay.dataset.price) : 0;
        const rentalFactor = 0.1; // 10% of purchase price for rent
        
        purchaseTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (priceDisplay) {
                    if (this.value === 'rent') {
                        const rentalPrice = Math.round(originalPrice * rentalFactor);
                        priceDisplay.textContent = numberFormat(rentalPrice) + ' Gold (Monthly)';
                        priceDisplay.dataset.currentPrice = rentalPrice;
                    } else {
                        priceDisplay.textContent = numberFormat(originalPrice) + ' Gold';
                        priceDisplay.dataset.currentPrice = originalPrice;
                    }
                }
            });
        });
        
        // Confirm purchase
        purchaseForm.addEventListener('submit', function(e) {
            const currentPrice = parseFloat(priceDisplay.dataset.currentPrice) || 0;
            const userMoney = parseFloat(document.querySelector('.character-money').dataset.money) || 0;
            
            if (currentPrice > userMoney) {
                e.preventDefault();
                alert('You do not have enough money to purchase this property');
            } else {
                if (!confirm('Are you sure you want to purchase this property?')) {
                    e.preventDefault();
                }
            }
        });
    }
    
    // Property sale form
    const saleForm = document.querySelector('.sale-form');
    
    if (saleForm) {
        saleForm.addEventListener('submit', function(e) {
            if (!document.getElementById('confirm_sale').checked) {
                e.preventDefault();
                alert('Please confirm that you want to sell this property');
            } else {
                if (!confirm('Are you sure you want to sell this property? This action cannot be undone.')) {
                    e.preventDefault();
                }
            }
        });
    }
    
    // Furniture management
    initFurnitureManagement();
});

/**
 * Initialize furniture management functionality
 */
function initFurnitureManagement() {
    // Furniture purchase
    const furniturePurchaseForms = document.querySelectorAll('.furniture-purchase-form');
    
    furniturePurchaseForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const price = parseFloat(this.dataset.price) || 0;
            const userMoney = parseFloat(document.querySelector('.character-money').dataset.money) || 0;
            
            if (price > userMoney) {
                e.preventDefault();
                alert('You do not have enough money to purchase this furniture');
            } else {
                if (!confirm('Are you sure you want to purchase this furniture?')) {
                    e.preventDefault();
                }
            }
        });
    });
    
    // Furniture position update
    const furnitureItems = document.querySelectorAll('.furniture-item');
    const propertyView = document.querySelector('.property-view-area');
    
    if (propertyView && furnitureItems.length) {
        let activeFurniture = null;
        let isDragging = false;
        let startX, startY;
        
        // Make furniture items draggable
        furnitureItems.forEach(item => {
            // Initial position from database
            const posX = parseFloat(item.dataset.posX) || 0;
            const posY = parseFloat(item.dataset.posY) || 0;
            const rotation = parseFloat(item.dataset.rotation) || 0;
            
            item.style.left = posX + 'px';
            item.style.top = posY + 'px';
            item.style.transform = `rotate(${rotation}deg)`;
            
            // Drag functionality
            item.addEventListener('mousedown', function(e) {
                if (e.target.closest('.furniture-controls')) {
                    return; // Don't start drag if clicked on controls
                }
                
                activeFurniture = this;
                isDragging = true;
                startX = e.clientX - this.offsetLeft;
                startY = e.clientY - this.offsetTop;
                
                // Add active class
                furnitureItems.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
                
                // Update form values
                updateFurnitureForm(this);
            });
        });
        
        // Property view mouse events
        propertyView.addEventListener('mousemove', function(e) {
            if (isDragging && activeFurniture) {
                const x = e.clientX - startX;
                const y = e.clientY - startY;
                
                // Limit to property view boundaries
                const maxX = propertyView.offsetWidth - activeFurniture.offsetWidth;
                const maxY = propertyView.offsetHeight - activeFurniture.offsetHeight;
                
                const newX = Math.max(0, Math.min(maxX, x));
                const newY = Math.max(0, Math.min(maxY, y));
                
                activeFurniture.style.left = newX + 'px';
                activeFurniture.style.top = newY + 'px';
                
                // Update form values
                updateFurnitureForm(activeFurniture);
            }
        });
        
        propertyView.addEventListener('mouseup', function() {
            isDragging = false;
        });
        
        propertyView.addEventListener('mouseleave', function() {
            isDragging = false;
        });
        
        // Furniture controls
        const rotateLeftButtons = document.querySelectorAll('.rotate-left');
        const rotateRightButtons = document.querySelectorAll('.rotate-right');
        const removeFurnitureButtons = document.querySelectorAll('.remove-furniture');
        
        rotateLeftButtons.forEach(button => {
            button.addEventListener('click', function() {
                const item = this.closest('.furniture-item');
                const currentRotation = parseFloat(item.dataset.rotation) || 0;
                const newRotation = (currentRotation - 15) % 360;
                
                item.dataset.rotation = newRotation;
                item.style.transform = `rotate(${newRotation}deg)`;
                
                // Update form values
                updateFurnitureForm(item);
            });
        });
        
        rotateRightButtons.forEach(button => {
            button.addEventListener('click', function() {
                const item = this.closest('.furniture-item');
                const currentRotation = parseFloat(item.dataset.rotation) || 0;
                const newRotation = (currentRotation + 15) % 360;
                
                item.dataset.rotation = newRotation;
                item.style.transform = `rotate(${newRotation}deg)`;
                
                // Update form values
                updateFurnitureForm(item);
            });
        });
        
        removeFurnitureButtons.forEach(button => {
            button.addEventListener('click', function() {
                const item = this.closest('.furniture-item');
                
                if (confirm('Are you sure you want to remove this furniture?')) {
                    // Submit the remove form
                    document.getElementById('remove_furniture_' + item.dataset.id).submit();
                }
            });
        });
        
        // Furniture position form
        const positionForm = document.getElementById('furniture_position_form');
        
        if (positionForm) {
            positionForm.addEventListener('submit', function(e) {
                if (!activeFurniture) {
                    e.preventDefault();
                    alert('Please select a furniture item first');
                }
            });
        }
    }
}

/**
 * Update furniture form values based on furniture position
 * 
 * @param {HTMLElement} item Furniture item element
 */
function updateFurnitureForm(item) {
    const form = document.getElementById('furniture_position_form');
    
    if (form) {
        form.elements['furniture_id'].value = item.dataset.id;
        form.elements['position_x'].value = parseInt(item.style.left) || 0;
        form.elements['position_y'].value = parseInt(item.style.top) || 0;
        form.elements['position_z'].value = item.dataset.posZ || 0;
        form.elements['rotation'].value = item.dataset.rotation || 0;
    }
}

/**
 * Format number with commas
 * 
 * @param {number} number Number to format
 * @returns {string} Formatted number
 */
function numberFormat(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
} 