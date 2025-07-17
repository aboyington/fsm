<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserSessionModel;

class CleanupExpiredSessions extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Sessions';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'sessions:cleanup';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Clean up expired user sessions from the database';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'sessions:cleanup';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $userSessionModel = new UserSessionModel();
        
        CLI::write('Cleaning up expired sessions...', 'yellow');
        
        // Get count of expired sessions before cleanup
        $expiredCount = $userSessionModel
            ->where('expires_at <', date('Y-m-d H:i:s'))
            ->countAllResults();
        
        if ($expiredCount > 0) {
            // Clean up expired sessions
            $deleted = $userSessionModel->cleanupExpiredSessions();
            
            if ($deleted) {
                CLI::write("Successfully cleaned up {$expiredCount} expired sessions.", 'green');
            } else {
                CLI::write('No expired sessions found to clean up.', 'green');
            }
        } else {
            CLI::write('No expired sessions found to clean up.', 'green');
        }
        
        // Show active sessions count
        $activeCount = $userSessionModel
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->countAllResults();
            
        CLI::write("Active sessions: {$activeCount}", 'blue');
    }
}
