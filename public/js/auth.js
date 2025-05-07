/**
 * HelixRP - Authentication JS
 */

document.addEventListener('DOMContentLoaded', function() {
    // Password strength validation
    const passwordField = document.getElementById('password');
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            validatePasswordStrength(this.value);
        });
    }
    
    // Password confirmation validation
    const passwordConfirmField = document.getElementById('password_confirm');
    if (passwordConfirmField) {
        passwordConfirmField.addEventListener('input', function() {
            validatePasswordMatch(passwordField.value, this.value);
        });
    }
});

/**
 * Validate password strength
 * 
 * @param {string} password Password to validate
 */
function validatePasswordStrength(password) {
    // Get password field element
    const passwordField = document.getElementById('password');
    
    // Remove existing feedback
    let existingFeedback = passwordField.parentNode.querySelector('.password-strength');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // If password is empty, do nothing
    if (!password) {
        return;
    }
    
    // Check password strength
    let strength = 0;
    let feedback = '';
    
    // Length check
    if (password.length >= 8) {
        strength += 1;
    } else {
        feedback = 'Password should be at least 8 characters long';
    }
    
    // Contains uppercase letter
    if (/[A-Z]/.test(password)) {
        strength += 1;
    } else if (!feedback) {
        feedback = 'Password should contain at least one uppercase letter';
    }
    
    // Contains lowercase letter
    if (/[a-z]/.test(password)) {
        strength += 1;
    } else if (!feedback) {
        feedback = 'Password should contain at least one lowercase letter';
    }
    
    // Contains number
    if (/[0-9]/.test(password)) {
        strength += 1;
    } else if (!feedback) {
        feedback = 'Password should contain at least one number';
    }
    
    // Contains special character
    if (/[^A-Za-z0-9]/.test(password)) {
        strength += 1;
    } else if (!feedback) {
        feedback = 'Password should contain at least one special character';
    }
    
    // Create strength indicator
    let strengthClass = '';
    let strengthText = '';
    
    if (strength < 2) {
        strengthClass = 'text-danger';
        strengthText = 'Weak';
    } else if (strength < 4) {
        strengthClass = 'text-warning';
        strengthText = 'Moderate';
    } else {
        strengthClass = 'text-success';
        strengthText = 'Strong';
    }
    
    // Create feedback element
    const feedbackElement = document.createElement('div');
    feedbackElement.className = 'password-strength ' + strengthClass;
    feedbackElement.innerHTML = feedback ? feedback : 'Password Strength: ' + strengthText;
    
    // Add feedback after password field
    passwordField.parentNode.appendChild(feedbackElement);
}

/**
 * Validate password match
 * 
 * @param {string} password Password
 * @param {string} confirmPassword Confirmation password
 */
function validatePasswordMatch(password, confirmPassword) {
    // Get confirmation field element
    const confirmField = document.getElementById('password_confirm');
    
    // Remove existing feedback
    let existingFeedback = confirmField.parentNode.querySelector('.password-match');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // If either field is empty, do nothing
    if (!password || !confirmPassword) {
        return;
    }
    
    // Check if passwords match
    const match = password === confirmPassword;
    
    // Create feedback element
    const feedbackElement = document.createElement('div');
    feedbackElement.className = 'password-match ' + (match ? 'text-success' : 'text-danger');
    feedbackElement.innerHTML = match ? 'Passwords match' : 'Passwords do not match';
    
    // Add feedback after confirmation field
    confirmField.parentNode.appendChild(feedbackElement);
} 