document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.nav-links a, .profile-link, .logo-link');
    const params = new URLSearchParams(window.location.search);
    const currentPage = params.get('action') || 'home'; 

    console.log(links);

    links.forEach(link => {
        const linkPage = new URL(link.href).searchParams.get('action');
        if (linkPage === currentPage) {
            link.classList.add('active'); 
        }

        link.addEventListener('click', (e) => {
            const url = link.getAttribute('href');
            window.history.pushState({}, '', url);
            document.querySelector('.nav-links').classList.remove('visible');
            document.querySelector('.profile-link').classList.remove('visible');
        });
    });

    const burgerBtn = document.querySelector('.burger-btn');
    burgerBtn.addEventListener('click', () => {
        const nav = document.querySelector('.nav-links');
        const profileIcon = document.querySelector('.profile-link');
        nav.classList.toggle('visible');
        profileIcon.classList.toggle('visible');
        profileIcon.style.right = `${burgerBtn.offsetWidth + 15}px`;
        });

        window.addEventListener('popstate', () => {
            document.querySelector('.nav-links').classList.remove('visible');
            document.querySelector('.profile-link').classList.remove('visible');
        });
});