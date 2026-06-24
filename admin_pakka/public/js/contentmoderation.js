let rejectionData = [];
let confirmBtn;

document.addEventListener('DOMContentLoaded', function () {
    confirmBtn = document.getElementById('confirmRejectBtn');
    disableRejectButton();
});

function openRejectModal(chapterId, content)
{
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');

    form.action = "/contentmoderation/" + chapterId + "/reject";

    document.getElementById('contentArea').innerText = content;

    modal.style.display = 'flex';

    rejectionData = [];
    document.getElementById('commentsBox').innerHTML = '';
    document.getElementById('rejection_data').value = '';

    disableRejectButton();
}

function closeRejectModal()
{
    document.getElementById('rejectModal').style.display = 'none';
}

function addHighlightComment()
{
    const selection = window.getSelection().toString().trim();

    if (!selection) {
        alert("Please highlight text first.");
        return;
    }

    const comment = prompt("Why reject this?");
    if (!comment) return;

    rejectionData.push({ text: selection, comment });

    renderComments();
    updateHiddenInput();
    updateButtonState();

    window.getSelection().removeAllRanges();
}

function renderComments()
{
    const box = document.getElementById('commentsBox');
    box.innerHTML = '';

    rejectionData.forEach((item, index) => {
        box.innerHTML += `
            <div style="padding:10px;border:1px solid #ddd;border-radius:8px;background:#f9fafb;">
                <b style="color:red;">Text:</b>
                <p>${item.text}</p>

                <b>Reason:</b>
                <p>${item.comment}</p>

                <button type="button" onclick="removeComment(${index})" style="color:red;border:none;background:none;cursor:pointer;">
                    Remove
                </button>
            </div>
        `;
    });
}

function removeComment(index)
{
    rejectionData.splice(index, 1);
    renderComments();
    updateHiddenInput();
    updateButtonState();
}

function updateHiddenInput()
{
    document.getElementById('rejection_data').value =
        JSON.stringify(rejectionData);
}

function updateButtonState()
{
    if (rejectionData.length > 0) {
        enableRejectButton();
    } else {
        disableRejectButton();
    }
}

function enableRejectButton()
{
    confirmBtn.disabled = false;
    confirmBtn.style.background = '#ef4444';
    confirmBtn.style.cursor = 'pointer';
}

function disableRejectButton()
{
    confirmBtn.disabled = true;
    confirmBtn.style.background = '#d1d5db';
    confirmBtn.style.cursor = 'not-allowed';
}

function closeRejectModal()
{
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejection_reason').value = '';
    disableRejectButton();
}

const textarea = document.getElementById('rejection_reason');
const confirmBtn = document.getElementById('confirmRejectBtn');

textarea.addEventListener('input', function () {

    if (textarea.value.trim().length > 0) {
        confirmBtn.disabled = false;
        confirmBtn.style.background = '#ef4444';
        confirmBtn.style.cursor = 'pointer';
    } else {
        disableRejectButton();
    }

});

function disableRejectButton()
{
    confirmBtn.disabled = true;
    confirmBtn.style.background = '#d1d5db';
    confirmBtn.style.cursor = 'not-allowed';
}

