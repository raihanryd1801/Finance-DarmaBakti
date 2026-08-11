<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke items (1 Invoice punya banyak barang)
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}