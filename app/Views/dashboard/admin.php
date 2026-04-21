<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Meralkoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --mk-bg: #f8f9fa;
            --mk-primary: #1d3557;
            --mk-primary-soft: #457b9d;
            --mk-accent: #e63946;
        }

        body {
            background: linear-gradient(145deg, #eef4f8, var(--mk-bg));
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .dashboard-shell {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .panel {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 1rem 1.8rem rgba(29, 53, 87, 0.08);
        }

        .panel-header {
            background: linear-gradient(120deg, var(--mk-primary), var(--mk-primary-soft));
            color: #fff;
            border-radius: 1rem 1rem 0 0;
            padding: 1rem 1.25rem;
        }

        .badge-role {
            background: rgba(230, 57, 70, 0.15);
            color: var(--mk-accent);
            border: 1px solid rgba(230, 57, 70, 0.3);
            font-weight: 600;
        }

        .table thead th {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="dashboard-shell">
        <?= view('components/header', ['role' => 'admin', 'fullname' => $fullname]) ?>

        <?php if (session('success')): ?>
            <div class="alert alert-success"><?= esc((string) session('success')) ?></div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= esc((string) session('error')) ?></div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-5">
                <div class="card panel h-100" id="manage-users">
                    <div class="panel-header">
                        <h2 class="h5 mb-0">Create User Account</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="/admin/users">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input id="fullname" name="fullname" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input id="username" name="username" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" name="password" type="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select id="role" name="role" class="form-select" required>
                                    <option value="user">Normal User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card panel h-100" id="view-users">
                    <div class="panel-header d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Registered Users</h2>
                        <span class="badge text-bg-light">Total: <?= count($users) ?></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= esc((string) ($user['id'] ?? '')) ?></td>
                                            <td><?= esc((string) ($user['fullname'] ?? '')) ?></td>
                                            <td><?= esc((string) ($user['username'] ?? '')) ?></td>
                                            <td><span class="badge badge-role"><?= esc(strtoupper((string) ($user['role'] ?? ''))) ?></span></td>
                                            <td>
                                                <form method="post" action="/admin/users/<?= esc((string) $user['id']) ?>/update" class="row g-2">
                                                    <div class="col-12">
                                                        <input name="fullname" type="text" value="<?= esc((string) ($user['fullname'] ?? '')) ?>" class="form-control form-control-sm" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <input name="username" type="text" value="<?= esc((string) ($user['username'] ?? '')) ?>" class="form-control form-control-sm" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <select name="role" class="form-select form-select-sm">
                                                            <option value="user" <?= (($user['role'] ?? '') === 'user') ? 'selected' : '' ?>>Normal User</option>
                                                            <option value="admin" <?= (($user['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <input name="password" type="password" class="form-control form-control-sm" placeholder="New password (optional)">
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Update</button>
                                                    </div>
                                                </form>

                                                <form method="post" action="/admin/users/<?= esc((string) $user['id']) ?>/delete" class="mt-2" onsubmit="return confirm('Delete this account?');">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if ($users === []): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card panel" id="audit-logs">
            <div class="panel-header">
                <h2 class="h5 mb-0">System Audit Trails</h2>
            </div>
            <div class="card-body p-0">
                <?php if (! $auditStorageReady): ?>
                    <div class="alert alert-warning m-3 mb-0">Audit trail table is not available yet. Create an audit table to persist activity logs.</div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($auditTrails as $trail): ?>
                                <tr>
                                    <td><?= esc((string) ($trail['username'] ?? $trail['user_id'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($trail['role'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($trail['action'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($trail['details'] ?? $trail['description'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($trail['created_at'] ?? $trail['createdAt'] ?? '-')) ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if ($auditTrails === []): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No audit trails available yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
