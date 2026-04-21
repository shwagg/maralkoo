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
        return $this->resolveAvailableTable(['billings', 'billing_history', 'billing_histories', 'bills']);
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
            'description' => $details,
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
            'client_name' => $bill['client_name'] ?? '',
            'kw_used' => $bill['kw_used'] ?? 0,
            'total_amount' => $bill['total_amount'] ?? 0,
            'createdAt' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
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

    protected function countBillingHistory(int $userId): int
    {
        $table = $this->getBillingTableName();
        if ($table === null) {
            return 0;
        }

        $db = db_connect();
        $fields = $db->getFieldNames($table);
        $builder = $db->table($table);

        if (in_array('user_id', $fields, true)) {
            $builder->where('user_id', $userId);
        }

        return (int) $builder->countAllResults();
    }

    protected function sumBillingTotal(int $userId): float
    {
        $table = $this->getBillingTableName();
        if ($table === null) {
            return 0.0;
        }

        $db = db_connect();
        $fields = $db->getFieldNames($table);

        $amountCol = in_array('total_amount', $fields, true) ? 'total_amount' : (in_array('amount_due', $fields, true) ? 'amount_due' : null);
        if ($amountCol === null) {
            return 0.0;
        }

        $builder = $db->table($table);

        if (in_array('user_id', $fields, true)) {
            $builder->where('user_id', $userId);
        }

        $row = $builder->selectSum($amountCol, 'total')->get()->getRowArray();

        return (float) ($row['total'] ?? 0.0);
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
