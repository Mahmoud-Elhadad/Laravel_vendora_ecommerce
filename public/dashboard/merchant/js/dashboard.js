// Common Dashboard JavaScript Functions

// Sidebar Toggle for Mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('open');
}

// Initialize Sidebar Toggle
document.addEventListener('DOMContentLoaded', function() {
    // Add mobile menu button if it doesn't exist
    if (!document.querySelector('.mobile-menu-btn')) {
        const navbar = document.querySelector('.navbar-left');
        if (navbar) {
            const menuBtn = document.createElement('button');
            menuBtn.className = 'mobile-menu-btn';
            menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            menuBtn.style.cssText = `
                display: none;
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: var(--text-primary);
                margin-right: 16px;
            `;
            menuBtn.onclick = toggleSidebar;
            navbar.insertBefore(menuBtn, navbar.firstChild);
        }
    }

    // Show mobile menu button on small screens
    if (window.innerWidth <= 768) {
        const menuBtn = document.querySelector('.mobile-menu-btn');
        if (menuBtn) {
            menuBtn.style.display = 'block';
        }
    }
});

// Responsive handling
window.addEventListener('resize', function() {
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');
    
    if (window.innerWidth <= 768) {
        if (menuBtn) menuBtn.style.display = 'block';
        sidebar.classList.remove('open');
    } else {
        if (menuBtn) menuBtn.style.display = 'none';
        sidebar.classList.remove('open');
    }
});

// Notification Badge Animation
function animateNotificationBadge() {
    const badge = document.querySelector('.navbar-notifications .badge');
    if (badge) {
        badge.style.animation = 'pulse 2s infinite';
    }
}

// Toast Notification System
function showToast(message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;

    // Add styles
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(toast);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add CSS animations for toast
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
`;
document.head.appendChild(style);

// Confirm Dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Format Currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Format Date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Search Functionality
function initializeSearch(searchInput, tableRows) {
    if (!searchInput || !tableRows) return;

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

// Filter Functionality
function initializeFilter(filterSelect, tableRows, columnIndex) {
    if (!filterSelect || !tableRows) return;

    filterSelect.addEventListener('change', function(e) {
        const filterValue = e.target.value.toLowerCase();
        
        tableRows.forEach(row => {
            if (filterValue === '') {
                row.style.display = '';
            } else {
                const cell = row.cells[columnIndex];
                const cellText = cell.textContent.toLowerCase();
                row.style.display = cellText.includes(filterValue) ? '' : 'none';
            }
        });
    });
}

// Form Validation
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        const formGroup = field.closest('.form-group');
        if (!field.value.trim()) {
            isValid = false;
            if (formGroup) {
                formGroup.classList.add('error');
            }
        } else {
            if (formGroup) {
                formGroup.classList.remove('error');
            }
        }
    });

    return isValid;
}

// Image Preview
function initializeImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (!input || !preview) return;

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.add('show');
            };
            reader.readAsDataURL(file);
        }
    });
}

// Delete Button Handler
function initializeDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.btn-danger');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            confirmAction('Are you sure you want to delete this item?', function() {
                showToast('Item deleted successfully', 'success');
                // In a real app, this would make an API call
            });
        });
    });
}

// Number Counter Animation
function animateCounter(element, target, duration = 1000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start).toLocaleString();
        }
    }, 16);
}

// Initialize Counter Animations
function initializeCounters() {
    const counters = document.querySelectorAll('.card .value');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
        if (!isNaN(target)) {
            animateCounter(counter, target);
        }
    });
}

// Dropdown Toggle
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});

// Initialize all common functionality
document.addEventListener('DOMContentLoaded', function() {
    animateNotificationBadge();
    initializeDeleteButtons();
    initializeCounters();
});
