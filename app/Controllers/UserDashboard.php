<?php

namespace App\Controllers;

use App\Controllers\Concerns\TracksAuditAndBilling;
use CodeIgniter\HTTP\RedirectResponse;

class UserDashboard extends BaseController
{
    use TracksAuditAndBilling;

    public function index(): RedirectResponse
    {
        return redirect()->to('/user/compute-bill');
    }

    public function computeBillPage(): string
    {
        return view('user/computebill', [
            'fullname' => (string) session('fullname'),
            'lastResult' => session('lastBillResult'),
        ]);
    }

    public function billingHistory(): string
    {
        $userId = (int) session('userId');

        return view('user/billinghistory', [
            'fullname' => (string) session('fullname'),
            'billingHistory' => $this->listBillingHistory($userId, 30),
            'billingStorageReady' => $this->getBillingTableName() !== null,
        ]);
    }

    public function actionTrail(): string
    {
        $userId = (int) session('userId');

        return view('user/actiontrail', [
            'fullname' => (string) session('fullname'),
            'auditTrails' => $this->listAuditTrails($userId, 30),
            'auditStorageReady' => $this->getAuditTableName() !== null,
        ]);
    }

    public function previewBill(): \CodeIgniter\HTTP\ResponseInterface
    {
        $kw = max(0.0, (float) $this->request->getGet('kw_used'));
        $total = $this->computeTieredTotalAmount($kw);

        if ($kw <= 0.0) {
            $rateLabel = '-';
        } elseif ($kw <= 200.0) {
            $rateLabel = '₱10.00/KW  (1 – 200 KW tier)';
        } elseif ($kw <= 500.0) {
            $rateLabel = '₱13.00/KW  (201 – 500 KW tier)';
        } else {
            $rateLabel = '₱15.00/KW  (501+ KW tier)';
        }

        return $this->response->setJSON([
            'total_amount'    => $total,
            'total_formatted' => '₱' . number_format($total, 2),
            'rate_label'      => $rateLabel,
        ]);
    }

    public function computeBill(): RedirectResponse
    {
        $validation = service('validation');
        $validation->setRules([
            'client_name' => 'required|min_length[3]|max_length[120]',
            'kw_used' => 'required|numeric|greater_than_equal_to[0]',
        ]);

        $payload = [
            'client_name' => trim((string) $this->request->getPost('client_name')),
            'kw_used' => (float) $this->request->getPost('kw_used'),
        ];

        if (! $validation->run($payload)) {
            return redirect()->to('/user/compute-bill')->with('error', implode(' ', $validation->getErrors()));
        }

        $bill = [
            'client_name' => $payload['client_name'],
            'kw_used' => $payload['kw_used'],
            'total_amount' => $this->computeTieredTotalAmount($payload['kw_used']),
        ];

        $saved = $this->saveComputedBill($bill);

        $this->appendAuditTrail(
            'user_compute_bill',
            'Computed bill for ' . $payload['client_name']
            . ' with ' . number_format($bill['kw_used'], 2)
            . ' KW and total ' . number_format($bill['total_amount'], 2)
        );

        session()->setFlashdata('lastBillResult', $bill);

        if (! $saved) {
            return redirect()->to('/user/compute-bill')->with(
                'warning',
                'Bill computed successfully, but billing history table is not available yet.'
            );
        }

        return redirect()->to('/user/compute-bill')->with('success', 'Bill computed and saved successfully.');
    }

    private function computeTieredTotalAmount(float $kwUsed): float
    {
        $kw = max(0.0, $kwUsed);

        if ($kw <= 200.0) {
            $total = $kw * 10.0;
        } elseif ($kw <= 500.0) {
            $total = $kw * 13.0;
        } else {
            $total = $kw * 15.0;
        }

        return round($total, 2);
    }
}
