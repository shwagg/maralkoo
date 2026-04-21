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

		.preview-box {
			background: linear-gradient(130deg, #e8f8f5, #d5f5e3);
			border: 1px solid #a9dfbf;
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
						<label for="kw_used" class="form-label">KW Used</label>
						<input id="kw_used" name="kw_used" type="number" step="0.01" min="0" class="form-control" required>
					</div>
					<div id="bill-preview" class="preview-box mb-3" style="display:none;">
						<div class="d-flex justify-content-between align-items-center">
							<span class="fw-semibold">Computed Total</span>
							<strong id="preview-total" class="fs-5 text-success">₱0.00</strong>
						</div>
						<div class="small text-muted mt-1" id="preview-rate"></div>
					</div>
					<button type="submit" class="btn btn-primary w-100">Save Bill</button>
				</form>

				<?php if (is_array($lastResult)): ?>
					<div class="result-box mt-3">
						<strong>Last Computation</strong>
						<div>Client: <?= esc((string) ($lastResult['client_name'] ?? '')) ?></div>
						<div>KW Used: <?= esc(number_format((float) ($lastResult['kw_used'] ?? $lastResult['kwh_used'] ?? 0), 2)) ?></div>
						<div>Total Amount: <?= esc(number_format((float) ($lastResult['total_amount'] ?? $lastResult['amount_due'] ?? 0), 2)) ?></div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<script>
(function () {
	var kwInput    = document.getElementById('kw_used');
	var previewBox = document.getElementById('bill-preview');
	var previewTotal = document.getElementById('preview-total');
	var previewRate  = document.getElementById('preview-rate');
	var timer = null;

	function fetchPreview(kw) {
		previewTotal.textContent = 'Computing…';
		previewBox.style.display = 'block';
		fetch('/user/bills/preview?kw_used=' + encodeURIComponent(kw))
			.then(function (r) { return r.json(); })
			.then(function (data) {
				previewTotal.textContent = data.total_formatted;
				previewRate.textContent  = data.rate_label;
			})
			.catch(function () {
				previewTotal.textContent = 'Error';
				previewRate.textContent  = '';
			});
	}

	kwInput.addEventListener('input', function () {
		clearTimeout(timer);
		var kw = parseFloat(this.value);
		if (isNaN(kw) || kw < 0 || this.value === '') {
			previewBox.style.display = 'none';
			return;
		}
		timer = setTimeout(function () { fetchPreview(kw); }, 300);
	});
})();
</script>
</body>
</html>
