(function () {
    const container = document.getElementById('catBooks');
    const btnPrev = document.getElementById('catPrev');
    const btnNext = document.getElementById('catNext');

    if (!container || !btnPrev || !btnNext) return;

    // Les données viennent du PHP via data-books=""
    const raw = container.getAttribute('data-books') || '[]';

    let books = [];
    try {
        books = JSON.parse(raw);
    } catch (e) {
        books = [];
    }

    if (!Array.isArray(books) || books.length === 0) return;

    const cards = container.querySelectorAll('a[data-slot]');
    const perPage = 4;
    let start = 0;

    function coverSrc(filename) {
        if (filename && filename.trim() !== '') {
            return "/assets/bo/img/" + filename;
        }
        return "../assets/fo/img/books/placeholder.png";
    }

    function bookHref(id) {
        if (!id || Number(id) <= 0) return "#";
        return "../views/book-detail.php?id=" + Number(id);
    }

    function render() {
        for (let i = 0; i < perPage; i++) {
            const idx = (start + i) % books.length;
            const b = books[idx] || {};

            const a = cards[i];
            const img = a.querySelector('img');
            const t = a.querySelector('.title');
            const au = a.querySelector('.author');

            a.href = bookHref(b.id_livre);
            img.src = coverSrc(b.livre_couverture);
            img.alt = b.livre_titre ? b.livre_titre : 'Livre';

            t.textContent = b.livre_titre ? b.livre_titre : 'Livre';
            au.textContent = b.auteur_nom ? b.auteur_nom : 'Auteur inconnu';

            if (!b.id_livre || Number(b.id_livre) <= 0) {
                a.style.pointerEvents = "none";
                a.style.opacity = "0.6";
            } else {
                a.style.pointerEvents = "";
                a.style.opacity = "";
            }
        }
    }

    function prev() {
        start -= perPage;
        if (start < 0) start = Math.max(0, books.length - perPage);
        render();
    }

    function next() {
        start = (start + perPage) % books.length;
        render();
    }

    render();
    btnPrev.addEventListener('click', prev);
    btnNext.addEventListener('click', next);
})();
