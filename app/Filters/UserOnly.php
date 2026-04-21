<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to continue.');
        }

        $role = strtolower(trim((string) session('role')));
        $allowedRoles = ['user', 'normal_user', 'normal'];

        if (in_array($role, $allowedRoles, true)) {
            return null;
        }

        return redirect()->to('/admin/manage-users')->with('error', 'Admins are not allowed to compute bills.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
