<!DOCTYPE html>
<html>

<head>

    <title>Transactions</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">


<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            href="{{ route('admin.dashboard.page') }}"
            class="navbar-brand">

            Wallet Admin

        </a>

        <button
            onclick="logout()"
            class="btn btn-danger btn-sm">

            Logout

        </button>

    </div>

</nav>


<div class="container mt-5">

    <div class="d-flex justify-content-between mb-4">

        <h2>
            Transactions
        </h2>

        <a
            href="{{ route('admin.dashboard.page') }}"
            class="btn btn-secondary">

            Dashboard

        </a>

    </div>


    <div id="message"></div>


    <div class="card shadow">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Transaction ID</th>

                            <th>User</th>

                            <th>Type</th>

                            <th>Amount</th>

                            <th>Before</th>

                            <th>After</th>

                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody id="transactionTable">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>

let token = localStorage.getItem('token');


if (!token) {

    window.location.href = "/login";

}


async function getTransactions()
{

    let response = await fetch(
        '/api/admin/transactions',
        {
            method: 'GET',

            headers: {

                'Accept':
                    'application/json',

                'Authorization':
                    'Bearer ' + token

            }
        }
    );


    let data = await response.json();


    if (!response.ok) {

        document.getElementById(
            'message'
        ).innerHTML = `

            <div class="alert alert-danger">

                ${data.message}

            </div>

        `;

        return;
    }


    let transactions =
        data.data.data;


    let html = '';


    transactions.forEach(
        transaction => {

        let typeClass =
            transaction.type === 'credit'
            ? 'success'
            : 'danger';


        html += `

            <tr>

                <td>
                    ${transaction.id}
                </td>

                <td>
                    ${transaction.transaction_id}
                </td>

                <td>
                    ${transaction.wallet.user.name}
                </td>

                <td>

                    <span
                        class="badge bg-${typeClass}">

                        ${transaction.type.toUpperCase()}

                    </span>

                </td>

                <td>
                    ₹${Number(
                        transaction.amount
                    ).toFixed(2)}
                </td>

                <td>
                    ₹${Number(
                        transaction.balance_before
                    ).toFixed(2)}
                </td>

                <td>
                    ₹${Number(
                        transaction.balance_after
                    ).toFixed(2)}
                </td>

                <td>
                    ${transaction.created_at}
                </td>

            </tr>

        `;

    });


    document.getElementById(
        'transactionTable'
    ).innerHTML = html;

}


function logout()
{

    localStorage.removeItem('token');

    window.location.href = "/login";

}


getTransactions();

</script>

</body>
</html>