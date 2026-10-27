<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transaction History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Transaction History</h4>
        </div>

        <div class="card-body">

            <div id="message"></div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Transaction ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance Before</th>
                            <th>Balance After</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody id="transactionTable">

                        <tr>
                            <td colspan="7" class="text-center">
                                Loading...
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>

    // Get token from localStorage
    let token = localStorage.getItem('token');

    // If token not available
    if (!token) {
        window.location.href = "/login";
    }


    async function getTransactions() {

        try {

            let response = await fetch('/api/transactions', {

                method: 'POST',

                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token
                }

            });


            let data = await response.json();

            console.log(data);


            if (data.status) {

                let table = document.getElementById('transactionTable');

                table.innerHTML = '';


                if (data.data.length === 0) {

                    table.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center">
                                No transactions found
                            </td>
                        </tr>
                    `;

                    return;
                }


                data.data.forEach((transaction, index) => {

                    let typeClass =
                        transaction.type === 'credit'
                        ? 'success'
                        : 'danger';


                    table.innerHTML += `

                        <tr>

                            <td>${index + 1}</td>

                            <td>
                                ${transaction.transaction_id}
                            </td>

                            <td>
                                <span class="badge bg-${typeClass}">
                                    ${transaction.type}
                                </span>
                            </td>

                            <td>
                                ₹${transaction.amount}
                            </td>

                            <td>
                                ₹${transaction.balance_before}
                            </td>

                            <td>
                                ₹${transaction.balance_after}
                            </td>

                            <td>
                                ${transaction.created_at}
                            </td>

                        </tr>

                    `;

                });

            } else {

                document.getElementById('message').innerHTML = `

                    <div class="alert alert-danger">
                        ${data.message}
                    </div>

                `;

            }


        } catch (error) {

            console.error(error);

            document.getElementById('message').innerHTML = `

                <div class="alert alert-danger">
                    Something went wrong.
                </div>

            `;

        }

    }


    // Call API
    getTransactions();

</script>

</body>
</html>