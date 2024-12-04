// Fonction pour ajouter la classe "active" aux liens correspondant à la page actuelle
function handleActiveLinks() {
    const links = document.querySelectorAll('.nav-links a, .profile-link, .logo-link');
    const params = new URLSearchParams(window.location.search);
    const currentPage = params.get('action') || 'home';

    if (links.length > 0) {
        links.forEach(link => {
            const linkPage = new URL(link.href).searchParams.get('action');
            if (linkPage === currentPage) {
                link.classList.add('active');
            }

            link.addEventListener('click', (e) => {
                const url = link.getAttribute('href');
                window.history.pushState({}, '', url);
                const nav = document.querySelector('.nav-links');
                const profileIcon = document.querySelector('.profile-link');
                if (nav) nav.classList.remove('visible');
                if (profileIcon) profileIcon.classList.remove('visible');
            });
        });
    }
}

// Fonction pour gérer le menu burger
function handleBurgerMenu() {
    const burgerBtn = document.querySelector('.burger-btn');
    if (burgerBtn) {
        burgerBtn.addEventListener('click', () => {
            const nav = document.querySelector('.nav-links');
            const profileIcon = document.querySelector('.profile-link');
            const logoutIcon = document.querySelector('.logout-link');

            burgerBtn.classList.toggle('active');
            if (nav) nav.classList.toggle('visible');
            if (profileIcon) profileIcon.classList.toggle('visible');
            if (logoutIcon) logoutIcon.classList.toggle('visible');

            if (logoutIcon) {
                logoutIcon.style.right = `${burgerBtn.offsetWidth + 15}px`;
            }
            if (profileIcon && logoutIcon) {
                profileIcon.style.right = `${burgerBtn.offsetWidth + logoutIcon.offsetWidth + 15}px`;
            }
        });
    }
}

// Fonction pour gérer l'événement "popstate"
function handlePopState() {
    window.addEventListener('popstate', () => {
        const nav = document.querySelector('.nav-links');
        const profileIcon = document.querySelector('.profile-link');
        const logoutIcon = document.querySelector('.logout-link');

        if (nav) nav.classList.remove('visible');
        if (profileIcon) profileIcon.classList.remove('visible');
        if (logoutIcon) logoutIcon.classList.remove('visible');
    });
}

// Initialisation des fonctionnalités
function init() {
    handleActiveLinks();
    handleBurgerMenu();
    handlePopState();
}

// Lancement
document.addEventListener('DOMContentLoaded', init);


function togglePasswordVisibility(passwordId, iconId) {
    const passwordField = document.getElementById(passwordId);
    const icon = document.getElementById(iconId);
    const eyeClosedIcon = 'public/img/eye-closed.svg';
    const eyeIcon = 'public/img/eye.svg';

    if (!passwordField || !icon) return;

    icon.addEventListener('click', function () {
        const isPassword = passwordField.type === 'password';
        passwordField.type = isPassword ? 'text' : 'password';
        icon.querySelector('img').src = isPassword ? eyeClosedIcon : eyeIcon;
    });
}

function initPasswordFeatures() {
    const passwordFields = document.querySelectorAll('[type="password"]');
    passwordFields.forEach(passwordField => {
        const toggleIcon = document.querySelector(`#toggle-${passwordField.id}`);
        if (toggleIcon) {
            togglePasswordVisibility(passwordField.id, toggleIcon.id);
        }
    });
}

document.addEventListener('DOMContentLoaded', initPasswordFeatures);



