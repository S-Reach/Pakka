
const tabButtons = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {

        tabButtons.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));

        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

//Follow/Unfollow Script 
document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('followBtn');
    const countEl = document.getElementById('followersCount');

    if (!btn) return;

    let following = btn.dataset.following === 'true';
    let loading = false;

    function render(){
        btn.innerText = following ? 'Unfollow' : 'Follow';
        btn.style.background = following ? '#fee2e2' : '#111827';
        btn.style.color = following ? '#b91c1c' : '#fff';
    }

    render();

    btn.addEventListener('click', async () => {

        if (loading) return;
        loading = true;

        try {
            let res = await fetch("{{ route('user.follow', $user->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            let data = await res.json();

            following = data.following;

            if (countEl) {
                countEl.innerText = data.followersCount;
            }

            render();

        } catch (err) {
            console.error(err);
        }

        loading = false;
    });

});