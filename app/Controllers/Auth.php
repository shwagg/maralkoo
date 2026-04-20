<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function loginPage(): string
    {
        return view('auth/login');
    }

    public function login(): ResponseInterface
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[6]|max_length[255]',
        ]);

        $payload = [
            'username' => (string) $this->request->getPost('username'),
            'password' => (string) $this->request->getPost('password'),
        ];

        if (! $validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'message' => 'Please provide a valid username and password.',
                'errors' => $validation->getErrors(),
            ]);
        }

        $userModel = new UserModel();
        $user = $userModel->where('username', $payload['username'])->first();

        if (! $user || ! password_verify($payload['password'], (string) $user['password'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Invalid username or password.',
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

        $redirectTo = $user['role'] === 'admin' ? '/admin/dashboard' : '/user/dashboard';

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Login successful.',
            'redirect' => $redirectTo,
        ]);
    }
}
