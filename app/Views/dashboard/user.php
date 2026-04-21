<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard | Meralkoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --mk-a: #264653;
            --mk-b: #2a9d8f;
            --mk-c: #e9c46a;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 80% -20%, #d5f3ef, #f9fcff 55%);
        }

        .dashboard-shell {
            max-width: 1150px;
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

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card panel h-100">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:52px;height:52px;background:rgba(42,157,143,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--mk-b)" viewBox="0 0 16 16">
                                <path d="M0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5H1.5A1.5 1.5 0 0 0 0 6zM2 10h4v1H2zm5 0h2v1H7zm3 0h2v1h-2zM2 8h12v1H2zm0-2h12v1H2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Total Bills</div>
                            <div class="fw-bold fs-3 lh-1"><?= esc((string) $billingCount) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card panel h-100">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:52px;height:52px;background:rgba(233,196,106,0.18);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#b38000" viewBox="0 0 16 16">
                                <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.12-1.718z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Total Amount</div>
                            <div class="fw-bold fs-3 lh-1" style="color:#b38000;">₱<?= esc(number_format((float) $billingTotal, 2)) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card panel h-100" id="compute-electric-bill">
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
                            <div class="small text-muted mb-3">
                                Rates: 1–200 KW = ₱10.00/KW &nbsp;|&nbsp; 201–500 KW = ₱13.00/KW &nbsp;|&nbsp; 501+ KW = ₱15.00/KW.
                                Total amount is computed automatically.
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

            <div class="col-12 col-lg-6">
                <div class="card panel h-100" id="billing-history">
                    <div class="panel-header">
                        <h2 class="h5 mb-0">My Billing History</h2>
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
        </div>

        <div class="card panel" id="action-trail">
            <div class="panel-header">
                <h2 class="h5 mb-0">My Action Trail</h2>
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
<script>
(function () {
    var kwInput      = document.getElementById('kw_used');
    var previewBox   = document.getElementById('bill-preview');
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
