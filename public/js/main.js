/**
 * HelixRP - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
    
    // Dropdown menus
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Close all other dropdowns
            const allDropdowns = document.querySelectorAll('.dropdown');
            allDropdowns.forEach(dropdown => {
                if (dropdown !== this.parentNode) {
                    dropdown.classList.remove('active');
                }
            });
            
            // Toggle this dropdown
            this.parentNode.classList.toggle('active');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            const allDropdowns = document.querySelectorAll('.dropdown');
            allDropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });
    
    // User dropdown toggle
    const userDropdownToggle = document.querySelector('.user-dropdown-toggle');
    
    if (userDropdownToggle) {
        userDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            this.parentNode.classList.toggle('active');
        });
    }
    
    // Character stats tooltips
    const statItems = document.querySelectorAll('.stat-item');
    
    statItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const statType = this.querySelector('i').className.split(' ')[1];
            let tooltipText = '';
            
            switch (statType) {
                case 'fa-heart':
                    tooltipText = 'Health';
                    break;
                case 'fa-bolt':
                    tooltipText = 'Energy';
                    break;
                case 'fa-utensils':
                    tooltipText = 'Hunger';
                    break;
                default:
                    tooltipText = 'Stat';
            }
            
            // Get progress value
            const progressValue = this.querySelector('.progress-bar').style.width;
            
            // Create tooltip
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = `${tooltipText}: ${progressValue}`;
            
            // Add tooltip to stat item
            this.appendChild(tooltip);
        });
        
        item.addEventListener('mouseleave', function() {
            const tooltip = this.querySelector('.tooltip');
            if (tooltip) {
                this.removeChild(tooltip);
            }
        });
    });
    
    // Progress bar animation
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Check for required fields
            const requiredFields = form.querySelectorAll('[required]');
            let hasError = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    hasError = true;
                    field.classList.add('is-invalid');
                    
                    // Create or update error message
                    let feedback = field.parentNode.querySelector('.invalid-feedback');
                    
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        field.parentNode.appendChild(feedback);
                    }
                    
                    feedback.textContent = 'This field is required';
                } else {
                    field.classList.remove('is-invalid');
                    
                    // Remove error message if exists
                    const feedback = field.parentNode.querySelector('.invalid-feedback');
                    if (feedback) {
                        field.parentNode.removeChild(feedback);
                    }
                }
            });
            
            if (hasError) {
                e.preventDefault();
            }
        });
    });
}); 