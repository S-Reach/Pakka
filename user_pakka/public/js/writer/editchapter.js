// ===================================
// GLOBAL DATA FROM BLADE
// ===================================
const isEditing = window.chapterData.isEditing === 'true';
const storyLanguage = window.chapterData.storyLanguage;
const storyId = window.chapterData.storyId;
const chapterId = window.chapterData.chapterId;

// ===================================
// ELEMENTS
// ===================================
const premiumToggle = document.getElementById('premiumToggle');
const priceBox = document.getElementById('priceBox');
const contentTextarea = document.getElementById('chapterContent');
const autosaveStatus = document.getElementById('autosaveStatus');

const validationModal = document.getElementById('validationModal');
const validationMessage = document.getElementById('validationMessage');
const closeValidationModal = document.getElementById('closeValidationModal');

const successModal = document.getElementById('successModal');

// ===================================
// PREMIUM TOGGLE
// ===================================
if (premiumToggle && priceBox) {

    function togglePriceBox() {
        priceBox.style.display = premiumToggle.checked ? 'block' : 'none';
    }

    togglePriceBox();
    premiumToggle.addEventListener('change', togglePriceBox);
}

// ===================================
// WORD / CHARACTER COUNT
// ===================================
function updateWordCount() {

    const text = contentTextarea.value.trim();

    const khmerCharacters =
        (text.match(/[\u1780-\u17FF]/g) || []).length;

    const englishOnly = text.replace(/[\u1780-\u17FF]/g, ' ').trim();

    let englishWords = 0;

    if (englishOnly) {
        englishWords = englishOnly.split(/\s+/).filter(Boolean).length;
    }

    const countLabel = document.getElementById('countLabel');
    const contentCount = document.getElementById('contentCount');

    if (storyLanguage === 'khmer') {
        countLabel.innerText = 'Characters:';
        contentCount.innerText = khmerCharacters;
    } else {
        countLabel.innerText = 'Words:';
        contentCount.innerText = englishWords;
    }
}

contentTextarea.addEventListener('input', updateWordCount);
window.addEventListener('resize', updateWordCount);
updateWordCount();

// ===================================
// AUTOSAVE (LOCALSTORAGE)
// ===================================
const draftKeyPrefix = isEditing
    ? `chapter_edit_${storyId}_${chapterId}`
    : `chapter_create_${storyId}`;

const titleKey = `${draftKeyPrefix}_title`;
const contentKey = `${draftKeyPrefix}_content`;

function autoSaveDraft() {

    localStorage.setItem(titleKey, document.getElementById('chapterTitle').value);
    localStorage.setItem(contentKey, document.getElementById('chapterContent').value);

    autosaveStatus.innerText =
        'Draft autosaved at ' + new Date().toLocaleTimeString();
}

setInterval(autoSaveDraft, 5000);

document.getElementById('chapterTitle').addEventListener('input', autoSaveDraft);
document.getElementById('chapterContent').addEventListener('input', autoSaveDraft);

// ===================================
// LOAD DRAFT
// ===================================
window.addEventListener('DOMContentLoaded', () => {

    if (!isEditing) {

        const savedTitle = localStorage.getItem(titleKey);
        const savedContent = localStorage.getItem(contentKey);

        if (savedTitle) {
            document.getElementById('chapterTitle').value = savedTitle;
        }

        if (savedContent) {
            document.getElementById('chapterContent').value = savedContent;
        }
    }

    updateWordCount();
});

// ===================================
// VALIDATION MODAL
// ===================================
function showValidationModal(message) {
    validationMessage.innerText = message;
    validationModal.style.display = 'flex';
}

closeValidationModal.addEventListener('click', () => {
    validationModal.style.display = 'none';
});

validationModal.addEventListener('click', (e) => {
    if (e.target === validationModal) {
        validationModal.style.display = 'none';
    }
});

// ===================================
// FORM SUBMIT VALIDATION
// ===================================
document.getElementById('chapterForm').addEventListener('submit', function (e) {

    const text = contentTextarea.value.trim();

    const englishOnly = text.replace(/[\u1780-\u17FF]/g, ' ').trim();

    const englishWords = englishOnly
        ? englishOnly.split(/\s+/).length
        : 0;

    const khmerCharacters =
        (text.match(/[\u1780-\u17FF]/g) || []).length;

    const isPremium =
        document.getElementById('premiumToggle')?.checked;

    if (isPremium) {

        if (storyLanguage === 'english' && englishWords < 1500) {
            e.preventDefault();
            showValidationModal('Premium chapters require at least 1,500 words.');
            return;
        }

        if (storyLanguage === 'khmer' && khmerCharacters < 5000) {
            e.preventDefault();
            showValidationModal('Premium chapters require at least 5,000 Khmer characters.');
            return;
        }
    }

    // Clear draft on success
    localStorage.removeItem(titleKey);
    localStorage.removeItem(contentKey);
});

// ===================================
// SUCCESS MODAL CLOSE
// ===================================
if (successModal) {
    successModal.addEventListener('click', function (e) {
        if (e.target === successModal) {
            successModal.style.display = 'none';
        }
    });
}