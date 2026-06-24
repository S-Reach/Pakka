document.addEventListener('DOMContentLoaded', function () {

    const APP = window.APP || {};

    /* =========================
        REPLY TOGGLE
    ========================= */
    window.toggleReply = function (id) {
        const form = document.getElementById('reply-form-' + id);
        if (!form) return;

        form.style.display = (form.style.display === 'none' || !form.style.display)
            ? 'block'
            : 'none';
    };


    /* =========================
        LIKE STORY
    ========================= */
    const likeBtn = document.getElementById('likeBtn');
    const likeText = document.getElementById('likeText');
    const likeCount = document.getElementById('likeCount');

    let liked = APP.liked || false;
    let likeLoading = false;

    function renderLike() {
        if (!likeBtn) return;

        if (liked) {
            likeText.innerText = likeText.dataset.liked;
            likeBtn.style.background = "#fee2e2";
        } else {
            likeText.innerText = likeText.dataset.like;
            likeBtn.style.background = "#f3f4f6";
        }
    }

    renderLike();

    if (likeBtn) {
        likeBtn.addEventListener('click', function () {

            if (likeLoading) return;
            likeLoading = true;

            fetch(`/story/${APP.storyId}/like`, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': APP.csrf,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {

                liked = data.liked;
                renderLike();

                if (likeCount) {
                    likeCount.innerText = data.likes;
                }

            })
            .finally(() => {
                likeLoading = false;
            });
        });
    }


    /* =========================
        FOLLOW USER
    ========================= */
    const followBtn = document.getElementById('followBtn');
    const followersCount = document.getElementById('followersCount');

    let following = APP.isFollowing || false;
    let followLoading = false;

    function renderFollow() {
        if (!followBtn) return;

        followBtn.innerText = following ? 'Unfollow' : 'Follow';
        followBtn.style.background = following ? '#fee2e2' : '#111827';
        followBtn.style.color = following ? '#111' : '#fff';
    }

    renderFollow();

    if (followBtn) {
        followBtn.addEventListener('click', function () {

            if (followLoading) return;
            followLoading = true;

            fetch(`/user/${APP.userId}/follow`, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': APP.csrf,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {

                following = data.following;
                renderFollow();

                if (followersCount) {
                    followersCount.innerText = data.followers;

                    followersCount.style.transform = "scale(1.2)";
                    setTimeout(() => {
                        followersCount.style.transform = "scale(1)";
                    }, 200);
                }

            })
            .finally(() => {
                followLoading = false;
            });
        });
    }


    /* =========================
        COMMENT LIKE
    ========================= */
    document.querySelectorAll('.like-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            const form = this.closest('.comment-like-form');
            if (!form) return;

            const commentId = form.dataset.id;

            const heart = this.querySelector('.heart');
            const count = this.querySelector('.like-count');

            fetch(`/comment/${commentId}/like`, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': APP.csrf,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {

                if (count) count.innerText = data.likes;

                if (heart) {
                    heart.innerText = data.liked ? "❤️" : "🤍";
                    heart.style.color = data.liked ? "red" : "#9ca3af";
                }

            });
        });
    });


    /* =========================
        VIEW COUNT
    ========================= */
    fetch(`/story/${APP.storyId}/view`, {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': APP.csrf,
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {

        const el = document.getElementById("viewCount");

        if (el) {
            el.innerText = data.views;

            el.style.transform = "scale(1.2)";
            setTimeout(() => el.style.transform = "scale(1)", 200);
        }
    });


    /* =========================
        SHARE POPUP
    ========================= */
    window.openSharePopup = function () {
        const el = document.getElementById('sharePopup');
        if (el) el.style.display = 'flex';
    };

    window.closeSharePopup = function () {
        const el = document.getElementById('sharePopup');
        if (el) el.style.display = 'none';
    };

    window.copyShareLink = function () {

        const input = document.getElementById('shareLink');
        if (!input) return;

        input.select();
        input.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(input.value);

        const btn = document.querySelector('.copy-btn');

        if (btn) {
            btn.innerHTML = '✓';

            setTimeout(() => {
                btn.innerHTML = '📋';
            }, 1500);
        }
    };


    const sharePopup = document.getElementById('sharePopup');
    if (sharePopup) {
        sharePopup.addEventListener('click', function (e) {
            if (e.target === this) {
                closeSharePopup();
            }
        });
    }


    /* =========================
        REPORT POPUP (STORY)
    ========================= */
    window.openReportPopup = function () {
        const el = document.getElementById('reportPopup');
        if (el) el.style.display = 'flex';
    };

    window.closeReportPopup = function () {
        const el = document.getElementById('reportPopup');
        if (el) el.style.display = 'none';
    };

    const reportPopup = document.getElementById('reportPopup');
    if (reportPopup) {
        reportPopup.addEventListener('click', function (e) {
            if (e.target === this) {
                closeReportPopup();
            }
        });
    }


    /* =========================
        COMMENT REPORT POPUP
    ========================= */
    window.openCommentReportPopup = function () {
        const el = document.getElementById('commentReportPopup');
        if (el) el.style.display = 'flex';
    };

    window.closeCommentReportPopup = function () {
        const el = document.getElementById('commentReportPopup');
        if (el) el.style.display = 'none';
    };

    const commentReportPopup = document.getElementById('commentReportPopup');
    if (commentReportPopup) {
        commentReportPopup.addEventListener('click', function (e) {
            if (e.target === this) {
                closeCommentReportPopup();
            }
        });
    }

});

// RATE STORY
let selectedRating = 0;

window.openRating = function () {
    const modal = document.getElementById('ratingModal');
    if (modal) modal.style.display = 'flex';
};

window.closeRating = function () {
    const modal = document.getElementById('ratingModal');
    if (modal) modal.style.display = 'none';
};

window.submitRating = function () {
    if (!selectedRating) {
        alert("Please select a rating first");
        return;
    }

    fetch(`/story/${APP.storyId}/rate`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": APP.csrf,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            rating: selectedRating
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || "Rating submitted!");

        const ratingValue = document.getElementById("ratingValue");
        if (ratingValue) {
            ratingValue.innerText = "⭐ " + data.rating;
        }

        closeRating();
    });
};

/* STAR CLICK */
document.querySelectorAll("#stars span").forEach(star => {
    star.addEventListener("click", function () {
        selectedRating = this.dataset.value;

        document.querySelectorAll("#stars span").forEach(s => {
            s.style.color = "#d1d5db";
        });

        for (let i = 0; i < selectedRating; i++) {
            document.querySelectorAll("#stars span")[i].style.color = "#fbbf24";
        }
    });
});