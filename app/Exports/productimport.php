<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class productimport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Ambil semua data produk
    */
    public function collection()
    {
        return Products::orderBy('id', 'desc')->get();
    }

    /**
    * Judul kolom di Excel
    */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Produk',
            'Kategori',
            'Harga',
            'Stok',
           
        ];
    }

    /**
    * Format tiap baris data
    */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            optional($product->category)->name ?? 'Tidak ada kategori',
            'Rp. ' . number_format($product->price, 0, ',', '.'),
            $product->stock,
           
            // $product->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
