<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Money</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body class="bg-light">


<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header bg-success text-white text-center">

                    <h3>Add Money</h3>

                </div>


                <div class="card-body">

                    <div id="message"></div>


                    <form id="addMoneyForm">

                        <div class="mb-3">

                            <label class="form-label">
                                Amount
                            </label>

                            <input
                                type="number"
                                id="amount"
                                class="form-control"
                               
                                placeholder="Enter amount"
                                required>

                            <small class="text-muted">
                                Minimum ₹0.01 and Maximum ₹5000
                            </small>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-success w-100">

                            Add Money

                        </button>

                    </form>


                    <div class="text-center mt-3">

                        <a href="/dashboard">
                            Back to Dashboard
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.getElementById('addMoneyForm').addEventListener('submit', async function(e) {

    e.preventDefault();


    let amount = document.getElementById('amount').value;

    let token = localStorage.getItem('token');


    if (!token) {

        window.location.href = "/login";

        return;

    }


    let response = await fetch('/api/addmoney', {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'Accept': 'application/json',

            'Authorization': 'Bearer ' + token

        },

        body: JSON.stringify({

            amount: amount

        })

    });


    let data = await response.json();


    if (data.status) {

        document.getElementById('message').innerHTML = `

            <div class="alert alert-success">

                ${data.message}

                <br>

                New Balance:
                ₹${data.balance}

            </div>

        `;


        document.getElementById('addMoneyForm').reset();

    } else {

        document.getElementById('message').innerHTML = `

            <div class="alert alert-danger">

                ${data.message}

            </div>

        `;

    }

});

</script>


</body>

</html>