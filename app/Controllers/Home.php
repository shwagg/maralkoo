<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index(): ResponseInterface
    {
        if (! session('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = strtolower(trim((string) session('role')));
        if ($role === 'admin') {
            return redirect()->to('/admin/manage-users');
        }

        return redirect()->to('/user/compute-bill');
    }
}
