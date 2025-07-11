<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessHoursModel extends Model
{
    protected $table = 'business_hours';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'organization_id',
        'business_hours_type',
        'monday_start', 'monday_end',
        'tuesday_start', 'tuesday_end',
        'wednesday_start', 'wednesday_end',
        'thursday_start', 'thursday_end',
        'friday_start', 'friday_end',
        'saturday_start', 'saturday_end',
        'sunday_start', 'sunday_end'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    /**
     * Get business hours by organization ID
     */
    public function getByOrganizationId($organizationId = 1)
    {
        return $this->where('organization_id', $organizationId)->first();
    }
    
    /**
     * Get formatted business hours for display
     */
    public function getFormattedHours($businessHours)
    {
        if (!$businessHours) {
            return '24 Hours X 7 days';
        }
        
        switch ($businessHours['business_hours_type']) {
            case '24x7':
                return '24 Hours X 7 days';
            case '24x5':
                return '24 Hours X 5 days';
            case 'custom':
                return 'Custom Hours';
            default:
                return '24 Hours X 7 days';
        }
    }
    
    /**
     * Get weekdays
     */
    public function getWeekdays()
    {
        return [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
    }
    
    /**
     * Generate time options for dropdowns
     */
    public function getTimeOptions()
    {
        $times = [];
        for ($hour = 0; $hour < 24; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $time24 = sprintf('%02d:%02d', $hour, $minute);
                $time12 = date('g:i A', strtotime($time24));
                $times[$time24] = $time12;
            }
        }
        return $times;
    }
}
