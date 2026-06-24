// ==========================================
// TITLE PREVIEW
// ==========================================
document.getElementById('titleInput')
.addEventListener('input', function(){

    document.getElementById('previewTitle')
    .innerText = this.value || '-';

});

// ==========================================
// LANGUAGE PREVIEW
// ==========================================
document.getElementById('languageSelect')
.addEventListener('change', function(){

    document.getElementById('previewLanguage')
    .innerText = this.value || '-';

});

// ==========================================
// SYNOPSIS WORD COUNT
// ==========================================
const synopsisInput =
    document.getElementById('synopsisInput');

const wordCount =
    document.getElementById('wordCount');

function updateWordCount(){

    const text = synopsisInput.value.trim();

    if(text === ''){

        wordCount.innerText = '0 words';

        return;
    }

    const words = text.split(/\s+/).length;

    wordCount.innerText = words + ' words';

}

synopsisInput.addEventListener(
    'input',
    updateWordCount
);

// ==========================================
// RESTORE GENRES
// ==========================================
const savedGenres =
    JSON.parse(localStorage.getItem('draft_story_genres')) || [];

document.querySelectorAll('input[name="genres[]"]')
.forEach(item => {

    if(savedGenres.includes(item.value)){

        item.checked = true;

    }

});

// ==========================================
// RESTORE TAGS
// ==========================================
const savedTags =
    JSON.parse(localStorage.getItem('draft_story_tags')) || [];

document.querySelectorAll('input[name="tags[]"]')
.forEach(item => {

    if(savedTags.includes(item.value)){

        item.checked = true;

    }

});

updateWordCount();

// ==========================================
// COVER PREVIEW
// ==========================================
document.getElementById('coverInput')
.addEventListener('change', function(e){

    const file = e.target.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(event){

            document.getElementById('previewCover')
            .src = event.target.result;

        }

        reader.readAsDataURL(file);

    }

});

// ==========================================
// PREMIUM TOGGLE
// ==========================================
const toggle = document.getElementById('premiumToggle');

function updatePriceBox(){

    if(toggle.checked){

        priceBox.style.display = 'block';

        priceInput.setAttribute('required', true);

    }else{

        priceBox.style.display = 'none';

        priceInput.removeAttribute('required');

        priceInput.value = '';

    }

}

updatePriceBox();

toggle.addEventListener('change', updatePriceBox);

// ==========================================
// INITIAL PREVIEW SETUP
// ==========================================
window.addEventListener('DOMContentLoaded', () => {

    document.getElementById('previewTitle').innerText =
        document.getElementById('titleInput').value || '-';

    const languageValue =
        document.getElementById('languageSelect').value;

    document.getElementById('previewLanguage').innerText =
        languageValue || '-';

});
