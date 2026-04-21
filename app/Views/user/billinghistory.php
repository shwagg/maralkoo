<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Billing History | Meralkoo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		:root {
			--mk-a: #264653;
			--mk-b: #2a9d8f;
		}

		body {
			min-height: 100vh;
			font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
			background: radial-gradient(circle at 80% -20%, #d5f3ef, #f9fcff 55%);
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
			box-shadow: 0 1rem 2rem rgba(38, 70, 83, 0.08);
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
				<h2 class="h5 mb-0">Billing History</h2>
			</div>
			<div class="card-body p-0">
				<?php if (! $billingStorageReady): ?>
					<div class="alert alert-warning m-3 mb-0">Billing history table is not available yet. Computations will still be shown as latest result only.</div>
				<?php endif; ?>

				<div class="table-responsive">
					<table class="table table-hover mb-0">
						<thead>
							<tr>
								<th>Client</th>
								<th>kW Used</th>
								<th>Total Amount</th>
								<th>Created At</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($billingHistory as $row): ?>
								<tr>
									<td><?= esc((string) ($row['client_name'] ?? '-')) ?></td>
									<td><?= esc((string) ($row['kw_used'] ?? $row['kwh_used'] ?? '-')) ?></td>
									<td><?= esc((string) ($row['total_amount'] ?? $row['amount_due'] ?? '-')) ?></td>
									<td><?= esc((string) ($row['createdAt'] ?? $row['created_at'] ?? '-')) ?></td>
								</tr>
							<?php endforeach; ?>

							<?php if ($billingHistory === []): ?>
								<tr>
									<td colspan="4" class="text-center text-muted py-4">No billing history yet.</td>
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
