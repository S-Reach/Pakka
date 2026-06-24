document.querySelectorAll('.action-btn').forEach(button => {
    button.addEventListener('click', async function () {

        const userId = this.dataset.id;
        const action = this.dataset.action;

        if (!confirm(`Are you sure you want to ${action} this user?`)) return;

        try {
            const response = await fetch(`/user/${action}/${userId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }

            alert(data.message);
            location.reload();

        } catch (error) {
            console.error(error);
            alert(error.message || 'Something went wrong');
        }

    });
});