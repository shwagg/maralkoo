<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Compute Bill | Meralkoo</title>
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
		}

		.dashboard-shell {
			max-width: 900px;
			margin: 2rem auto;
			padding: 0 1rem;
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

		.result-box {
			background: linear-gradient(130deg, #fff8e7, #fef3c7);
			border: 1px solid #f1d8a5;
			border-radius: 0.75rem;
			padding: 0.9rem 1rem;
		}
	</style>
</head>
<body>
	<div class="dashboard-shell">
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
				<h2 class="h5 mb-0">Compute Electric Bill</h2>
			</div>
			<div class="card-body">
				<form method="post" action="/user/bills/compute">
					<div class="mb-3">
						<label for="client_name" class="form-label">Client Name</label>
						<input id="client_name" name="client_name" type="text" class="form-control" required>
					</div>
					<div class="mb-3">
						<label for="account_number" class="form-label">Account Number</label>
						<input id="account_number" name="account_number" type="text" class="form-control" required>
					</div>
					<div class="row g-3">
						<div class="col-12 col-md-6">
							<label for="previous_reading" class="form-label">Previous Reading (kWh)</label>
							<input id="previous_reading" name="previous_reading" type="number" step="0.01" min="0" class="form-control" required>
						</div>
						<div class="col-12 col-md-6">
							<label for="current_reading" class="form-label">Current Reading (kWh)</label>
							<input id="current_reading" name="current_reading" type="number" step="0.01" min="0" class="form-control" required>
						</div>
					</div>
					<div class="mt-3 mb-3">
						<label for="rate_per_kwh" class="form-label">Rate per kWh</label>
						<input id="rate_per_kwh" name="rate_per_kwh" type="number" step="0.01" min="0.01" class="form-control" required>
					</div>
					<button type="submit" class="btn btn-primary w-100">Compute Bill</button>
				</form>

				<?php if (is_array($lastResult)): ?>
					<div class="result-box mt-3">
						<strong>Last Computation</strong>
						<div>Client: <?= esc((string) ($lastResult['client_name'] ?? '')) ?></div>
						<div>Account: <?= esc((string) ($lastResult['account_number'] ?? '')) ?></div>
						<div>kWh Used: <?= esc(number_format((float) ($lastResult['kwh_used'] ?? 0), 2)) ?></div>
						<div>Amount Due: <?= esc(number_format((float) ($lastResult['amount_due'] ?? 0), 2)) ?></div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>
</html>
