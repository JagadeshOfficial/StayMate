document.addEventListener("DOMContentLoaded", () => {
    const sideMenu = document.getElementById("side-menu");
    const signupModal = document.getElementById("signup-modal");
    const signinModal = document.getElementById("signin-modal");
    const forgotPasswordModal = document.getElementById("forgot-password-modal");
    const submenu = document.getElementById("signup-submenu");

    // Toggle side menu
    window.toggleMenu = () => {
        sideMenu.classList.toggle("open");
    };

    // Toggle submenu
    window.toggleSubMenu = (event) => {
        event.preventDefault();
        submenu.classList.toggle("open");
    };

    // Open signup modal
    window.openSignupModal = () => {
        closeAllModals();
        signupModal.style.display = "block";
    };

    // Open signin modal
    window.openSigninModal = () => {
        closeAllModals();
        signinModal.style.display = "block";
    };

    // Open forgot password modal
    window.openForgotPasswordModal = () => {
        closeAllModals();
        forgotPasswordModal.style.display = "block";
    };

    // Close all modals and submenu
    const closeAllModals = () => {
        signupModal.style.display = "none";
        signinModal.style.display = "none";
        forgotPasswordModal.style.display = "none";
        submenu.classList.remove("open");
    };

    // Close modals or menu when clicking outside
    document.addEventListener("click", (event) => {
        const target = event.target;

        // Ignore clicks inside modals, side menu, or submenu
        if (
            target.closest(".modal-content") || 
            target.closest(".side-menu") || 
            target.closest(".submenu") || 
            target.closest(".menu-icon")
        ) {
            return;
        }

        closeAllModals();
        sideMenu.classList.remove("open");
    });

    // Attach click events for modal buttons dynamically
    document.body.addEventListener("click", (event) => {
        const target = event.target;

        if (target.matches("#signup-form button[type='button']")) {
            sendOTP();
        } else if (target.matches("#signup-form button[type='submit']")) {
            validateForm(event);
        } else if (target.matches("#signin-form button[type='submit']")) {
            login(event);
        } else if (target.matches("#forgot-password-form button[type='submit']")) {
            alert("Password reset link sent to your email!");
            closeAllModals();
        }
    });

    
});















/* hostel script*/
// Toggle side menu
function toggleMenu() {
    document.getElementById('side-menu').classList.toggle('open');
}

// Update price range value
function updatePriceValue() {
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    priceValue.textContent = `₹${priceRange.value}`;
}

// Apply filters and update the hostel display
function applyFilters() {
    const location = document.getElementById('location').value;
    const priceRange = document.getElementById('price-range').value;
    const roomType = document.getElementById('room-type').value;

    // Fetch and filter hostels based on selected filters (in a real app, this would be an API call or dynamic rendering)
    const filteredHostels = getFilteredHostels(location, priceRange, roomType);

    // Display filtered hostels
    displayHostels(filteredHostels);
}



// Filter hostels based on selected criteria
function getFilteredHostels(location, price, roomType) {
    return allHostels.filter(hostel => {
        return (
            (hostel.location === location || location === 'all') &&
            hostel.price <= price &&
            (hostel.roomType === roomType || roomType === 'all')
        );
    });
}

// Display hostels in the property-cards section
function displayHostels(hostels) {
    const propertyCardsContainer = document.getElementById('property-cards');
    propertyCardsContainer.innerHTML = '';

    hostels.forEach(hostel => {
        const card = document.createElement('div');
        card.classList.add('property-card');
        card.innerHTML = `
            <img src="${hostel.img}" alt="${hostel.name}">
            <h4>${hostel.name}</h4>
            <p>Location: ${hostel.location}</p>
            <p>Price: ₹${hostel.price}/month</p>
            <p>Room Type: ${hostel.roomType}</p>
            <button class="btn-secondary">Book Now</button>
        `;
        propertyCardsContainer.appendChild(card);
    });
}

// Initial display of all hostels
displayHostels(allHostels);







let currentIndex = 0;

function navigateLeft() {
    const cards = document.querySelectorAll('.property-card');
    if (currentIndex > 0) {
        currentIndex--;
        cards[currentIndex].scrollIntoView({ behavior: 'smooth', inline: 'center' });
    }
}

function navigateRight() {
    const cards = document.querySelectorAll('.property-card');
    if (currentIndex < cards.length - 1) {
        currentIndex++;
        cards[currentIndex].scrollIntoView({ behavior: 'smooth', inline: 'center' });
    }
}


