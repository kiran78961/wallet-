
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wallet Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body class="bg-light">


<!-- ================================================= -->
<!-- NAVBAR -->
<!-- ================================================= -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand">
           
        </a>

        <button
            class="btn btn-danger"
            onclick="logout()">

            Logout

        </button>


        

    </div>

</nav>



<!-- ================================================= -->
<!-- MAIN CONTAINER -->
<!-- ================================================= -->

<div class="container mt-5">


    <!-- ================================================= -->
    <!-- DASHBOARD CARDS -->
    <!-- ================================================= -->

    <div class="row g-4">


        <!-- ================= USER CARD ================= -->

        <div class="col-md-4">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        Welcome
                    </h5>

                    <h3 id="userName">
                        User
                    </h3>

                    <p id="userEmail"
                       class="text-muted">
                    </p>

                </div>

            </div>

        </div>



        <!-- ================= BALANCE CARD ================= -->

        <div class="col-md-4">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        Wallet Balance
                    </h5>

                    <h2 class="text-success">

                        ₹ <span id="balance">
                            0.00
                        </span>

                    </h2>


                    <button
                        class="btn btn-primary"
                        onclick="getBalance()">

                        Check Balance

                    </button>

                </div>

            </div>

        </div>



        <!-- ================= ADD MONEY CARD ================= -->

        <div class="col-md-4">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        Add Money
                    </h5>

                    <p class="text-muted">
                        Add money to your wallet
                    </p>


                    <a
                        href="/add-money"
                        class="btn btn-success">

                        Add Money

                    </a>

                </div>

            </div>

        </div>



        <!-- ================= DEBIT MONEY CARD ================= -->

        <div class="col-md-4">

            <div class="card shadow h-100">

                <div class="card-body">


                    <h5 class="text-center mb-3">

                        Debit Money

                    </h5>


                    <!-- Debit Message -->

                    <div id="debitMessage"></div>



                    <!-- Amount -->

                    <div class="mb-3">

                        <label class="form-label">

                            Enter Amount

                        </label>


                        <input
                            type="number"
                            id="debitAmount"
                            class="form-control"
                            placeholder="Enter amount"
                            min="0.01"
                            max="5000"
                            step="0.01">

                    </div>



                    <!-- Debit Button -->

                    <button
                        type="button"
                        class="btn btn-danger w-100"
                        onclick="debitMoney()">

                        Debit Money

                    </button>


                    <small class="text-muted d-block mt-2">

                        Maximum debit amount: ₹5000

                    </small>


                </div>

            </div>

        </div>



    </div>



    <!-- ================================================= -->
    <!-- TRANSACTION HISTORY -->
    <!-- ================================================= -->

    <div class="row mt-4">

        <div class="col-md-12">

            <div class="card shadow">


                <!-- Header -->

                <div class="card-header bg-primary text-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            Transaction History

                        </h5>


                        <button
                            class="btn btn-light btn-sm"
                            onclick="getTransactions()">

                            Refresh

                        </button>

                    </div>

                </div>



                <!-- Body -->

                <div class="card-body">


                    <div id="transactionMessage"></div>


                    <div class="table-responsive">


                        <table
                            class="table table-bordered table-hover align-middle">


                            <!-- Table Header -->

                            <thead class="table-dark">

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Transaction ID
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Amount
                                    </th>

                                    <th>
                                        Balance Before
                                    </th>

                                    <th>
                                        Balance After
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                </tr>

                            </thead>



                            <!-- Table Body -->

                            <tbody id="transactionTable">


                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center">

                                        Loading...

                                    </td>

                                </tr>


                            </tbody>


                        </table>

                        


                    </div>


                </div>

            </div>

        </div>

    </div>



</div>



<!-- ================================================= -->
<!-- JAVASCRIPT -->
<!-- ================================================= -->

<script>


// =====================================================
// GET TOKEN
// =====================================================

let token = localStorage.getItem('token');


// =====================================================
// CHECK TOKEN
// =====================================================

if (!token) {

    window.location.href = "/login";

}



// =====================================================
// GET WALLET BALANCE
// =====================================================

async function getBalance() {


    try {


        let response = await fetch('/api/balance', {

            method: 'POST',

            headers: {

                'Accept': 'application/json',

                'Authorization': 'Bearer ' + token

            }

        });


        let data = await response.json();


        console.log("Balance Response:", data);



        // SUCCESS

        if (data.status) {


            document.getElementById('balance').innerText =
                parseFloat(data.data.balance).toFixed(2);


        }


        // ERROR

        else {


            alert(data.message);

        }


    }

    catch (error) {


        console.error(error);


        alert("Unable to fetch wallet balance");


    }

}



// =====================================================
// DEBIT MONEY
// =====================================================

async function debitMoney() {


    // Get amount

    let amount =
        document.getElementById('debitAmount').value;


    // Message element

    let message =
        document.getElementById('debitMessage');



    // Clear previous message

    message.innerHTML = "";



    // =================================================
    // CHECK EMPTY
    // =================================================

    if (amount === "") {


        message.innerHTML = `

            <div class="alert alert-danger">

                Please enter amount.

            </div>

        `;


        return;

    }



    // Convert amount to number

    amount = parseFloat(amount);



    // =================================================
    // CHECK ZERO / NEGATIVE
    // =================================================

    if (amount <= 0) {


        message.innerHTML = `

            <div class="alert alert-danger">

                Amount must be greater than 0.

            </div>

        `;


        return;

    }



    // =================================================
    // CHECK MAXIMUM 5000
    // =================================================

    if (amount > 5000) {


        message.innerHTML = `

            <div class="alert alert-danger">

                Maximum debit amount is ₹5000.

            </div>

        `;


        return;

    }



    try {


        // Disable button

        let button =
            document.querySelector(
                'button[onclick="debitMoney()"]'
            );


        button.disabled = true;

        button.innerText = "Processing...";



        // =================================================
        // CALL DEBIT API
        // =================================================

        let response = await fetch('/api/debitMoney', {

            method: 'POST',

            headers: {

                'Accept': 'application/json',

                'Content-Type': 'application/json',

                'Authorization': 'Bearer ' + token

            },

            body: JSON.stringify({

                amount: amount

            })

        });



        let data = await response.json();



        console.log("Debit Response:", data);



        // =================================================
        // SUCCESS
        // =================================================

        if (data.status) {


            message.innerHTML = `

                <div class="alert alert-success">

                    <strong>
                        ${data.message}
                    </strong>

                    <hr>

                    <div>

                        <strong>
                            Transaction ID:
                        </strong>

                        ${data.data.transaction_id}

                    </div>

                    <div>

                        <strong>
                            Type:
                        </strong>

                        ${data.data.type}

                    </div>

                    <div>

                        <strong>
                            Amount:
                        </strong>

                        ₹${data.data.amount}

                    </div>

                    <div>

                        <strong>
                            Balance Before:
                        </strong>

                        ₹${data.data.balance_before}

                    </div>

                    <div>

                        <strong>
                            Balance After:
                        </strong>

                        ₹${data.data.balance_after}

                    </div>

                </div>

            `;



            // =================================================
            // CLEAR INPUT
            // =================================================

            document.getElementById('debitAmount').value = "";



            // =================================================
            // UPDATE BALANCE
            // =================================================

            document.getElementById('balance').innerText =
                parseFloat(
                    data.data.balance_after
                ).toFixed(2);



            // =================================================
            // REFRESH TRANSACTIONS
            // =================================================

            getTransactions();


        }


        // =================================================
        // ERROR
        // =================================================

        else {


            let errorMessage = data.message;


            // Insufficient balance

            if (data.balance !== undefined) {

                errorMessage +=
                    "<br>Available Balance: ₹" +
                    data.balance;

            }


            message.innerHTML = `

                <div class="alert alert-danger">

                    ${errorMessage}

                </div>

            `;

        }


    }

    catch (error) {


        console.error(error);


        message.innerHTML = `

            <div class="alert alert-danger">

                Something went wrong while debiting money.

            </div>

        `;


    }

    finally {


        // Enable button again

        let button =
            document.querySelector(
                'button[onclick="debitMoney()"]'
            );


        button.disabled = false;

        button.innerText = "Debit Money";


    }

}



// =====================================================
// GET TRANSACTION HISTORY
// =====================================================

async function getTransactions() {


    try {


        let table =
            document.getElementById(
                'transactionTable'
            );


        // Loading

        table.innerHTML = `

            <tr>

                <td
                    colspan="7"
                    class="text-center">

                    Loading transactions...

                </td>

            </tr>

        `;



        // =================================================
        // CALL TRANSACTION API
        // =================================================

        let response =
            await fetch('/api/transactions', {

                method: 'POST',

                headers: {

                    'Accept': 'application/json',

                    'Authorization':
                        'Bearer ' + token

                }

            });



        let data =
            await response.json();



        console.log(
            "Transaction Response:",
            data
        );



        // =================================================
        // SUCCESS
        // =================================================

        if (data.status) {


            table.innerHTML = "";



            // =================================================
            // NO TRANSACTIONS
            // =================================================

            if (
                !data.data ||
                data.data.length === 0
            ) {


                table.innerHTML = `

                    <tr>

                        <td
                            colspan="7"
                            class="text-center text-muted">

                            No transactions found.

                        </td>

                    </tr>

                `;


                return;

            }



            // =================================================
            // LOOP TRANSACTIONS
            // =================================================

            data.data.forEach(
                (transaction, index) => {


                    // Type badge

                    let typeBadge = "";



                    if (
                        transaction.type ===
                        "credit"
                    ) {


                        typeBadge = `

                            <span
                                class="badge bg-success">

                                Credit

                            </span>

                        `;

                    }

                    else {


                        typeBadge = `

                            <span
                                class="badge bg-danger">

                                Debit

                            </span>

                        `;

                    }



                    // Add row

                    table.innerHTML += `

                        <tr>

                            <td>
                                ${index + 1}
                            </td>


                            <td>
                                ${transaction.transaction_id}
                            </td>


                            <td>
                                ${typeBadge}
                            </td>


                            <td>
                                ₹${parseFloat(
                                    transaction.amount
                                ).toFixed(2)}
                            </td>


                            <td>
                                ₹${parseFloat(
                                    transaction.balance_before
                                ).toFixed(2)}
                            </td>


                            <td>
                                ₹${parseFloat(
                                    transaction.balance_after
                                ).toFixed(2)}
                            </td>


                            <td>
                                ${formatDate(
                                    transaction.created_at
                                )}
                            </td>

                        </tr>

                    `;


                }
            );


        }


        // =================================================
        // API ERROR
        // =================================================

        else {


            table.innerHTML = `

                <tr>

                    <td
                        colspan="7"
                        class="text-center text-danger">

                        ${data.message}

                    </td>

                </tr>

            `;

        }


    }

    catch (error) {


        console.error(error);


        document.getElementById(
            'transactionTable'
        ).innerHTML = `

            <tr>

                <td
                    colspan="7"
                    class="text-center text-danger">

                    Unable to load transactions.

                </td>

            </tr>

        `;

    }

}



// =====================================================
// FORMAT DATE
// =====================================================

function formatDate(date) {


    if (!date) {

        return "-";

    }


    let d =
        new Date(date);


    return d.toLocaleString();

}



// =====================================================
// LOGOUT
// =====================================================

async function logout() {


    try {


        let response =
            await fetch('/api/logout', {

                method: 'POST',

                headers: {

                    'Accept':
                        'application/json',

                    'Authorization':
                        'Bearer ' + token

                }

            });



        let data =
            await response.json();



        console.log(
            "Logout Response:",
            data
        );



        if (data.status) {


            // Remove token

            localStorage.removeItem(
                'token'
            );


            localStorage.removeItem(
                'token_type'
            );


            // Redirect

            window.location.href =
                "/login";


        }

        else {


            alert(
                data.message
            );

        }


    }

    catch (error) {


        console.error(error);


        alert(
            "Logout failed"
        );

    }

}



// =====================================================
// LOAD DATA WHEN PAGE OPENS
// =====================================================


// Load balance

getBalance();


// Load transaction history

getTransactions();


</script>


</body>

</html>
