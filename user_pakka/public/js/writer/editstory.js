// TITLE
document.getElementById('titleInput').addEventListener('input', function () {
    document.getElementById('previewTitle').innerText = this.value || '-';
});

// LANGUAGE
document.getElementById('languageSelect').addEventListener('change', function () {
    document.getElementById('previewLanguage').innerText = this.value;
});

// FORMAT CLICK (dynamic)
document.querySelectorAll('.format-card').forEach(card => {
    card.addEventListener('click', function () {

        // remove active
        document.querySelectorAll('.format-card').forEach(c => c.classList.remove('active'));

        // add active
        this.classList.add('active');

        // check radio
        const radio = this.querySelector('input');
        radio.checked = true;

        // trigger change manually (important)
        radio.dispatchEvent(new Event('change'));
    });
});

// listen to radio change (clean way)
document.querySelectorAll('input[name="format"]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.getElementById('previewFormat').innerText =
            this.value === 'serialized' ? 'Serialized' : 'Short Story';
    });
});

// COVER
document.getElementById('coverInput').addEventListener('change', function (e) {
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {
            document.getElementById('previewCover').src = event.target.result;
        }

        reader.readAsDataURL(file);
    }
});