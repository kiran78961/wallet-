<!DOCTYPE html>
<html>

<head>

    <title>Admin Users</title>

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
            All Users
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

                            <th>Name</th>

                            <th>Email</th>

                            <th>Balance</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody id="userTable">

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


async function getUsers()
{

    let response = await fetch(
        '/api/admin/users',
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


    if (response.status === 401) {

        localStorage.removeItem('token');

        window.location.href = "/login";

        return;
    }


    if (!data.status) {

        document.getElementById(
            'message'
        ).innerHTML = `

            <div class="alert alert-danger">

                ${data.message}

            </div>

        `;

        return;
    }


    let html = '';


    data.data.forEach(user => {

        let balance =
            user.wallet
                ? user.wallet.balance
                : 0;


        html += `

            <tr>

                <td>${user.id}</td>

                <td>${user.name}</td>

                <td>${user.email}</td>

                <td>₹${Number(balance).toFixed(2)}</td>

                <td>

                    <a
                        href="/admin/user/${user.id}/wallet"
                        class="btn btn-primary btn-sm">

                        Manage Wallet

                    </a>

                </td>

            </tr>

        `;

    });


    document.getElementById(
        'userTable'
    ).innerHTML = html;

}


function logout()
{

    localStorage.removeItem('token');

    window.location.href = "/login";

}


getUsers();

</script>

</body>
</html>