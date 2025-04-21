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
        $today = Carbon::today();
        $currentDate = Carbon::now()->toDateString();
    
        // Jumlah transaksi hari ini (dari detail_sales)
        $todaySalesCount = detail_sales::whereDate('created_at', $currentDate)->count();
    
        // Ambil data total penjualan per hari
        $sales = detail_sales::selectRaw('DATE(created_at) AS date, COUNT(*) AS total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();
    
        // Penjualan member & non-member dari tabel saless
        $memberSales = Saless::whereNotNull('customer_id')
            ->whereDate('created_at', $today)
            ->count();
    
        $nonMemberSales = Saless::whereNull('customer_id')
            ->whereDate('created_at', $today)
            ->count();
    
        // Data chart line
        $labels = $sales->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M Y'))->toArray();
        $salesData = $sales->pluck('total')->toArray();
    
        // Data chart pie (produk)
        $productShell = detail_sales::with('product')
            ->selectRaw('product_id, SUM(amount) as total_amount')
            ->groupBy('product_id')
            ->get();
    
        $labelspieChart = $productShell->map(fn($item) => $item->product->name . ' : ' . $item->total_amount)->toArray();
        $salesDatapieChart = $productShell->map(fn($item) => $item->total_amount)->toArray();
    
        // Semua data dikirim ke view yang sama
        return view('module.dashboard.index', compact(
            'todaySalesCount',
            'memberSales',
            'nonMemberSales',
            'labels',
            'salesData',
            'labelspieChart',
            'salesDatapieChart'
        ));
    }

    public function show(Request $request, $id)
    {
        // Ambil sale berdasarkan id
        $sale = saless::with('detail_sales.product')->findOrFail($id);
        // check request apakah dia ngirim request poin yang artinya dia adalah member jika tidak ada maka dia non member
        if($request->check_poin){
            // Proses pengurangan point
            $customer = customers::where('id', $request->customer_id)->first();
            $sale->update([
                'total_point' => $customer->point,
                'total_pay' => $sale->total_pay - $customer->point,
                'total_return' => $sale->total_return + $customer->point,
                'total_discount' => $sale->total_price - $customer->point,
            ]);

            $customer->update([
                'name' => $request->name ? $request->name : $customer->name,
                'point' => 0
            ]);
        }
        if ($request->name) {
            $customer = customers::where('id', $request->customer_id)->first();
            $customer->update([
                'name' => $request->name
            ]);
        }
        return view('module.pembelian.print-sale', compact('sale'));
    }

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
        if (Auth::user()->role == 'employee') {
            return FacadesExcel::download(new salesimport, 'Penjualan.xlsx');
        }
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
