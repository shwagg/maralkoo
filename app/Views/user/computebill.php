<!doctype html>
<html lang="en">
<head>
	<?= view('components/head_assets', ['title' => 'Compute Bill | Meralkoo']) ?>
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
							<strong id="preview-total" class="fs-5" style="color: var(--mk-a);">₱0.00</strong>
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
<?= view('components/footer_assets') ?>
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
		window.Meralkoo.ajax({
			url: '/user/bills/preview',
			type: 'GET',
			dataType: 'json',
			data: {
				kw_used: kw
			}
		}).done(function (data) {
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
