function showTab(tab, button)
{
    document.getElementById('earnings-tab').style.display = 'none';
    document.getElementById('withdraws-tab').style.display = 'none';

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    button.classList.add('active');

    if(tab === 'earnings')
    {
        document.getElementById('earnings-tab').style.display = 'block';
    }
    else
    {
        document.getElementById('withdraws-tab').style.display = 'block';
    }
}

function openWithdrawModal(
    id,
    writer,
    amount,
    bank_name,
    account_number,
    account_holder_name,
    date,
    status
)
{
    document.getElementById('modal-id').innerText = '#' + id;
    document.getElementById('modal-writer').innerText = writer;
    document.getElementById('modal-amount').innerText = '$' + amount;
    document.getElementById('modal-bank-name').innerText = bank_name;
    document.getElementById('modal-account-number').innerText = account_number;
    document.getElementById('modal-account-holder-name').innerText = account_holder_name;
    document.getElementById('modal-date').innerText = date;
    document.getElementById('modal-status').innerText = status;

    document.getElementById('modal-paid-btn').href =
        '/withdraw/' + id + '/paid';

    if(status !== 'Pending')
    {
        document.getElementById('modal-paid-btn').style.display = 'none';
    }
    else
    {
        document.getElementById('modal-paid-btn').style.display = 'inline-block';
    }

    document.getElementById('withdrawModal').style.display = 'flex';
}

function closeWithdrawModal()
{
    document.getElementById('withdrawModal').style.display = 'none';
}
