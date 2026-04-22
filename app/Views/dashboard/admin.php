<!doctype html>
<html lang="en">
<head>
    <?= view('components/head_assets', ['title' => 'Admin Dashboard | Meralkoo']) ?>
    <style>
        :root {
            --mk-bg: #f8f9fa;
            --mk-primary: #4683cb;
            --mk-primary-soft: #4683cb;
            --mk-accent: #4683cb;
        }

        body {
            background: linear-gradient(145deg, rgba(70, 131, 203, 0.12), var(--mk-bg));
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
        }

        .dashboard-shell {
            width: 100%;
            margin: 0;
            padding: 0 0 2.5rem;
        }

        .dashboard-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 0;
        }

        .panel {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 1rem 1.8rem rgba(70, 131, 203, 0.12);
        }

        .panel-header {
            background: linear-gradient(120deg, var(--mk-primary), var(--mk-primary-soft));
            color: #fff;
            border-radius: 1rem 1rem 0 0;
            padding: 1rem 1.25rem;
        }

        .badge-role {
            background: rgba(70, 131, 203, 0.15);
            color: var(--mk-accent);
            border: 1px solid rgba(70, 131, 203, 0.3);
            font-weight: 600;
        }

        .btn-primary,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-outline-primary,
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active {
            border-color: #4683cb;
            background-color: #4683cb;
            color: #ffffff;
        }

        .table thead th {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="dashboard-shell container-fluid px-0">
        <?= view('components/header', ['role' => 'admin', 'fullname' => $fullname]) ?>

        <?php if (session('success')): ?>
            <div class="alert alert-success"><?= esc((string) session('success')) ?></div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= esc((string) session('error')) ?></div>
        <?php endif; ?>

        <div class="dashboard-content">
        <div class="row g-4 justify-content-center align-items-stretch">
            <div class="col-12 col-lg-5">
                <div class="card panel h-100">
                    <div class="card-body d-flex align-items-center gap-4 py-4 px-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:64px;height:64px;background:rgba(70,131,203,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--mk-primary)" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase small mb-2">Registered Users</div>
                            <div class="fw-bold display-5 lh-1"><?= esc((string) $userCount) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card panel h-100">
                    <div class="card-body d-flex align-items-center gap-4 py-4 px-4">
                                <a href="/admin/manage-users"
                           class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-decoration-none"
                           style="width:64px;height:64px;background:rgba(70,131,203,0.1);"
                           title="Create a new user account">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="var(--mk-accent)" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                        </a>
                        <div>
                            <div class="text-muted text-uppercase small mb-2">Quick Action</div>
                            <a href="/admin/manage-users" class="fw-semibold fs-5 text-decoration-none" style="color:var(--mk-accent);">Create New User</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <?= view('components/footer_assets') ?>
</body>
</html>
