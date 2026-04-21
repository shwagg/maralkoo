<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meralkoo | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background-color: #ffffff;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ── Logo ── */
        .mk-logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        /* ── Card shell ── */
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
        }

        /* ── Field labels ── */
        .form-label-upper {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        /* ── Inputs: borderless look, only bottom border ── */
        .form-control-flat {
            border: none;
            border-bottom: 1px solid #ced4da;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
            background: transparent;
            box-shadow: none !important;
            color: #212529;
            font-size: 0.95rem;
        }

        .form-control-flat:focus {
            border-bottom-color: #495057;
            background: transparent;
        }

        /* ── Password row (input + hint button) ── */
        .input-group-flat .form-control-flat {
            flex: 1 1 auto;
        }

        .btn-hint {
            background: transparent;
            border: none;
            border-bottom: 1px solid #ced4da;
            border-radius: 0;
            color: #adb5bd;
            padding: 0.375rem 0.5rem;
            font-size: 0.8rem;
            line-height: 1;
            cursor: pointer;
        }

        .btn-hint:hover {
            color: #6c757d;
        }

        /* ── Login button ── */
        .btn-login {
            background-color: #b0bec5;
            border-color: #b0bec5;
            color: #ffffff;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            padding: 0.6rem;
            transition: background-color 0.2s, border-color 0.2s;
        }

        .btn-login:hover,
        .btn-login:focus {
            background-color: #90a4ae;
            border-color: #90a4ae;
            color: #ffffff;
        }

        /* ── Divider ── */
        .mk-divider {
            border: none;
            border-top: 1px solid #e9ecef;
            width: 60%;
            margin: 0 auto 1.5rem;
        }
    </style>
</head>
<body>

    <div class="d-flex flex-column align-items-center justify-content-center min-vh-100 p-4">

        <div class="login-card rounded-3 shadow-sm p-4 p-md-5">

            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="<?= base_url('assets/MeralKoo.svg') ?>" alt="Meralkoo" class="mk-logo mb-3">
                <hr class="mk-divider">
            </div>

            <!-- Alerts -->
            <div id="loginAlert" class="alert d-none" role="alert"></div>

            <?php if (session('success')): ?>
                <div class="alert alert-success" role="alert"><?= esc((string) session('success')) ?></div>
            <?php endif; ?>

            <?php if (session('error')): ?>
                <div class="alert alert-danger" role="alert"><?= esc((string) session('error')) ?></div>
            <?php endif; ?>

            <!-- Form -->
            <form id="loginForm">

                <div class="mb-4">
                    <label for="identifier" class="form-label-upper">Username or Email</label>
                    <input type="text"
                           class="form-control form-control-flat"
                           id="identifier"
                           name="identifier"
                           autocomplete="username"
                           placeholder=""
                           required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label-upper">Password</label>
                    <!-- input-group keeps the password field and hint button on the same baseline -->
                    <div class="input-group input-group-flat">
                        <input type="password"
                               class="form-control form-control-flat"
                               id="password"
                               name="password"
                               placeholder=""
                               autocomplete="current-password"
                               required>
                        <button type="button"
                                class="btn-hint"
                                id="togglePassword"
                                title="Show / hide password"
                                aria-label="Toggle password visibility">?</button>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-login w-100 rounded-2 mt-2">Login</button>

            </form>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        (function () {
            const $form      = $('#loginForm');
            const $alert     = $('#loginAlert');
            const $submitBtn = $('#submitBtn');
            const $pwdInput  = $('#password');
            const $toggleBtn = $('#togglePassword');

            /* ── Password visibility toggle ── */
            $toggleBtn.on('click', function () {
                const isHidden = $pwdInput.attr('type') === 'password';
                $pwdInput.attr('type', isHidden ? 'text' : 'password');
                $(this).text(isHidden ? '×' : '?');
            });

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
