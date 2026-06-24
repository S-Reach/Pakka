let rejectionData = [];
let confirmBtn;

document.addEventListener('DOMContentLoaded', function () {

    confirmBtn = document.getElementById('confirmRejectBtn');

    disableRejectButton(); // default state
});

/* ================= OPEN MODAL ================= */
function openRejectModal(chapterId)
{
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');

    form.action = "{{ url('/contentmoderation/reject') }}/" + chapterId;

    modal.style.display = 'flex';

    // reset data every open
    rejectionData = [];
    document.getElementById('commentsBox').innerHTML = '';
    document.getElementById('rejection_data').value = '';

    disableRejectButton(); // important reset
}

/* ================= CLOSE MODAL ================= */
function closeRejectModal()
{
    document.getElementById('rejectModal').style.display = 'none';
}

/* ================= ADD HIGHLIGHT COMMENT ================= */
function addHighlightComment()
{
    const selection = window.getSelection().toString().trim();

    if (!selection) {
        alert("Please highlight some text first.");
        return;
    }

    const comment = prompt("Why are you rejecting this part?");

    if (!comment) return;

    rejectionData.push({
        text: selection,
        comment: comment
    });

    renderComments();
    updateHiddenInput();
    updateButtonState();

    window.getSelection().removeAllRanges();
}

/* ================= RENDER COMMENTS ================= */
function renderComments()
{
    const box = document.getElementById('commentsBox');
    box.innerHTML = '';

    rejectionData.forEach((item, index) => {

        const div = document.createElement('div');
        div.style = "padding:10px; border:1px solid #ddd; border-radius:8px; background:#f9fafb;";

        div.innerHTML = `
            <strong style="color:#dc2626;">Highlighted Text:</strong>
            <p style="margin:5px 0;">${item.text}</p>

            <strong>Reason:</strong>
            <p style="margin:5px 0;">${item.comment}</p>

            <button type="button" onclick="removeComment(${index})"
                style="margin-top:5px; color:red; background:none; border:none; cursor:pointer;">
                Remove
            </button>
        `;

        box.appendChild(div);
    });
}

/* ================= REMOVE COMMENT ================= */
function removeComment(index)
{
    rejectionData.splice(index, 1);

    renderComments();
    updateHiddenInput();
    updateButtonState();
}

/* ================= UPDATE HIDDEN INPUT ================= */
function updateHiddenInput()
{
    document.getElementById('rejection_data').value =
        JSON.stringify(rejectionData);
}

/* ================= BUTTON STATE CONTROL ================= */
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
    confirmBtn.style.opacity = '1';
}

function disableRejectButton()
{
    confirmBtn.disabled = true;
    confirmBtn.style.background = '#d1d5db';
    confirmBtn.style.cursor = 'not-allowed';
    confirmBtn.style.opacity = '0.7';
}