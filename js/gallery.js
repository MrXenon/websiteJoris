// Interactieve fotogalerij
// Werkt voor elke <div data-gallery> op de pagina (zie includes/gallery.php)

document.querySelectorAll('[data-gallery]').forEach(function (galerij) {
    const groteFoto = galerij.querySelector('[data-gallery-image]');
    const webpBron = galerij.querySelector('[data-gallery-source]');
    const thumbs = galerij.querySelectorAll('.gallery-thumb');
    const teller = galerij.querySelector('[data-gallery-counter]');
    const vorigeKnop = galerij.querySelector('[data-gallery-prev]');
    const volgendeKnop = galerij.querySelector('[data-gallery-next]');
    const zoomKnop = galerij.querySelector('[data-gallery-zoom]');

    let huidige = 0;

    // Laat foto nummer "index" zien
    function toonFoto(index) {
        // Rondje maken: na de laatste weer naar de eerste
        if (index < 0) {
            index = thumbs.length - 1;
        }
        if (index >= thumbs.length) {
            index = 0;
        }

        // Zelfde foto aangeklikt? Dan hoeft er niks te gebeuren
        if (index === huidige) {
            return;
        }

        const thumb = thumbs[index];

        // Eerst laten vervagen, dan de nieuwe foto laden
        groteFoto.classList.add('fade');
        setTimeout(function () {
            webpBron.srcset = thumb.dataset.fullWebp;
            groteFoto.src = thumb.dataset.full;
            groteFoto.alt = thumb.dataset.alt;
        }, 150);

        // Staat de lightbox open? Dan daar ook de nieuwe foto tonen
        if (lightbox.classList.contains('open')) {
            lightboxFoto.src = thumb.dataset.fullWebp;
            lightboxFoto.alt = thumb.dataset.alt;
        }

        // Actieve thumbnail bijwerken
        thumbs[huidige].classList.remove('active');
        thumb.classList.add('active');
        thumb.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });

        huidige = index;
        teller.textContent = (huidige + 1) + ' / ' + thumbs.length;
    }

    // Als de nieuwe foto geladen is: weer zichtbaar maken
    groteFoto.addEventListener('load', function () {
        groteFoto.classList.remove('fade');
    });

    // Klik op een thumbnail
    thumbs.forEach(function (thumb, index) {
        thumb.addEventListener('click', function () {
            toonFoto(index);
        });
    });

    vorigeKnop.addEventListener('click', function () {
        toonFoto(huidige - 1);
    });

    volgendeKnop.addEventListener('click', function () {
        toonFoto(huidige + 1);
    });

    // Pijltjestoetsen op het toetsenbord
    document.addEventListener('keydown', function (event) {
        // Niet reageren als iemand in een invoerveld zit (bijv. het aantal)
        if (event.target.closest('input, textarea, select')) {
            return;
        }
        if (event.key === 'ArrowLeft') {
            toonFoto(huidige - 1);
        } else if (event.key === 'ArrowRight') {
            toonFoto(huidige + 1);
        } else if (event.key === 'Escape') {
            sluitLightbox();
        }
    });

    // Vegen op telefoon (swipe)
    let startX = 0;
    groteFoto.addEventListener('touchstart', function (event) {
        startX = event.touches[0].clientX;
    }, { passive: true });

    groteFoto.addEventListener('touchend', function (event) {
        const verschil = event.changedTouches[0].clientX - startX;
        if (verschil > 50) {
            toonFoto(huidige - 1);
        } else if (verschil < -50) {
            toonFoto(huidige + 1);
        }
    });

    // ---------- Lightbox (foto groot op het hele scherm) ----------
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = '<button class="lightbox-close" aria-label="Sluiten">&times;</button><img alt="">';
    document.body.appendChild(lightbox);

    const lightboxFoto = lightbox.querySelector('img');

    function openLightbox() {
        lightboxFoto.src = groteFoto.currentSrc || groteFoto.src;
        lightboxFoto.alt = groteFoto.alt;
        lightbox.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function sluitLightbox() {
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
    }

    zoomKnop.addEventListener('click', openLightbox);
    groteFoto.addEventListener('click', openLightbox);

    // Klik op de donkere achtergrond of het kruisje = sluiten
    lightbox.addEventListener('click', function (event) {
        if (event.target !== lightboxFoto) {
            sluitLightbox();
        }
    });
});
