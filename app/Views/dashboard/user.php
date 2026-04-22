<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard | Meralkoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --mk-a: #4683cb;
            --mk-b: #4683cb;
            --mk-c: #4683cb;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 80% -20%, rgba(70, 131, 203, 0.16), #f9fcff 55%);
            margin: 0;
        }

        .dashboard-shell {
            width: 100%;
            margin: 0;
            padding: 0 0 2.5rem;
        }

        .dashboard-content {
            max-width: 980px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 0;
        }

        .panel {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 1rem 2rem rgba(70, 131, 203, 0.12);
        }

        .panel-header {
            border-radius: 1rem 1rem 0 0;
            background: linear-gradient(125deg, var(--mk-a), var(--mk-b));
            color: #fff;
            padding: 1rem 1.25rem;
        }

        .result-box {
            background: linear-gradient(130deg, rgba(70, 131, 203, 0.06), rgba(70, 131, 203, 0.14));
            border: 1px solid rgba(70, 131, 203, 0.22);
            border-radius: 0.75rem;
            padding: 0.9rem 1rem;
        }

        .preview-box {
            background: linear-gradient(130deg, rgba(70, 131, 203, 0.06), rgba(70, 131, 203, 0.14));
            border: 1px solid rgba(70, 131, 203, 0.22);
            border-radius: 0.75rem;
            padding: 0.9rem 1rem;
        }

        .btn-primary,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #4683cb;
            border-color: #4683cb;
        }
    </style>
</head>
<body>
    <div class="dashboard-shell container-fluid px-0">
        <?= view('components/header', ['role' => 'user', 'fullname' => $fullname]) ?>

        <?php if (session('success')): ?>
            <div class="alert alert-success"><?= esc((string) session('success')) ?></div>
        <?php endif; ?>

        <?php if (session('warning')): ?>
            <div class="alert alert-warning"><?= esc((string) session('warning')) ?></div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= esc((string) session('error')) ?></div>
        <?php endif; ?>

        <div class="dashboard-content">
        <div class="row g-4 justify-content-center align-items-stretch">
            <div class="col-12 col-md-6">
                <div class="card panel h-100">
                    <div class="card-body d-flex align-items-center gap-4 py-4 px-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:64px;height:64px;background:rgba(70,131,203,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--mk-b)" viewBox="0 0 16 16">
                                <path d="M0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5H1.5A1.5 1.5 0 0 0 0 6zM2 10h4v1H2zm5 0h2v1H7zm3 0h2v1h-2zM2 8h12v1H2zm0-2h12v1H2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase small mb-2">Total Bills</div>
                            <div class="fw-bold display-5 lh-1"><?= esc((string) $billingCount) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card panel h-100">
                    <div class="card-body d-flex align-items-center gap-4 py-4 px-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:64px;height:64px;background:rgba(70,131,203,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--mk-a)" viewBox="0 0 16 16">
                                <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.12-1.718z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase small mb-2">Total Amount</div>
                            <div class="fw-bold display-5 lh-1" style="color:var(--mk-a);">₱<?= esc(number_format((float) $billingTotal, 2)) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
 </body>
 </html>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alertElement = document.getElementById('loginAlert');
            if (alertElement) {
                setTimeout(() => {
                    alertElement.classList.add('d-none');
                }, 5000);
            }
        });
</script>
</body>
</html>
