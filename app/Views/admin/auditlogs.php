<!doctype html>
<html lang="en">
<head>
	<?= view('components/head_assets', ['title' => 'Audit Logs | Meralkoo']) ?>
	<style>
		:root {
			--mk-bg: #f8f9fa;
			--mk-primary: #4683cb;
			--mk-primary-soft: #4683cb;
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
			padding: 0 0 1.5rem;
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
				<h2 class="h5 mb-0">Audit Logs</h2>
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
	<?= view('components/footer_assets') ?>
</body>
</html>
