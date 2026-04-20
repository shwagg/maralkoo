<?php

namespace App\Controllers\Concerns;

trait TracksAuditAndBilling
{
    protected function getAuditTableName(): ?string
    {
        return $this->resolveAvailableTable(['audit_trails', 'audit_logs']);
    }

    protected function getBillingTableName(): ?string
    {
        return $this->resolveAvailableTable(['billing_history', 'billing_histories', 'bills']);
    }

    protected function appendAuditTrail(string $action, string $details = ''): void
    {
        $table = $this->getAuditTableName();
        if ($table === null) {
            return;
        }

        $db = db_connect();
        $fields = $db->getFieldNames($table);

        $data = [
            'user_id' => (int) session('userId'),
            'username' => (string) session('username'),
            'role' => (string) session('role'),
            'action' => $action,
            'details' => $details,
            'ip_address' => (string) $this->request->getIPAddress(),
            'user_agent' => (string) $this->request->getUserAgent(),
            'created_at' => date('Y-m-d H:i:s'),
            'createdAt' => date('Y-m-d H:i:s'),
        ];

        $insert = [];
        foreach ($data as $column => $value) {
            if (in_array($column, $fields, true)) {
                $insert[$column] = $value;
            }
        }

        if ($insert !== []) {
            $db->table($table)->insert($insert);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function listAuditTrails(?int $userId = null, int $limit = 20): array
    {
        $table = $this->getAuditTableName();
        if ($table === null) {
            return [];
        }

        $db = db_connect();
        $fields = $db->getFieldNames($table);
        $builder = $db->table($table);

        if ($userId !== null && in_array('user_id', $fields, true)) {
            $builder->where('user_id', $userId);
        }

        if (in_array('created_at', $fields, true)) {
            $builder->orderBy('created_at', 'DESC');
        } elseif (in_array('createdAt', $fields, true)) {
            $builder->orderBy('createdAt', 'DESC');
        }

        return $builder->limit($limit)->get()->getResultArray();
    }

    protected function saveComputedBill(array $bill): bool
    {
        $table = $this->getBillingTableName();
        if ($table === null) {
            return false;
        }

        $db = db_connect();
        $fields = $db->getFieldNames($table);

        $data = [
            'user_id' => (int) session('userId'),
            'username' => (string) session('username'),
            'client_name' => $bill['client_name'] ?? '',
            'account_number' => $bill['account_number'] ?? '',
            'previous_reading' => $bill['previous_reading'] ?? 0,
            'current_reading' => $bill['current_reading'] ?? 0,
            'kwh_used' => $bill['kwh_used'] ?? 0,
            'rate_per_kwh' => $bill['rate_per_kwh'] ?? 0,
            'amount_due' => $bill['amount_due'] ?? 0,
            'billing_date' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s'),
            'createdAt' => date('Y-m-d H:i:s'),
        ];

        $insert = [];
        foreach ($data as $column => $value) {
            if (in_array($column, $fields, true)) {
                $insert[$column] = $value;
            }
        }

        if ($insert === []) {
            return false;
        }

        return $db->table($table)->insert($insert);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function listBillingHistory(int $userId, int $limit = 20): array
    {
        $table = $this->getBillingTableName();
        if ($table === null) {
            return [];
        }

        $db = db_connect();
        $fields = $db->getFieldNames($table);
        $builder = $db->table($table);

        if (in_array('user_id', $fields, true)) {
            $builder->where('user_id', $userId);
        }

        if (in_array('created_at', $fields, true)) {
            $builder->orderBy('created_at', 'DESC');
        } elseif (in_array('createdAt', $fields, true)) {
            $builder->orderBy('createdAt', 'DESC');
        }

        return $builder->limit($limit)->get()->getResultArray();
    }

    private function resolveAvailableTable(array $tableNames): ?string
    {
        $db = db_connect();

        foreach ($tableNames as $tableName) {
            if ($db->tableExists($tableName)) {
                return $tableName;
            }
        }

        return null;
    }
}
