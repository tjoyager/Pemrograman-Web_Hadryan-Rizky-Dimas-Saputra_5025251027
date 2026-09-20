document.addEventListener('DOMContentLoaded', function () {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navMenu = document.getElementById('navMenu');

    if (hamburgerBtn && navMenu) {
        hamburgerBtn.addEventListener('click', function () {
            navMenu.classList.toggle('active');
        });
    }

    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const nama = document.getElementById('nama').value.trim();
            const email = document.getElementById('email').value.trim();
            const subjek = document.getElementById('subjek').value.trim();
            const pesan = document.getElementById('pesan').value.trim();

            if (!nama || !email || !subjek || !pesan) {
                alert('Peringatan: Harap lengkapi semua kolom formulir sebelum mengirim pesan!');
                return;
            }

            alert('Terima kasih ' + nama + '! Pesan Anda telah berhasil terkirim.');
            contactForm.reset();
        });
    }
});
