
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center mt-5">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-body p-4">

                        <h3 class="text-center mb-4">
                            Forgot Password
                        </h3>

                        <!-- Message -->
                        <div id="message"></div>


                        <!-- =========================
                             STEP 1 : EMAIL
                        ========================== -->

                        <div id="emailSection">

                            <label class="form-label">
                                Enter your Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                placeholder="Enter your email"
                            >

                            <button
                                type="button"
                                class="btn btn-primary w-100 mt-3"
                                onclick="sendOTP()"
                            >
                                Send OTP
                            </button>

                        </div>


                        <!-- =========================
                             STEP 2 : OTP
                        ========================== -->

                        <div id="otpSection" style="display:none;">

                            <label class="form-label">
                                Enter OTP
                            </label>

                            <input
                                type="text"
                                id="otp"
                                class="form-control"
                                placeholder="Enter 6 digit OTP"
                                maxlength="6"
                            >

                            <button
                                type="button"
                                class="btn btn-success w-100 mt-3"
                                onclick="verifyOTP()"
                            >
                                Verify OTP
                            </button>

                        </div>


                        <!-- =========================
                             STEP 3 : NEW PASSWORD
                        ========================== -->

                        <div id="passwordSection" style="display:none;">

                            <label class="form-label">
                                New Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                placeholder="Enter new password"
                            >


                            <label class="form-label mt-3">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Confirm new password"
                            >


                            <button
                                type="button"
                                class="btn btn-primary w-100 mt-3"
                                onclick="resetPassword()"
                            >
                                Reset Password
                            </button>

                        </div>


                        <!-- Login Link -->

                        <div class="text-center mt-4">

                            <a href="/login">
                                Back to Login
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>

        // =====================================
        // STEP 1 : SEND OTP
        // =====================================

        async function sendOTP()
        {
            let email = document.getElementById('email').value;

            if (email === '') {

                showMessage(
                    'Please enter your email',
                    'danger'
                );

                return;
            }


            try {

                let response = await fetch('/api/forgot-password', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },

                    body: JSON.stringify({
                        email: email
                    })

                });


                let data = await response.json();


                if (data.status) {

                    showMessage(
                        data.message,
                        'success'
                    );


                    // Hide email section
                    document.getElementById(
                        'emailSection'
                    ).style.display = 'none';


                    // Show OTP section
                    document.getElementById(
                        'otpSection'
                    ).style.display = 'block';

                }
                else {

                    showMessage(
                        data.message,
                        'danger'
                    );

                }


            }
            catch (error) {

                showMessage(
                    'Something went wrong',
                    'danger'
                );

                console.log(error);

            }

        }



        // =====================================
        // STEP 2 : VERIFY OTP
        // =====================================

        async function verifyOTP()
        {

            let email = document.getElementById('email').value;

            let otp = document.getElementById('otp').value;


            if (otp === '') {

                showMessage(
                    'Please enter OTP',
                    'danger'
                );

                return;
            }


            try {

                let response = await fetch(
                    '/api/verify-reset-otp',
                    {

                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json'

                        },

                        body: JSON.stringify({

                            email: email,

                            otp: otp

                        })

                    }
                );


                let data = await response.json();


                if (data.status) {

                    showMessage(
                        data.message,
                        'success'
                    );


                    // Hide OTP section
                    document.getElementById(
                        'otpSection'
                    ).style.display = 'none';


                    // Show password section
                    document.getElementById(
                        'passwordSection'
                    ).style.display = 'block';

                }
                else {

                    showMessage(
                        data.message,
                        'danger'
                    );

                }

            }
            catch (error) {

                showMessage(
                    'Something went wrong',
                    'danger'
                );

                console.log(error);

            }

        }



        // =====================================
        // STEP 3 : RESET PASSWORD
        // =====================================

        async function resetPassword()
        {

            let email =
                document.getElementById('email').value;


            let otp =
                document.getElementById('otp').value;


            let password =
                document.getElementById('password').value;


            let password_confirmation =
                document.getElementById(
                    'password_confirmation'
                ).value;


            if (password === '') {

                showMessage(
                    'Please enter new password',
                    'danger'
                );

                return;
            }


            if (password !== password_confirmation) {

                showMessage(
                    'Passwords do not match',
                    'danger'
                );

                return;
            }


            try {

                let response = await fetch(
                    '/api/reset-password',
                    {

                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json'

                        },

                        body: JSON.stringify({

                            email: email,

                            otp: otp,

                            password: password,

                            password_confirmation:
                                password_confirmation

                        })

                    }
                );


                let data = await response.json();


                if (data.status) {

                    showMessage(
                        data.message,
                        'success'
                    );


                    // Clear fields

                    document.getElementById(
                        'password'
                    ).value = '';

                    document.getElementById(
                        'password_confirmation'
                    ).value = '';


                    // Redirect to login
                    setTimeout(function () {

                        window.location.href = '/login';

                    }, 2000);

                }
                else {

                    showMessage(
                        data.message,
                        'danger'
                    );

                }

            }
            catch (error) {

                showMessage(
                    'Something went wrong',
                    'danger'
                );

                console.log(error);

            }

        }



        // =====================================
        // SHOW MESSAGE
        // =====================================

        function showMessage(message, type)
        {

            document.getElementById(
                'message'
            ).innerHTML = `

                <div class="alert alert-${type}">
                    ${message}
                </div>

            `;

        }

    </script>

</body>

</html>

