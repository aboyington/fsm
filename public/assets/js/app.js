// Main App JavaScript
document.addEventListener('DOMContentLoaded', () => {
    // Check authentication
    const currentUser = sessionStorage.getItem('currentUser');
    if (currentUser) {
        const user = JSON.parse(currentUser);
        const userNameElement = document.getElementById('currentUserName');
        if (userNameElement) {
            userNameElement.textContent = `${user.first_name} ${user.last_name}`;
        }
    }
});

// Global logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        apiClient.logout();
    }
}

// Utility functions
function showAlert(type, message, container = null) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    if (container) {
        container.innerHTML = alertHtml;
    } else {
        // Find first alert container or prepend to main
        const alertContainer = document.querySelector('.alert-container') || document.querySelector('main');
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = alertHtml;
        alertContainer.prepend(tempDiv.firstElementChild);
    }
}

// Form error display function
function displayFormErrors(errors) {
    // Clear existing errors
    const errorElements = document.querySelectorAll('.text-danger');
    errorElements.forEach(element => element.remove());
    
    const errorInputs = document.querySelectorAll('.is-invalid');
    errorInputs.forEach(input => input.classList.remove('is-invalid'));
    
    // Display new errors
    Object.keys(errors).forEach(fieldName => {
        const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('is-invalid');
            
            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger small mt-1';
            errorDiv.textContent = errors[fieldName];
            field.parentNode.appendChild(errorDiv);
        }
    });
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Loading spinner
function showLoading(show = true) {
    const existingSpinner = document.getElementById('loadingSpinner');
    
    if (show && !existingSpinner) {
        const spinner = document.createElement('div');
        spinner.id = 'loadingSpinner';
        spinner.className = 'position-fixed top-50 start-50 translate-middle';
        spinner.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
        document.body.appendChild(spinner);
    } else if (!show && existingSpinner) {
        existingSpinner.remove();
    }
}

// Form validation helper
function validateForm(formElement) {
    const inputs = formElement.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
        
        // Remove invalid class on input
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('is-invalid');
            }
        });
    });
    
    return isValid;
}
