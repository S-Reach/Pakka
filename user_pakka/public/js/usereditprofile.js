// Preview Avatar

document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('avatarPreview').src = URL.createObjectURL(file);
    }
});

// FOLLOW
document.addEventListener('DOMContentLoaded', function () {

    const followersCount = document.getElementById('followersCount');

    function updateFollowers(value) {
        followersCount.innerText = value;
    }

    // Example: after follow API response
    // updateFollowers(data.followers);

});