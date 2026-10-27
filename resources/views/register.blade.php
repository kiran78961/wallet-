<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header text-center bg-primary text-white">
                   
                    <p class="mb-0">Create Account</p>
                </div>

                <div class="card-body">

                    <div id="message"></div>

                    <form id="registerForm">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>

                            <input
                                type="text"
                                id="name"
                                class="form-control"
                                placeholder="Enter your name"
                                required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                placeholder="Enter password"
                                required>
                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Register

                        </button>

                    </form>

                    <div class="text-center mt-3">

                        <a href="/login">
                            Already have an account? Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>


<script>

document.getElementById('registerForm').addEventListener('submit', async function(e) {

    e.preventDefault();

    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    let response = await fetch('/api/signup', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },

        body: JSON.stringify({
            name: name,
            email: email,
            password: password
        })

    });

    let data = await response.json();

    if (data.status) {

        document.getElementById('message').innerHTML = `
            <div class="alert alert-success">
                ${data.message}
            </div>
        `;

        document.getElementById('registerForm').reset();

        setTimeout(() => {
            window.location.href = "/login";
        }, 1500);

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