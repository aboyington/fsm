<?php

namespace App\Models;

use CodeIgniter\Model;

class FiscalYearModel extends Model
{
    protected $table = 'fiscal_years';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'organization_id',
        'fiscal_year_start',
        'fiscal_year_end',
        'current_fiscal_year',
        'fiscal_year_format'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'fiscal_year_start' => 'required|regex_match[/^\d{2}-\d{2}$/]',
        'fiscal_year_end' => 'required|regex_match[/^\d{2}-\d{2}$/]',
        'current_fiscal_year' => 'required|numeric|min_length[4]|max_length[4]',
        'fiscal_year_format' => 'required|in_list[calendar,custom]'
    ];
    
    /**
     * Get fiscal year by organization ID
     */
    public function getByOrganizationId($organizationId = 1)
    {
        return $this->where('organization_id', $organizationId)->first();
    }
    
    /**
     * Get month options for dropdowns
     */
    public function getMonthOptions()
    {
        return [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];
    }
    
    /**
     * Get day options for dropdowns
     */
    public function getDayOptions()
    {
        $days = [];
        for ($i = 1; $i <= 31; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $days[$day] = $i;
        }
        return $days;
    }
    
    /**
     * Get year options for dropdowns (current year +/- 5 years)
     */
    public function getYearOptions()
    {
        $currentYear = date('Y');
        $years = [];
        for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }
}
