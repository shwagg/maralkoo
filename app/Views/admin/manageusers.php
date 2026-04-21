<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Manage Users | Meralkoo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		:root {
			--mk-bg: #f8f9fa;
			--mk-primary: #1d3557;
			--mk-primary-soft: #457b9d;
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
			<div class="panel-header">
				<h2 class="h5 mb-0">Manage Users</h2>
			</div>
			<div class="card-body">
				<form method="post" action="/admin/users">
					<div class="mb-3">
						<label for="fullname" class="form-label">Full Name</label>
						<input id="fullname" name="fullname" type="text" class="form-control" required>
					</div>
					<div class="mb-3">
						<label for="email" class="form-label">Email</label>
						<input id="email" name="email" type="email" class="form-control" required>
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
</body>
</html>
