document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.nav-links a');
    const params = new URLSearchParams(window.location.search);
    const currentPage = params.get('action') || 'home'; 

    links.forEach(link => {
        const linkPage = new URL(link.href).searchParams.get('action');
        if (linkPage === currentPage) {
            link.classList.add('active'); 
        }
    });
});