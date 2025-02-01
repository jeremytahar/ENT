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

// Appel de la fonction d'initialisation des fonctionnalités
document.addEventListener('DOMContentLoaded', init);

// Fonction pour gérer la visibilité du mot de passe
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

// Initialisation des fonctionnalités de mot de passe
function initPasswordFeatures() {
    const passwordFields = document.querySelectorAll('[type="password"]');
    passwordFields.forEach(passwordField => {
        const toggleIcon = document.querySelector(`#toggle-${passwordField.id}`);
        if (toggleIcon) {
            togglePasswordVisibility(passwordField.id, toggleIcon.id);
        }
    });
}

// Appel de la fonction d'initialisation des fonctionnalités de mot de passe
document.addEventListener('DOMContentLoaded', initPasswordFeatures);

// Fonction pour gérer la recherche
function setupSearch(inputSelector, itemSelector, messageSelector, getSearchText = (item) => item.textContent) {
    const input = document.querySelector(inputSelector);
    const items = document.querySelectorAll(itemSelector);
    const noResultsMessage = document.querySelector(messageSelector);

    if (!input || !items || !noResultsMessage) {
        console.warn('Un ou plusieurs sélecteurs sont invalides.');
        return;
    }

    // Fonction pour retirer les accents
    const removeAccents = (str) => str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    input.addEventListener('input', () => {
        const searchValue = removeAccents(input.value.toLowerCase());
        let hasVisibleItem = false;

        items.forEach(item => {
            const itemText = removeAccents(getSearchText(item).toLowerCase());

            
            if (itemText.includes(searchValue)) {
                item.style.display = '';
                hasVisibleItem = true;
            } else {
                item.style.display = 'none';
            }
        });

        noResultsMessage.style.display = hasVisibleItem ? 'none' : 'block';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Recherche pour les professeurs (uniquement si des professeurs existent sur la page)
    if (document.querySelector('.teacher')) {
        setupSearch(
            '#searchInput',
            '.teacher',
            '.noResults',
            (item) => item.querySelector('h2').textContent
        );
    }

    // Recherche pour les modules (uniquement si des modules existent sur la page)
    if (document.querySelector('.course')) {
        setupSearch(
            '#searchInput',
            '.course',
            '.noResults',
            (item) => item.querySelector('h2').textContent
        );
    }
});


// Fonction pour ouvrir et fermer les modules des notes
function toggleModule(event) {
    const moduleHeader = event.currentTarget;
    const content = moduleHeader.nextElementSibling;
    const arrow = moduleHeader.querySelector(".arrow");

    if (content.classList.contains("hidden")) {
        content.classList.remove("hidden");
        arrow.style.transform = "rotate(0deg)";
    } else {
        content.classList.add("hidden");
        arrow.style.transform = "rotate(-90deg)";
    }
}

document.querySelectorAll(".module-header").forEach(header => {
    header.addEventListener("click", toggleModule);
});


document.addEventListener("DOMContentLoaded", function () {
    const modules = document.querySelectorAll(".module");
    let totalModuleAverages = 0;
    let moduleCount = 0;

    modules.forEach(module => {
        const rows = module.querySelectorAll(".items-table .score");
        let totalNotes = 0;
        let totalMax = 0;

        rows.forEach(row => {
            const score = row.textContent.split('/');
            const note = parseFloat(score[0]);
            const max = parseFloat(score[1]);
            if (!isNaN(note) && !isNaN(max)) {
                totalNotes += note;
                totalMax += max;
            }
        });

        const average = totalMax > 0 ? ((totalNotes / totalMax) * 20).toFixed(2) : 0;

        const averageElement = module.querySelector(".average");
        if (averageElement) {
            averageElement.textContent = `${average}/20`;
        }

        if (average > 0) {
            totalModuleAverages += parseFloat(average);
            moduleCount++;
        }
    });

    const generalAverage = moduleCount > 0 ? (totalModuleAverages / moduleCount).toFixed(2) : 0;

    const generalAverageElement = document.querySelector(".general-average-grade");
    if (generalAverageElement) {
        generalAverageElement.textContent = `${generalAverage}/20`;
    }
});

document.getElementById('profile-picture').addEventListener('change', function () {
    document.getElementById('profile-form').submit();
});


  
