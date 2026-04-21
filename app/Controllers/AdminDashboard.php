<?php

namespace App\Controllers;

use App\Controllers\Concerns\TracksAuditAndBilling;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class AdminDashboard extends BaseController
{
    use TracksAuditAndBilling;

    public function index(): RedirectResponse
    {
        return redirect()->to('/admin/manage-users');
    }

    public function manageUsers(): string
    {
        return view('admin/manageusers', [
            'fullname' => (string) session('fullname'),
        ]);
    }

    public function viewUsers(): string
    {
        $userModel = new UserModel();

        return view('admin/viewusers', [
            'fullname' => (string) session('fullname'),
            'users' => $userModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function auditLogs(): string
    {
        return view('admin/auditlogs', [
            'fullname' => (string) session('fullname'),
            'auditTrails' => $this->listAuditTrails(null, 30),
            'auditStorageReady' => $this->getAuditTableName() !== null,
        ]);
    }

    public function createUser(): RedirectResponse
    {
        $validation = service('validation');
        $validation->setRules([
            'fullname' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[255]|is_unique[users.email]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]|max_length[255]',
            'role' => 'required|in_list[admin,user]',
        ]);

        $payload = [
            'fullname' => trim((string) $this->request->getPost('fullname')),
            'email' => strtolower(trim((string) $this->request->getPost('email'))),
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
            'role' => strtolower(trim((string) $this->request->getPost('role'))),
        ];

        if (! $validation->run($payload)) {
            return redirect()->to('/admin/manage-users')->with('error', implode(' ', $validation->getErrors()));
        }

        $userModel = new UserModel();
        $userModel->insert([
            'fullname' => $payload['fullname'],
            'email' => $payload['email'],
            'username' => $payload['username'],
            'password' => password_hash($payload['password'], PASSWORD_DEFAULT),
            'role' => $payload['role'],
        ]);

        $this->appendAuditTrail('admin_create_user', 'Created user: ' . $payload['username']);

        return redirect()->to('/admin/manage-users')->with('success', 'User account created successfully.');
    }

    public function updateUser(int $id): RedirectResponse
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! is_array($user)) {
            return redirect()->to('/admin/view-users')->with('error', 'User account not found.');
        }

        $fullname = trim((string) $this->request->getPost('fullname'));
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $username = trim((string) $this->request->getPost('username'));
        $role = strtolower(trim((string) $this->request->getPost('role')));
        $password = (string) $this->request->getPost('password');

        if ($fullname === '' || strlen($fullname) < 3 || strlen($fullname) > 100) {
            return redirect()->to('/admin/view-users')->with('error', 'Full name must be between 3 and 100 characters.');
        }

        if ($username === '' || strlen($username) < 3 || strlen($username) > 50) {
            return redirect()->to('/admin/view-users')->with('error', 'Username must be between 3 and 50 characters.');
        }

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
            return redirect()->to('/admin/view-users')->with('error', 'Please provide a valid email address.');
        }

        if (! in_array($role, ['admin', 'user'], true)) {
            return redirect()->to('/admin/view-users')->with('error', 'Role must be either admin or user.');
        }

        $existing = $userModel->where('username', $username)->where('id !=', $id)->first();
        if (is_array($existing)) {
            return redirect()->to('/admin/view-users')->with('error', 'Username is already in use.');
        }

        $existingEmail = $userModel->where('email', $email)->where('id !=', $id)->first();
        if (is_array($existingEmail)) {
            return redirect()->to('/admin/view-users')->with('error', 'Email address is already in use.');
        }

        $updateData = [
            'fullname' => $fullname,
            'email' => $email,
            'username' => $username,
            'role' => $role,
        ];

        if ($password !== '') {
            if (strlen($password) < 6 || strlen($password) > 255) {
                return redirect()->to('/admin/view-users')->with('error', 'Password must be between 6 and 255 characters.');
            }

            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $updateData);

        $this->appendAuditTrail('admin_update_user', 'Updated user account ID: ' . $id);

        return redirect()->to('/admin/view-users')->with('success', 'User account updated successfully.');
    }

    public function deleteUser(int $id): RedirectResponse
    {
        if ($id === (int) session('userId')) {
            return redirect()->to('/admin/view-users')->with('error', 'You cannot delete your own account.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! is_array($user)) {
            return redirect()->to('/admin/view-users')->with('error', 'User account not found.');
        }

        $db = db_connect();

        try {
            $db->transBegin();

            $dependentTables = [
                'audit_trails',
                'audit_logs',
                'billings',
                'billing_history',
                'billing_histories',
                'bills',
            ];

            foreach ($dependentTables as $table) {
                if (! $db->tableExists($table)) {
                    continue;
                }

                $fields = $db->getFieldNames($table);
                if (! in_array('user_id', $fields, true)) {
                    continue;
                }

                $db->table($table)->where('user_id', $id)->delete();
            }

            if (! $userModel->delete($id) || $db->transStatus() === false) {
                throw new \RuntimeException('Failed to delete user and related records.');
            }

            $db->transCommit();
        } catch (\Throwable $exception) {
            $db->transRollback();
            log_message('error', 'Failed deleting user ID {id}: {message}', [
                'id' => $id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->to('/admin/view-users')->with('error', 'Unable to delete user account because related records still exist.');
        }

        $this->appendAuditTrail('admin_delete_user', 'Deleted user account ID: ' . $id);

        return redirect()->to('/admin/view-users')->with('success', 'User account deleted successfully.');
    }
}
