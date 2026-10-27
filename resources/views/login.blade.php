
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body id="body" class="bg-light">


<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div id="card" class="card shadow">

                <!-- Header -->

                <div class="card-header text-center bg-dark text-white">

                    <p class="mb-0">
                        Login
                    </p>

                </div>


                <div class="card-body">

                    <!-- Dark Mode Button -->

                    <div class="text-end mb-3">

                        <button
                            type="button"
                            id="darkModeBtn"
                            class="btn btn-sm btn-outline-dark">

                            🌙 Dark Mode

                        </button>

                    </div>


                    <!-- Message -->

                    <div id="message"></div>


                    <form id="loginForm">

                        @csrf


                        <!-- Email -->

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                placeholder="Enter email"
                                required>

                        </div>


                        <!-- Password -->

                        <div class="mb-2">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                placeholder="Enter password"
                                required>

                        </div>


                        <!-- Forgot Password -->

                        <div class="text-end mb-3">

                            <a
                                href="/forgot-password"
                                id="forgotLink"
                                class="text-decoration-none">

                                Forgot Password?

                            </a>

                        </div>


                        <!-- Login Button -->

                        <button
                            type="submit"
                            id="loginButton"
                            class="btn btn-dark w-100">

                            Login

                        </button>

                    </form>


                    <!-- Register -->

                    <div class="text-center mt-3">

                        <span>
                            Don't have an account?
                        </span>

                        <a
                            href="/register"
                            id="registerLink">

                            Create new account

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>


// ========================================
// DARK MODE ELEMENTS
// ========================================

const body =
    document.getElementById('body');

const card =
    document.getElementById('card');

const darkModeBtn =
    document.getElementById('darkModeBtn');

const loginButton =
    document.getElementById('loginButton');

const forgotLink =
    document.getElementById('forgotLink');

const registerLink =
    document.getElementById('registerLink');


// ========================================
// CHECK SAVED DARK MODE
// ========================================

let savedMode =
    localStorage.getItem('darkMode');

if (savedMode === 'true') {

    enableDarkMode();

}


// ========================================
// DARK MODE BUTTON
// ========================================

darkModeBtn.addEventListener(
    'click',
    function() {

        if (
            body.classList.contains('bg-dark')
        ) {

            disableDarkMode();

        } else {

            enableDarkMode();

        }

    }
);


// ========================================
// ENABLE DARK MODE
// ========================================

function enableDarkMode()
{

    // Body

    body.classList.remove('bg-light');

    body.classList.add('bg-dark');

    body.classList.add('text-white');


    // Card

    card.classList.remove('bg-white');

    card.classList.add('bg-secondary');

    card.classList.add('text-white');


    // Dark mode button

    darkModeBtn.classList.remove(
        'btn-outline-dark'
    );

    darkModeBtn.classList.add(
        'btn-outline-light'
    );

    darkModeBtn.innerHTML =
        '☀️ Light Mode';


    // Login button

    loginButton.classList.remove(
        'btn-dark'
    );

    loginButton.classList.add(
        'btn-light'
    );

    loginButton.classList.add(
        'text-dark'
    );


    // Forgot password link

    forgotLink.classList.remove(
        'text-dark'
    );

    forgotLink.classList.add(
        'text-white'
    );


    // Register link

    registerLink.classList.remove(
        'text-dark'
    );

    registerLink.classList.add(
        'text-white'
    );


    // Input fields

    document
        .querySelectorAll('.form-control')
        .forEach(function(input) {

            input.classList.add('bg-dark');

            input.classList.add('text-white');

            input.classList.add(
                'border-secondary'
            );

        });


    // Save mode

    localStorage.setItem(
        'darkMode',
        'true'
    );

}


// ========================================
// DISABLE DARK MODE
// ========================================

function disableDarkMode()
{

    // Body

    body.classList.remove('bg-dark');

    body.classList.add('bg-light');

    body.classList.remove('text-white');


    // Card

    card.classList.remove('bg-secondary');

    card.classList.add('bg-white');

    card.classList.remove('text-white');


    // Dark mode button

    darkModeBtn.classList.remove(
        'btn-outline-light'
    );

    darkModeBtn.classList.add(
        'btn-outline-dark'
    );

    darkModeBtn.innerHTML =
        '🌙 Dark Mode';


    // Login button

    loginButton.classList.remove(
        'btn-light'
    );

    loginButton.classList.remove(
        'text-dark'
    );

    loginButton.classList.add(
        'btn-dark'
    );


    // Forgot password link

    forgotLink.classList.remove(
        'text-white'
    );

    forgotLink.classList.add(
        'text-dark'
    );


    // Register link

    registerLink.classList.remove(
        'text-white'
    );

    registerLink.classList.add(
        'text-dark'
    );


    // Input fields

    document
        .querySelectorAll('.form-control')
        .forEach(function(input) {

            input.classList.remove(
                'bg-dark'
            );

            input.classList.remove(
                'text-white'
            );

            input.classList.remove(
                'border-secondary'
            );

        });


    // Save mode

    localStorage.setItem(
        'darkMode',
        'false'
    );

}


// ========================================
// LOGIN API
// ========================================

document
    .getElementById('loginForm')
    .addEventListener(
        'submit',
        async function(e) {

            e.preventDefault();


            // Get email

            let email =
                document.getElementById(
                    'email'
                ).value;


            // Get password

            let password =
                document.getElementById(
                    'password'
                ).value;


            try {

                // Login API

                let response =
                    await fetch(
                        '/api/login',
                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json'

                            },

                            body:
                                JSON.stringify({

                                    email: email,

                                    password: password

                                })

                        }
                    );


                let data =
                    await response.json();


                console.log(data);


                // ==============================
                // LOGIN SUCCESS
                // ==============================

                if (data.status === true) {


                    // Save token

                    localStorage.setItem(
                        'token',
                        data.tokens
                    );


                    // Save role

                    localStorage.setItem(
                        'role',
                        data.role
                    );


                    // ADMIN

                    if (
                        data.role === 'admin'
                    ) {

                        window.location.href =
                            '/admin/dashboard';

                    }


                    // USER

                    else if (
                        data.role === 'user'
                    ) {

                        window.location.href =
                            '/dashboard';

                    }

                }


                // ==============================
                // LOGIN FAILED
                // ==============================

                else {

                    document
                        .getElementById(
                            'message'
                        )
                        .innerHTML = `

                        <div class="alert alert-danger">

                            ${data.message}

                        </div>

                    `;

                }


            } catch (error) {

                console.log(error);


                document
                    .getElementById(
                        'message'
                    )
                    .innerHTML = `

                    <div class="alert alert-danger">

                        Something went wrong.
                        Please try again.

                    </div>

                `;

            }

        }
    );

</script>


</body>

</html>
