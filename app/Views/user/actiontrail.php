<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Action Trail | Meralkoo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		:root {
			--mk-a: #4683cb;
			--mk-b: #4683cb;
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
			padding: 0 0 1.5rem;
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

		<div class="card panel">
			<div class="panel-header">
				<h2 class="h5 mb-0">Action Trail</h2>
			</div>
			<div class="card-body p-0">
				<?php if (! $auditStorageReady): ?>
					<div class="alert alert-warning m-3 mb-0">Audit trail table is not available yet.</div>
				<?php endif; ?>

				<div class="table-responsive">
					<table class="table table-striped mb-0">
						<thead>
							<tr>
								<th>Action</th>
								<th>Details</th>
								<th>When</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($auditTrails as $trail): ?>
								<tr>
									<td><?= esc((string) ($trail['action'] ?? '-')) ?></td>
									<td><?= esc((string) ($trail['details'] ?? $trail['description'] ?? '-')) ?></td>
									<td><?= esc((string) ($trail['created_at'] ?? $trail['createdAt'] ?? '-')) ?></td>
								</tr>
							<?php endforeach; ?>

							<?php if ($auditTrails === []): ?>
								<tr>
									<td colspan="3" class="text-center text-muted py-4">No activity logs yet.</td>
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
