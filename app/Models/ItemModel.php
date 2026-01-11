<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Item;

class ItemModel extends Model
{
    protected $table      = 'items';
    protected $primaryKey = 'id';
    protected $returnType = Item::class; // Entity
    protected $useSoftDeletes = false;

    // Kolom yang boleh diisi user
    protected $allowedFields = ['nama_barang', 'sku', 'stok'];

    protected $useTimestamps = true;
}