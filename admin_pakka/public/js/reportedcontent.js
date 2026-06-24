function openModal(report)
{
    console.log('REPORT:', report);

    document.getElementById('reportModal').style.display = 'block';

    // ===== TEXT FIELDS =====
    document.getElementById('m_id').innerText = report.id ?? '-';
    document.getElementById('m_title').innerText = report.title ?? '-';
    document.getElementById('m_author').innerText = report.author ?? '-';
    document.getElementById('m_type').innerText = report.type ?? '-';
    document.getElementById('m_language').innerText = report.language ?? '-';
    document.getElementById('m_payment').innerText = report.payment_status ?? '-';
    document.getElementById('m_reported_by_list').innerText = report.reported_by_list ?? '-';
    document.getElementById('m_report_reason').innerText = report.report_reason ?? '-';
    document.getElementById('m_details').innerText = report.details ?? '-';
    document.getElementById('m_date').innerText = report.created_at ?? '-';
    document.getElementById('m_preview').innerText = report.preview ?? '';

    // ===== CHAPTER =====
    const chapterBox = document.getElementById('m_chapter_title').parentElement;

    if (report.type === 'chapter') {
        document.getElementById('m_chapter_title').innerText = report.chapter_title ?? '-';
        chapterBox.style.display = 'block';
    } else {
        chapterBox.style.display = 'none';
    }

    // ===== FORMS =====
    const safeForm = document.getElementById('safeForm');
    const notifyForm = document.getElementById('notifyForm');
    const hideForm = document.getElementById('hideForm');
    const unhideForm = document.getElementById('unhideForm');

    // SAFE
    safeForm.action = report.update_route;

    // HIDE / UNHIDE
    hideForm.action = report.hide_route;
    unhideForm.action = report.unhide_route;

    // ===== REVISION RULE =====
    if (report.type === 'story' || report.type === 'chapter') {
        notifyForm.style.display = 'block';
        notifyForm.action = report.notify_route;
    } else {
        notifyForm.style.display = 'none';
    }

    // ===== HIDE / UNHIDE RULE =====
    const isHidden = report.is_hidden == 1;

    if (isHidden) {
        hideForm.style.display = 'none';
        unhideForm.style.display = 'block';
    } else {
        hideForm.style.display = 'block';
        unhideForm.style.display = 'none';
    }
}

function closeModal()
{
    document.getElementById('reportModal').style.display = 'none';
}

window.onclick = function(event)
{
    const modal = document.getElementById('reportModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}