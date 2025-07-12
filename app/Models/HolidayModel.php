<?php

namespace App\Models;

use CodeIgniter\Model;

class HolidayModel extends Model
{
    protected $table = 'holidays';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'date',
        'year',
        'description',
        'is_recurring',
        'created_by',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'date' => 'required|valid_date',
        'year' => 'required|integer|greater_than[1900]|less_than[3000]',
        'is_recurring' => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Holiday name is required',
            'min_length' => 'Holiday name must be at least 2 characters long',
            'max_length' => 'Holiday name cannot exceed 100 characters'
        ],
        'date' => [
            'required' => 'Holiday date is required',
            'valid_date' => 'Please provide a valid date'
        ],
        'year' => [
            'required' => 'Year is required',
            'integer' => 'Year must be a valid integer',
            'greater_than' => 'Year must be greater than 1900',
            'less_than' => 'Year must be less than 3000'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get holidays for a specific year
     */
    public function getHolidaysByYear($year)
    {
        return $this->where('year', $year)
                   ->orderBy('date', 'ASC')
                   ->findAll();
    }

    /**
     * Get all available years that have holidays
     */
    public function getAvailableYears()
    {
        return $this->distinct()
                   ->select('year')
                   ->orderBy('year', 'DESC')
                   ->findColumn('year');
    }

    /**
     * Add or update holidays for a specific year
     */
    public function saveHolidaysForYear($year, $holidays)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete existing holidays for this year
            $this->where('year', $year)->delete();

            // Insert new holidays
            foreach ($holidays as $holiday) {
                $holiday['year'] = $year;
                $holiday['created_at'] = date('Y-m-d H:i:s');
                $holiday['updated_at'] = date('Y-m-d H:i:s');
                
                $this->insert($holiday);
            }

            $db->transComplete();
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * Check if a specific date is a holiday
     */
    public function isHoliday($date)
    {
        $year = date('Y', strtotime($date));
        return $this->where('year', $year)
                   ->where('date', $date)
                   ->countAllResults() > 0;
    }

    /**
     * Get holidays between two dates
     */
    public function getHolidaysBetweenDates($startDate, $endDate)
    {
        return $this->where('date >=', $startDate)
                   ->where('date <=', $endDate)
                   ->orderBy('date', 'ASC')
                   ->findAll();
    }

    /**
     * Get default holidays for a year (common holidays)
     */
    public function getDefaultHolidays($year)
    {
        return [
            [
                'name' => 'New Year\'s Day',
                'date' => $year . '-01-01',
                'year' => $year,
                'description' => 'New Year\'s Day',
                'is_recurring' => 1
            ],
            [
                'name' => 'Independence Day',
                'date' => $year . '-07-04',
                'year' => $year,
                'description' => 'Independence Day',
                'is_recurring' => 1
            ],
            [
                'name' => 'Christmas Day',
                'date' => $year . '-12-25',
                'year' => $year,
                'description' => 'Christmas Day',
                'is_recurring' => 1
            ]
        ];
    }
}
