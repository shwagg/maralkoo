<?php

namespace App\Controllers;

use App\Controllers\Concerns\TracksAuditAndBilling;
use App\Models\UserModel;
use Config\Database;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    use TracksAuditAndBilling;

    public function loginPage(): ResponseInterface|string
    {
        if (session('isLoggedIn')) {
            $role = strtolower(trim((string) session('role')));

            if ($role === 'admin') {
                return redirect()->to('/admin/manage-users');
            }

            return redirect()->to('/user/compute-bill');
        }

        return view('auth/login');
    }

    public function login(): ResponseInterface
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'identifier' => 'required|min_length[3]|max_length[100]',
            'password' => 'required|min_length[6]|max_length[255]',
        ]);

        $identifier = trim((string) ($this->request->getPost('identifier') ?? $this->request->getPost('username')));

        $payload = [
            'identifier' => $identifier,
            'password' => (string) $this->request->getPost('password'),
        ];

        if (! $validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'message' => 'Please provide a valid username/email and password.',
                'errors' => $validation->getErrors(),
            ]);
        }

        $userModel = new UserModel();
        $canLoginWithEmail = Database::connect()->fieldExists('email', 'users');

        if ($canLoginWithEmail) {
            $user = $userModel
                ->groupStart()
                ->where('username', $payload['identifier'])
                ->orWhere('email', $payload['identifier'])
                ->groupEnd()
                ->first();
        } else {
            $user = $userModel->where('username', $payload['identifier'])->first();
        }

        if (! $user || ! password_verify($payload['password'], (string) $user['password'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Invalid username/email or password.',
            ]);
        }

        $session = session();
        $session->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'fullname' => $user['fullname'],
            'username' => $user['username'],
            'role' => $user['role'],
        ]);

        $this->appendAuditTrail('login', 'User logged in successfully.');

        $redirectTo = $user['role'] === 'admin' ? '/admin/manage-users' : '/user/compute-bill';

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Login successful.',
            'redirect' => $redirectTo,
        ]);
    }

    public function logout(): RedirectResponse
    {
        $this->appendAuditTrail('logout', 'User logged out.');
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Logged out successfully.');
    }
}
