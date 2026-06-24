
// POPUP
function showPopup(message, type='success')
{
    const popup = document.getElementById('popup');
    popup.innerText = message;
    popup.className = 'popup ' + type;
    popup.style.display = 'block';

    setTimeout(() => {
        popup.style.display = 'none';
    }, 3000);
}

// FLASH MESSAGE
document.addEventListener('DOMContentLoaded', function()
{
    const success = document.getElementById('flash-success');
    const error = document.getElementById('flash-error');

    if(success){
        showPopup(success.dataset.message, 'success');
    }

    if(error){
        showPopup(error.dataset.message, 'error');
    }

    applyStyles();
    updateSize(document.getElementById('size').value);
});

// SELECT OPTION
function selectOption(type, value)
{
    document.getElementById(type).value = value;

    document.querySelectorAll('.' + type + '-card')
        .forEach(card => card.classList.remove('active'));

    document.getElementById(type + '-' + value)
        .classList.add('active');

    applyStyles();
}

// UPDATE SIZE (slider)
function updateSize(value)
{
    document.getElementById('sizeLabel').innerText = value + 'px';

    let preview = document.getElementById('preview');
    let previewKhmer = document.getElementById('preview-khmer');

    if(preview){
        preview.style.fontSize = value + 'px';
    }

    if(previewKhmer){
        previewKhmer.style.fontSize = value + 'px';
    }
}

// ✅ FIXED FUNCTION (buttons)
function changeSize(step)
{
    let sizeInput = document.getElementById('size');
    let current = parseInt(sizeInput.value);

    let newSize = current + step;

    if(newSize < 12) newSize = 12;
    if(newSize > 30) newSize = 30;

    sizeInput.value = newSize;

    updateSize(newSize);
}

// APPLY STYLES
function applyStyles()
{
    let theme = document.getElementById('theme').value;

    let bg = '#fff';
    let color = '#000';

    if(theme === 'dark'){
        bg = '#111';
        color = '#fff';
    }
    else if(theme === 'sepia'){
        bg = '#f4ecd8';
        color = '#000';
    }

    let preview = document.getElementById('preview');
    let previewKhmer = document.getElementById('preview-khmer');

    // English Preview
    if(preview)
    {
        preview.style.background = bg;
        preview.style.color = color;

        let fontInput = document.getElementById('font');

        if(fontInput)
        {
            let font = fontInput.value;

            let englishFont = "'Segoe UI', Arial, sans-serif";

            if(font === 'serif'){
                englishFont = "Georgia, serif";
            }
            else if(font === 'mono'){
                englishFont = "'Courier New', monospace";
            }

            preview.style.fontFamily = englishFont;
        }
    }

    // Khmer Preview
    if(previewKhmer)
    {
        previewKhmer.style.background = bg;
        previewKhmer.style.color = color;

        let khmerInput = document.getElementById('khmer_font');

        if(khmerInput)
        {
            let khmerFont = khmerInput.value;

            let khmerFontFamily = "'Battambang'";

            if(khmerFont === 'kantumruy'){
                khmerFontFamily = "'Kantumruy Pro'";
            }
            else if(khmerFont === 'noto'){
                khmerFontFamily = "'Noto Sans Khmer'";
            }

            previewKhmer.style.fontFamily = khmerFontFamily;
        }
    }
}

// INIT
document.addEventListener('DOMContentLoaded', function()
{
    applyStyles();
    updateSize(document.getElementById('size').value);
});
