<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\TerritoryModel;

class AssetsController extends BaseController
{
    protected $assetModel;
    protected $clientModel;
    protected $contactModel;
    protected $territoryModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->territoryModel = new TerritoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Assets Management',
            'assets' => $this->assetModel->getAssetsWithRelations(),
            'companies' => $this->clientModel->getClients(),
            'contacts' => $this->contactModel->where('status', 'active')->findAll(),
            'territories' => $this->territoryModel->where('status', 'active')->findAll(),
            'total_assets' => $this->assetModel->countAll(),
            'active_assets' => $this->assetModel->where('status', 'active')->countAllResults(),
            'inactive_assets' => $this->assetModel->where('status', 'inactive')->countAllResults(),
        ];

        return view('assets/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $validationRules = [
                'asset_name' => 'required|max_length[255]',
                'asset_number' => 'permit_empty|max_length[100]',
                'description' => 'permit_empty',
                'product' => 'permit_empty|max_length[255]',
                'parent_asset' => 'permit_empty|integer',
                'giai' => 'permit_empty|max_length[100]',
                'ordered_date' => 'permit_empty|valid_date',
                'installation_date' => 'permit_empty|valid_date',
                'purchased_date' => 'permit_empty|valid_date',
                'warranty_expiration' => 'permit_empty|valid_date',
                'company_id' => 'permit_empty|integer',
                'contact_id' => 'permit_empty|integer',
                'address' => 'permit_empty',
                'status' => 'required|in_list[active,inactive,maintenance,retired]'
            ];

            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $data = [
                'asset_name' => $this->request->getPost('asset_name'),
                'asset_number' => $this->request->getPost('asset_number'),
                'description' => $this->request->getPost('description'),
                'product' => $this->request->getPost('product'),
                'parent_asset' => $this->request->getPost('parent_asset') ?: null,
                'giai' => $this->request->getPost('giai'),
                'ordered_date' => $this->request->getPost('ordered_date') ?: null,
                'installation_date' => $this->request->getPost('installation_date') ?: null,
                'purchased_date' => $this->request->getPost('purchased_date') ?: null,
                'warranty_expiration' => $this->request->getPost('warranty_expiration') ?: null,
                'company_id' => $this->request->getPost('company_id') ?: null,
                'contact_id' => $this->request->getPost('contact_id') ?: null,
                'address' => $this->request->getPost('address'),
                'status' => $this->request->getPost('status') ?: 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->assetModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Asset created successfully.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create asset.'
                ]);
            }
        }
    }

    public function get($id)
    {
        $asset = $this->assetModel->find($id);
        
        if (!$asset) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Asset not found.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $asset
        ]);
    }

    public function update($id)
    {
        $asset = $this->assetModel->find($id);
        
        if (!$asset) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Asset not found.'
            ]);
        }

        $validationRules = [
            'asset_name' => 'required|max_length[255]',
            'asset_number' => 'permit_empty|max_length[100]',
            'description' => 'permit_empty',
            'product' => 'permit_empty|max_length[255]',
            'parent_asset' => 'permit_empty|integer',
            'giai' => 'permit_empty|max_length[100]',
            'ordered_date' => 'permit_empty|valid_date',
            'installation_date' => 'permit_empty|valid_date',
            'purchased_date' => 'permit_empty|valid_date',
            'warranty_expiration' => 'permit_empty|valid_date',
            'company_id' => 'permit_empty|integer',
            'contact_id' => 'permit_empty|integer',
            'address' => 'permit_empty',
            'status' => 'required|in_list[active,inactive,maintenance,retired]'
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'asset_name' => $this->request->getPost('asset_name'),
            'asset_number' => $this->request->getPost('asset_number'),
            'description' => $this->request->getPost('description'),
            'product' => $this->request->getPost('product'),
            'parent_asset' => $this->request->getPost('parent_asset') ?: null,
            'giai' => $this->request->getPost('giai'),
            'ordered_date' => $this->request->getPost('ordered_date') ?: null,
            'installation_date' => $this->request->getPost('installation_date') ?: null,
            'purchased_date' => $this->request->getPost('purchased_date') ?: null,
            'warranty_expiration' => $this->request->getPost('warranty_expiration') ?: null,
            'company_id' => $this->request->getPost('company_id') ?: null,
            'contact_id' => $this->request->getPost('contact_id') ?: null,
            'address' => $this->request->getPost('address'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->assetModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update asset.'
            ]);
        }
    }

    public function delete($id)
    {
        $asset = $this->assetModel->find($id);
        
        if (!$asset) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Asset not found.'
            ]);
        }

        if ($this->assetModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset deleted successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete asset.'
            ]);
        }
    }

    public function search()
    {
        $searchTerm = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $companyId = $this->request->getGet('company_id');

        $builder = $this->assetModel->getAssetsWithRelationsBuilder();

        if (!empty($searchTerm)) {
            $builder->groupStart()
                    ->like('assets.asset_name', $searchTerm)
                    ->orLike('assets.asset_number', $searchTerm)
                    ->orLike('assets.product', $searchTerm)
                    ->orLike('assets.giai', $searchTerm)
                    ->orLike('clients.client_name', $searchTerm)
                    ->groupEnd();
        }

        if (!empty($status)) {
            $builder->where('assets.status', $status);
        }

        if (!empty($companyId)) {
            $builder->where('assets.company_id', $companyId);
        }

        $assets = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $assets
        ]);
    }

    public function getContactsByCompany($companyId)
    {
        $contacts = $this->contactModel->where(['company_id' => $companyId, 'status' => 'active'])->findAll();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $contacts
        ]);
    }
}
