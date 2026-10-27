<!DOCTYPE html>
<html>

<head>

    <title>Manage Wallet</title>

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


    <a
        href="{{ route('admin.users.page') }}"
        class="btn btn-secondary mb-4">

        ← Users

    </a>


    <div id="message"></div>


    <!-- USER DETAILS -->

    <div class="card shadow mb-4">

        <div class="card-body">

            <h3 id="userName">
                Loading...
            </h3>

            <p id="userEmail"></p>

            <hr>

            <h2
                id="balance"
                class="text-primary">

                ₹0.00

            </h2>

        </div>

    </div>


    <div class="row">


        <!-- ADD MONEY -->

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body">

                    <h4 class="text-success">

                        Add Money

                    </h4>

                    <form
                        onsubmit="addMoney(event)">

                        <div class="mb-3">

                            <label>
                                Amount
                            </label>

                            <input
                                type="number"
                                id="addAmount"
                                class="form-control"
                                min="0.01"
                                max="5000"
                                step="0.01"
                                required>

                        </div>

                        <button
                            class="btn btn-success">

                            Add Money

                        </button>

                    </form>

                </div>

            </div>

        </div>


        <!-- DEBIT MONEY -->

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body">

                    <h4 class="text-danger">

                        Debit Money

                    </h4>

                    <form
                        onsubmit="debitMoney(event)">

                        <div class="mb-3">

                            <label>
                                Amount
                            </label>

                            <input
                                type="number"
                                id="debitAmount"
                                class="form-control"
                                min="0.01"
                                max="5000"
                                step="0.01"
                                required>

                        </div>

                        <button
                            class="btn btn-danger">

                            Debit Money

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

let token = localStorage.getItem('token');

let userId =
    window.location.pathname.split('/')[3];


if (!token) {

    window.location.href = "/login";

}


// GET WALLET

async function getWallet()
{

    let response = await fetch(
        `/api/admin/user/${userId}/wallet`,
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

        showMessage(data.message, 'danger');

        return;
    }


    document.getElementById(
        'userName'
    ).innerText =
        data.data.name;


    document.getElementById(
        'userEmail'
    ).innerText =
        data.data.email;


    document.getElementById(
        'balance'
    ).innerText =
        '₹' +
        Number(
            data.data.balance
        ).toFixed(2);

}


// ADD MONEY

async function addMoney(event)
{

    event.preventDefault();


    let amount =
        document.getElementById(
            'addAmount'
        ).value;


    let response = await fetch(
        `/api/admin/user/${userId}/add-money`,
        {
            method: 'POST',

            headers: {

                'Accept':
                    'application/json',

                'Content-Type':
                    'application/json',

                'Authorization':
                    'Bearer ' + token

            },

            body: JSON.stringify({
                amount: amount
            })

        }
    );


    let data = await response.json();


    if (data.status) {

        showMessage(
            data.message,
            'success'
        );

        document.getElementById(
            'addAmount'
        ).value = '';

        getWallet();

    } else {

        showMessage(
            data.message,
            'danger'
        );

    }

}


// DEBIT MONEY

async function debitMoney(event)
{

    event.preventDefault();


    let amount =
        document.getElementById(
            'debitAmount'
        ).value;


    let response = await fetch(
        `/api/admin/user/${userId}/debit-money`,
        {
            method: 'POST',

            headers: {

                'Accept':
                    'application/json',

                'Content-Type':
                    'application/json',

                'Authorization':
                    'Bearer ' + token

            },

            body: JSON.stringify({
                amount: amount
            })

        }
    );


    let data = await response.json();


    if (data.status) {

        showMessage(
            data.message,
            'success'
        );

        document.getElementById(
            'debitAmount'
        ).value = '';

        getWallet();

    } else {

        showMessage(
            data.message,
            'danger'
        );

    }

}


// MESSAGE

function showMessage(
    message,
    type
)
{

    document.getElementById(
        'message'
    ).innerHTML = `

        <div class="alert alert-${type}">

            ${message}

        </div>

    `;

}


// LOGOUT

function logout()
{

    localStorage.removeItem('token');

    window.location.href = "/login";

}


getWallet();

</script>

</body>
</html>