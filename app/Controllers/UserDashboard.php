<?php

namespace App\Controllers;

use App\Controllers\Concerns\TracksAuditAndBilling;
use CodeIgniter\HTTP\RedirectResponse;

class UserDashboard extends BaseController
{
    use TracksAuditAndBilling;

    public function index(): string
    {
        $userId = (int) session('userId');

        $data = [
            'fullname' => (string) session('fullname'),
            'billingHistory' => $this->listBillingHistory($userId, 30),
            'auditTrails' => $this->listAuditTrails($userId, 30),
            'billingStorageReady' => $this->getBillingTableName() !== null,
            'auditStorageReady' => $this->getAuditTableName() !== null,
            'lastResult' => session('lastBillResult'),
        ];

        return view('dashboard/user', $data);
    }

    public function computeBill(): RedirectResponse
    {
        $validation = service('validation');
        $validation->setRules([
            'client_name' => 'required|min_length[3]|max_length[120]',
            'account_number' => 'required|min_length[3]|max_length[50]',
            'previous_reading' => 'required|decimal|greater_than_equal_to[0]',
            'current_reading' => 'required|decimal|greater_than_equal_to[0]',
            'rate_per_kwh' => 'required|decimal|greater_than[0]',
        ]);

        $payload = [
            'client_name' => trim((string) $this->request->getPost('client_name')),
            'account_number' => trim((string) $this->request->getPost('account_number')),
            'previous_reading' => (float) $this->request->getPost('previous_reading'),
            'current_reading' => (float) $this->request->getPost('current_reading'),
            'rate_per_kwh' => (float) $this->request->getPost('rate_per_kwh'),
        ];

        if (! $validation->run($payload)) {
            return redirect()->to('/user/dashboard')->with('error', implode(' ', $validation->getErrors()));
        }

        if ($payload['current_reading'] < $payload['previous_reading']) {
            return redirect()->to('/user/dashboard')->with('error', 'Current reading cannot be lower than previous reading.');
        }

        $kwhUsed = $payload['current_reading'] - $payload['previous_reading'];
        $amountDue = round($kwhUsed * $payload['rate_per_kwh'], 2);

        $bill = [
            'client_name' => $payload['client_name'],
            'account_number' => $payload['account_number'],
            'previous_reading' => $payload['previous_reading'],
            'current_reading' => $payload['current_reading'],
            'kwh_used' => $kwhUsed,
            'rate_per_kwh' => $payload['rate_per_kwh'],
            'amount_due' => $amountDue,
        ];

        $saved = $this->saveComputedBill($bill);

        $this->appendAuditTrail(
            'user_compute_bill',
            'Computed bill for account ' . $payload['account_number'] . ' amount ' . number_format($amountDue, 2)
        );

        session()->setFlashdata('lastBillResult', $bill);

        if (! $saved) {
            return redirect()->to('/user/dashboard')->with(
                'warning',
                'Bill computed successfully, but billing history table is not available yet.'
            );
        }

        return redirect()->to('/user/dashboard')->with('success', 'Bill computed and saved successfully.');
    }
}
