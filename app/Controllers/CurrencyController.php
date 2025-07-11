<?php

namespace App\Controllers;

use App\Models\CurrencyModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class CurrencyController extends Controller
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new CurrencyModel();
        $data['currencies'] = $model->findAll();
        $data['title'] = 'Currency Settings';
        $data['activeTab'] = 'currency';

        return view('currency/index', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $model = new CurrencyModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'symbol' => $this->request->getPost('symbol'),
            'iso_code' => strtoupper($this->request->getPost('iso_code')),
            'exchange_rate' => $this->request->getPost('exchange_rate'),
            'thousand_separator' => $this->request->getPost('thousand_separator'),
            'decimal_spaces' => $this->request->getPost('decimal_spaces'),
            'decimal_separator' => $this->request->getPost('decimal_separator'),
            'is_active' => 1
        ];

        if ($model->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Currency added successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add currency', 'errors' => $model->errors()]);
        }
    }

    public function update($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $model = new CurrencyModel();

        $data = [
            'id' => $id,
            'exchange_rate' => $this->request->getPost('exchange_rate'),
            'thousand_separator' => $this->request->getPost('thousand_separator'),
            'decimal_spaces' => $this->request->getPost('decimal_spaces'),
            'decimal_separator' => $this->request->getPost('decimal_separator')
        ];

        if ($model->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Currency updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update currency', 'errors' => $model->errors()]);
        }
    }

    public function getCurrency($id)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $model = new CurrencyModel();
        $currency = $model->find($id);

        if ($currency) {
            return $this->response->setJSON(['success' => true, 'currency' => $currency]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Currency not found'])->setStatusCode(404);
        }
    }
}

