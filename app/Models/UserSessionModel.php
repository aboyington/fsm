<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSessionModel extends Model
{
    protected $table            = 'user_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'session_token', 'ip_address', 'user_agent', 
        'last_activity', 'created_at', 'expires_at'
    ];

    // Dates
    protected $useTimestamps = false; // We'll handle timestamps manually
    protected $dateFormat    = 'datetime';

    /**
     * Create a new session for user
     */
    public function createSession($userId, $ipAddress = null, $userAgent = null)
    {
        // Generate unique session token
        $token = bin2hex(random_bytes(32));
        
        // Calculate expiration time (24 hours from now)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $sessionData = [
            'user_id' => $userId,
            'session_token' => $token,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'last_activity' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => $expiresAt
        ];
        
        $this->insert($sessionData);
        
        return $token;
    }
    
    /**
     * Validate session token
     */
    public function validateSession($token)
    {
        $session = $this->where('session_token', $token)
                        ->where('expires_at >', date('Y-m-d H:i:s'))
                        ->first();
        
        if ($session) {
            // Update last activity
            $this->update($session['id'], [
                'last_activity' => date('Y-m-d H:i:s')
            ]);
            
            return $session;
        }
        
        return false;
    }
    
    /**
     * Get user by session token
     */
    public function getUserByToken($token)
    {
        $userModel = new UserModel();
        
        $session = $this->validateSession($token);
        
        if ($session) {
            $user = $userModel->where('id', $session['user_id'])
                             ->where('status', 'active')
                             ->first();
            
            if ($user) {
                unset($user['password']);
                return $user;
            }
        }
        
        return false;
    }
    
    /**
     * Remove session (logout)
     */
    public function removeSession($token)
    {
        return $this->where('session_token', $token)->delete();
    }
    
    /**
     * Remove all sessions for a user
     */
    public function removeAllUserSessions($userId)
    {
        return $this->where('user_id', $userId)->delete();
    }
    
    /**
     * Clean up expired sessions
     */
    public function cleanupExpiredSessions()
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
    
    /**
     * Get active sessions for a user
     */
    public function getUserActiveSessions($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->orderBy('last_activity', 'DESC')
                    ->findAll();
    }
}
