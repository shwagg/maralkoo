<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>View Users | Meralkoo</title>
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
			margin: 0;
		}

		.dashboard-shell {
			width: 100%;
			margin: 0;
			padding: 0 0 1.5rem;
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
	<div class="dashboard-shell container-fluid px-0">
		<?= view('components/header', ['role' => 'admin', 'fullname' => $fullname]) ?>

		<?php if (session('success')): ?>
			<div class="alert alert-success"><?= esc((string) session('success')) ?></div>
		<?php endif; ?>

		<?php if (session('error')): ?>
			<div class="alert alert-danger"><?= esc((string) session('error')) ?></div>
		<?php endif; ?>

		<div class="card panel">
			<div class="panel-header d-flex justify-content-between align-items-center">
				<h2 class="h5 mb-0">View Users</h2>
				<span class="badge text-bg-light">Total: <?= count($users) ?></span>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
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
									<td><?= esc((string) ($user['email'] ?? '')) ?></td>
									<td><?= esc((string) ($user['username'] ?? '')) ?></td>
									<td><span class="badge badge-role"><?= esc(strtoupper((string) ($user['role'] ?? ''))) ?></span></td>
									<td>
										<form method="post" action="/admin/users/<?= esc((string) $user['id']) ?>/update" class="row g-2">
											<div class="col-12">
												<input name="fullname" type="text" value="<?= esc((string) ($user['fullname'] ?? '')) ?>" class="form-control form-control-sm" required>
											</div>
											<div class="col-12">
												<input name="email" type="email" value="<?= esc((string) ($user['email'] ?? '')) ?>" class="form-control form-control-sm" required>
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
									<td colspan="6" class="text-center text-muted py-4">No users found.</td>
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
