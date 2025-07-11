<?php

namespace App\Models;

use CodeIgniter\Model;

class CurrencyModel extends Model
{
    protected $table = 'currencies';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'symbol',
        'iso_code',
        'exchange_rate',
        'thousand_separator',
        'decimal_spaces',
        'decimal_separator',
        'is_base',
        'is_active'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'symbol' => 'required',
        'iso_code' => 'required|exact_length[3]',
        'exchange_rate' => 'required|decimal',
        'thousand_separator' => 'required',
        'decimal_spaces' => 'required|integer',
        'decimal_separator' => 'required',
    ];

    public function getBaseCurrency()
    {
        return $this->where('is_base', 1)->first();
    }

    public function getActiveCurrencies()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
