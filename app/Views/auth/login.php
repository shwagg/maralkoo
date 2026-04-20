<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meralkoo | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --mk-bg-a: #f6fff8;
            --mk-bg-b: #d8f3dc;
            --mk-accent: #2d6a4f;
            --mk-accent-dark: #1b4332;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top right, var(--mk-bg-b), var(--mk-bg-a) 55%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 1.25rem 2.5rem rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .login-card .card-header {
            border: 0;
            background: linear-gradient(130deg, var(--mk-accent), var(--mk-accent-dark));
            color: #fff;
            padding: 1.25rem;
        }

        .brand-title {
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0;
        }

        .hint {
            margin: 0;
            opacity: 0.9;
            font-size: 0.92rem;
        }

        .btn-login {
            background: var(--mk-accent);
            border-color: var(--mk-accent);
        }

        .btn-login:hover,
        .btn-login:focus {
            background: var(--mk-accent-dark);
            border-color: var(--mk-accent-dark);
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-header">
            <h1 class="h4 brand-title">Meralkoo Electric Billing</h1>
            <p class="hint">Sign in with your account to continue.</p>
        </div>
        <div class="card-body p-4">
            <div id="loginAlert" class="alert d-none" role="alert"></div>

            <form id="loginForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-login text-white w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <span class="text-secondary">No account yet?</span>
                <a href="/signup" class="link-success fw-semibold">Sign Up</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        (function () {
            const $form = $('#loginForm');
            const $alert = $('#loginAlert');
            const $submitBtn = $('#submitBtn');

            function showAlert(type, message) {
                $alert.removeClass('d-none alert-success alert-danger').addClass('alert-' + type).text(message);
            }

            $form.on('submit', function (event) {
                event.preventDefault();
                $submitBtn.prop('disabled', true).text('Logging in...');
                $alert.addClass('d-none').text('');

                $.ajax({
                    url: '/login',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json'
                }).done(function (response) {
                    showAlert('success', response.message || 'Login successful. Redirecting...');
                    if (response.redirect) {
                        setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 700);
                    }
                }).fail(function (xhr) {
                    let message = 'Login failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showAlert('danger', message);
                }).always(function () {
                    $submitBtn.prop('disabled', false).text('Login');
                });
            });
        })();
    </script>
</body>
</html>
