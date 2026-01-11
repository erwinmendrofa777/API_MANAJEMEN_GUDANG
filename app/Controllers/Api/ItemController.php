<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ItemModel;
use App\Entities\Item;

class ItemController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\ItemModel';
    protected $format    = 'json';

    // 1. GET /api/items (List Semua Barang)
    public function index()
    {
        $data = $this->model->findAll();
        return $this->respond($data);
    }

    // 2. GET /api/items/{id} (Detail Satu Barang)
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) {
            return $this->failNotFound('Data barang tidak ditemukan.');
        }
        return $this->respond($data);
    }

    // 3. POST /api/items (Tambah Barang)
    public function create()
    {
        // Terima input JSON atau Form Data
        $input = $this->request->getVar(); 
        
        // Validasi Manual karena ResourceController sedikit berbeda
        $rules = [
            'nama_barang' => 'required',
            'sku'         => 'required|is_unique[items.sku]',
            'stok'        => 'required|integer|greater_than_equal_to[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $item = new Item((array) $input);
        
        if ($this->model->save($item)) {
            // Ambil data yang baru disimpan untuk di-return
            $newItem = $this->model->find($this->model->getInsertID());
            return $this->respondCreated([
                'message' => 'Barang berhasil ditambahkan',
                'data'    => $newItem
            ]);
        }

        return $this->failServerError('Gagal menyimpan data.');
    }

// 4. PUT /api/items/{id} (Update Data Master)
    public function update($id = null)
    {
        // Cek apakah data ada di database
        $exist = $this->model->find($id);
        if (!$exist) {
            return $this->failNotFound('Barang tidak ditemukan.');
        }

        // SOLUSI: Gunakan getJSON(true) agar membaca Body RAW JSON dengan benar
        // 'true' artinya convert jadi Array Asosiatif
        $input = $this->request->getJSON(true); 

        // Jika input kosong (user salah format JSON), return error
        if (!$input) {
            return $this->fail('Data JSON tidak valid atau kosong.', 400);
        }

        $rules = [
            'nama_barang' => 'required',
            // Validasi Unique kecuali punya dia sendiri
            'sku'         => "required|is_unique[items.sku,id,{$id}]",
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Update data
        $exist->fill($input);
        
        // Kunci stok agar tidak berubah lewat endpoint ini
        if(isset($input['stok'])) unset($exist->stok);

        if ($this->model->save($exist)) {
            return $this->respond([
                'message' => 'Data barang diperbarui',
                'data'    => $exist
            ]);
        }
        
        return $this->failServerError('Gagal menyimpan perubahan.');
    }

    // 5. DELETE /api/items/{id} (Hapus Barang)
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) return $this->failNotFound('Barang tidak ditemukan.');

        if ($this->model->delete($id)) {
            return $this->respondDeleted(['message' => 'Barang berhasil dihapus']);
        }

        return $this->failServerError();
    }

    // 6. POST /api/items/stock/{id} (Endpoint Khusus Manajemen Stok)
    public function stock($id = null)
    {
        $item = $this->model->find($id);
        if (!$item) return $this->failNotFound('Barang tidak ditemukan.');

        // Input: { "type": "in"|"out", "qty": 10 }
        $type = $this->request->getVar('type'); 
        $qty  = (int) $this->request->getVar('qty');

        if ($qty <= 0) return $this->fail('Jumlah qty harus lebih dari 0.');

        $newStock = $item->stok;

        if ($type === 'in') {
            $newStock += $qty;
        } elseif ($type === 'out') {
            $newStock -= $qty;
            // Validasi Stok Negatif
            if ($newStock < 0) {
                return $this->fail('Stok tidak mencukupi untuk pengurangan ini.', 400);
            }
        } else {
            return $this->fail('Tipe transaksi harus "in" atau "out".', 400);
        }

        $item->stok = $newStock;
        
        if ($this->model->save($item)) {
            return $this->respond([
                'message'      => 'Stok berhasil diperbarui',
                'current_stock'=> $newStock,
                'item'         => $item
            ]);
        }

        return $this->failServerError();
    }
}