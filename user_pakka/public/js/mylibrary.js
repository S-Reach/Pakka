function showTab(tab, element)
{
    // remove active from all tabs
    document.querySelectorAll('.tab').forEach(tabBtn => {
        tabBtn.classList.remove('active');
    });

    // add active to clicked tab
    element.classList.add('active');

    // hide all content
    document.getElementById('saved-tab').style.display = 'none';
    document.getElementById('history-tab').style.display = 'none';

    // show selected tab
    document.getElementById(tab + '-tab').style.display = 'block';
}
