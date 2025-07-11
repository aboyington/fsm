<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthFilter implements FilterInterface
{
    /**
     * Check if user is authenticated
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');
        $token = null;

        // Check for token in Authorization header
        $authHeader = $request->getHeaderLine('Authorization');
        if ($authHeader) {
            $matches = [];
            if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        // Check for token in X-API-Token header (fallback)
        if (!$token) {
            $token = $request->getHeaderLine('X-API-Token');
        }

        // For non-API routes, check session
        if (!$token) {
            $session = session();
            $token = $session->get('auth_token');
        }

        if (!$token) {
            // For API routes, always return JSON
            if (strpos($request->getPath(), 'api/') !== false) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized: No authentication token provided'
                ])->setStatusCode(401);
            }
            
            // For other AJAX requests, return JSON error
            if ($request->isAJAX()) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized: No authentication token provided'
                ])->setStatusCode(401);
            }
            
            // For regular web requests, redirect to login
            return redirect()->to('/login');
        }

        // Validate token
        $userModel = new UserModel();
        $user = $userModel->validateToken($token);

        if (!$user) {
            if ($request->isAJAX() || strpos($request->getPath(), 'api/') !== false) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized: Invalid or expired token'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }

        // Store user data in request for later use
        $request->user = $user;

        // Check role-based access if arguments provided
        if ($arguments && !in_array($user['role'], $arguments)) {
            if ($request->isAJAX() || strpos($request->getPath(), 'api/') !== false) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'Forbidden: Insufficient permissions'
                ])->setStatusCode(403);
            }
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to access this page.');
        }
    }

    /**
     * After request processing
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
