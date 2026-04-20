<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to continue.');
        }

        $role = strtolower(trim((string) session('role')));
        if ($role === 'admin') {
            return null;
        }

        return redirect()->to('/user/dashboard')->with('error', 'You do not have access to the admin dashboard.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
