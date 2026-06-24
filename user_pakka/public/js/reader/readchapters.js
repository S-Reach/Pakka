function openChapterReportModal() {

    document.getElementById('chapterReportModal').style.display = 'flex';
}

function closeChapterReportModal() {

    document.getElementById('chapterReportModal').style.display = 'none';
}

window.onclick = function(e){

    let modal = document.getElementById('chapterReportModal');

    if(e.target === modal){

        closeChapterReportModal();
    }
}