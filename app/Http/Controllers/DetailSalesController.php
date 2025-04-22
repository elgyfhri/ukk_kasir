<?php

namespace App\Http\Controllers;

use App\Exports\salesimport;
use App\Models\customers;
use App\Models\detail_sales;
use App\Models\saless;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class DetailSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentDate = Carbon::now()->toDateString();

        // Hitung jumlah transaksi hari ini
        $todaySalesCount = detail_sales::whereDate('created_at', $currentDate)->count();
        
        // Ambil seluruh data penjualan tanpa batasan bulan atau tahun
        $sales = detail_sales::selectRaw('DATE(created_at) AS date, COUNT(*) AS total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();
        
        $detail_sales = detail_sales::with('saless', 'product')->get();
        
        // Ubah hasil query menjadi array terstruktur
        $labels = $sales->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M Y'))->toArray();
        $salesData = $sales->pluck('total')->toArray();

        $productShell = detail_sales::with('product')
        ->selectRaw('product_id, SUM(amount) as total_amount')
        ->groupBy('product_id')
        ->get();
    
        // Ambil nama produk sebagai label dan jumlah produk terjual sebagai data
        $total = $productShell->sum('total_amount');

        $labelspieChart = $productShell->map(function($item) use ($total) {
            $percentage = $total > 0 ? ($item->total_amount / $total) * 100 : 0;
            return $item->product->name . ' : ' . round($percentage, 2) . '%';
        })->toArray();
        
        $salesDatapieChart = $productShell->map(function($item) use ($total) {
            return $total > 0 ? round(($item->total_amount / $total) * 100, 2) : 0;
        })->toArray();
        
        
        return view('module.dashboard.index', compact('labels', 'salesData', 'detail_sales', 'todaySalesCount', 'productShell', 'labelspieChart', 'salesDatapieChart'));
        
    }


   public function show(Request $request, $id)
{
    $sale = saless::with('detail_sales.product', 'customer')->findOrFail($id);
    $customer = customers::find($request->customer_id);

    $usedPoint = 0; // Default

    if ($customer) {
        // Update nama customer jika ada input 'name'
        if ($request->filled('name')) {
            $customer->name = $request->name;
        }

        // Jika checkbox 'Gunakan poin' dicentang
        if ($request->check_poin && $customer->available_point > 0) {
            $usedPoint = $customer->available_point;

            $sale->update([
                'total_pay' => $sale->total_pay - $usedPoint,
                'total_return' => $sale->total_return + $usedPoint,
                'total_discount' => $sale->total_price - $usedPoint,
            ]);

            $customer->available_point = 0;
        }

        $customer->save();
    }

    // Kirim juga nilai usedPoint ke view jika perlu
    return view('module.pembelian.print-sale', compact('sale', 'usedPoint'));
    
}


    
    // public function show(Request $request, $id)
    // {
    //     $sale = saless::with('detail_sales.product', 'customer')->findOrFail($id);
    //     $customer = customers::find($request->customer_id);
    
    //     if ($customer) {
    //         // Update nama customer jika diubah
    //         if ($request->filled('name')) {
    //             $customer->name = $request->name;
    //         }
    
    //         // Jika checkbox 'Gunakan poin' dicentang
    //         if ($request->has('check_poin') && $request->check_poin === 'Ya') {
    //             $usedPoint = $customer->available_point;
    
    //             // Update nilai transaksi
    //             $sale->update([
    //                 'total_pay' => $sale->total_pay - $usedPoint,
    //                 'total_return' => $sale->total_return + $usedPoint,
    //                 'total_discount' => $sale->total_price - $usedPoint,
    //                 'total_point' => $usedPoint, // ⬅️ hanya isi di sini
    //             ]);
    
    //             // Kurangi poin yang dipakai
    //             $customer->available_point = 0;
    //         } else {
    //             // Jika poin tidak digunakan
    //             $customer->available_point += $customer->pending_point;
    //             $customer->pending_point = 0;
    
    //             // Jangan isi total_point!
    //             $sale->update([
    //                 'total_point' => 0,
    //             ]);
    //         }
    
    //         $customer->save();
    //     }
    
    //     return view('module.pembelian.print-sale', compact('sale'));
    // }

    


    public function downloadPDF($id) {
        try {
            $sale = saless::with('detail_sales.product')->findOrFail($id);

            $pdf = FacadePdf::loadView('module.pembelian.download', ['sale' => $sale]);
            Log::info('PDF berhasil diunduh untuk transaksi dengan ID ' . $id);

            return $pdf->download('Surat_receipt.pdf');
        } catch (\Exception $e) {
            Log::error('Gagal mengunduh PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh PDF');
        }
    }

    public function exportexcel()
    {
        return FacadesExcel::download(new salesimport, 'Penjualan.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detail_sales $detail_sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detail_sales $detail_sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detail_sales $detail_sales)
    {
        //
    }
}
