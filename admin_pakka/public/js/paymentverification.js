
function openPaymentModal(data)
{
    document.getElementById('paymentModal').style.display = 'block';

    document.getElementById('m_ref').innerText =
        data.transaction_id;

    document.getElementById('m_date').innerText =
        data.date;

    document.getElementById('m_reader').innerText =
        data.reader_name;

    document.getElementById('m_email').innerText =
        data.reader_email;

    document.getElementById('m_writer').innerText =
        data.writer_name;

    document.getElementById('m_story').innerText =
        data.story_title;

    document.getElementById('m_chapter').innerText =
        data.chapter_title;

    document.getElementById('m_amount').innerText =
        data.amount;
}

function closePaymentModal()
{
    document.getElementById('paymentModal').style.display = 'none';
}

window.onclick = function(e)
{
    let modal = document.getElementById('paymentModal');

    if(e.target === modal)
    {
        modal.style.display = 'none';
    }
}
