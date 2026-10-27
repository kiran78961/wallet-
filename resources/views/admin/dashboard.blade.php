<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">


<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <span class="navbar-brand">
            Wallet Admin
        </span>

        <button
            onclick="logout()"
            class="btn btn-danger btn-sm">

            Logout

        </button>

    </div>

</nav>


<div class="container mt-5">

    <h2 class="mb-4">

        Admin Dashboard

    </h2>


    <div id="message"></div>


    <div class="row g-4">


        <!-- USERS -->

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>Total Users</h5>

                    <h2 id="totalUsers">
                        0
                    </h2>

                    <a
                        href="{{ route('admin.users.page') }}"
                        class="btn btn-primary">

                        View Users

                    </a>

                </div>

            </div>

        </div>


        <!-- BALANCE -->

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>Total Wallet Balance</h5>

                    <h2 id="totalBalance">
                        ₹0.00
                    </h2>

                </div>

            </div>

        </div>


        <!-- TRANSACTIONS -->

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>Total Transactions</h5>

                    <h2 id="totalTransactions">
                        0
                    </h2>

                    <a
                        href="{{ route('admin.transactions.page') }}"
                        class="btn btn-dark">

                        Transactions

                    </a>

                </div>

            </div>

        </div>


        <!-- CREDIT -->

        <div class="col-md-6">

            <div class="card shadow border-success">

                <div class="card-body">

                    <h5 class="text-success">

                        Total Credit

                    </h5>

                    <h2
                        id="totalCredit"
                        class="text-success">

                        ₹0.00

                    </h2>

                </div>

            </div>

        </div>


        <!-- DEBIT -->

        <div class="col-md-6">

            <div class="card shadow border-danger">

                <div class="card-body">

                    <h5 class="text-danger">

                        Total Debit

                    </h5>

                    <h2
                        id="totalDebit"
                        class="text-danger">

                        ₹0.00

                    </h2>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

let token = localStorage.getItem('token');


// Check token

if (!token) {

    window.location.href = "/login";

}


// Get Dashboard

async function getDashboard()
{

    try {

        let response = await fetch(
            '/api/admin/dashboard',
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',
                    'Authorization':
                        'Bearer ' + token
                }
            }
        );


        let data = await response.json();


        if (!response.ok) {

            if (response.status === 401) {

                localStorage.removeItem('token');

                window.location.href = "/login";

                return;
            }

            throw new Error(data.message);
        }


        if (data.status) {

            document.getElementById(
                'totalUsers'
            ).innerText =
                data.data.total_users;


            document.getElementById(
                'totalBalance'
            ).innerText =
                '₹' +
                Number(
                    data.data.total_wallet_balance
                ).toFixed(2);


            document.getElementById(
                'totalCredit'
            ).innerText =
                '₹' +
                Number(
                    data.data.total_credit
                ).toFixed(2);


            document.getElementById(
                'totalDebit'
            ).innerText =
                '₹' +
                Number(
                    data.data.total_debit
                ).toFixed(2);


            document.getElementById(
                'totalTransactions'
            ).innerText =
                data.data.total_transactions;

        }

    } catch (error) {

        document.getElementById(
            'message'
        ).innerHTML = `

            <div class="alert alert-danger">

                ${error.message}

            </div>

        `;

    }

}


// Logout

function logout()
{

    localStorage.removeItem('token');

    window.location.href = "/login";

}


// Load dashboard

getDashboard();

</script>

</body>
</html>