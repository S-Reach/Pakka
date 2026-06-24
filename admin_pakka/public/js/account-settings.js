const editTab = document.getElementById('editTab');
const passTab = document.getElementById('passTab');

const editSection = document.getElementById('editSection');
const passSection = document.getElementById('passSection');

editTab.addEventListener('click', () => {
    editTab.classList.add('active');
    editTab.classList.remove('inactive');

    passTab.classList.remove('active');
    passTab.classList.add('inactive');

    editSection.classList.remove('d-none');
    passSection.classList.add('d-none');
});

passTab.addEventListener('click', () => {
    passTab.classList.add('active');
    passTab.classList.remove('inactive');

    editTab.classList.remove('active');
    editTab.classList.add('inactive');

    passSection.classList.remove('d-none');
    editSection.classList.add('d-none');
});
