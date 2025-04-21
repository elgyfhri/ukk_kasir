@extends('main')
@section('title', '| Pembelian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-end mb-3">
                        <div class="col text-start">
                            <div class="row">
                                @if (Auth::user()->role == 'employee')
                                <div class="col-6">
                                    <a href="{{ route('sales.exportexcel') }}" class="btn btn-info">
                                        Export Penjualan (.xlsx)
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if (Auth::user()->role == 'employee')
                        <div class="col text-end">
                            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i>
                                Tambah Penjualan
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="salesTable" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Pelanggan</th>
                                    <th scope="col">Tanggal Penjualan</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Dibuat Oleh</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($saless as $sale)
                                    <tr>
                                        <th>{{ $i++ }}</th>
                                        <td>{{ $sale->customer ? $sale->customer->name : 'NON-MEMBER' }}</td>
                                        <td>{{ $sale->sale_date }}</td>
                                        <td>{{ 'Rp. ' . number_format($sale->total_price,'0', ',', '.') }}</td>
                                        <td>{{ $sale->user->name ?? 'Pegawai Tidak Ada' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#lihat-{{$sale->id}}"><i class="mdi mdi-eye"></i></button>
                                                <a href="{{ route('download', $sale->id) }}" class="btn btn-info"><i class="mdi mdi-download"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Modal untuk melihat detail penjualan -->
                                    <div class="modal fade" id="lihat-{{$sale->id}}" tabindex="-1" aria-labelledby="modalLihat" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLihat">Detail Penjualan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <small>
                                                                Member Status : {{ $sale['customer'] ? 'Member' : 'Bukan Member' }}</br>
                                                                No. HP : {{ $sale['customer'] ? $sale['customer']['no_hp'] : '-' }}</br>
                                                                Poin Member : {{ $sale['customer'] ? $sale['customer']['point'] : '-' }}
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small>
                                                                Bergabung Sejak :
                                                                {{ $sale['customer'] ? \Carbon\Carbon::parse($sale['customer']['created_at'])->format('d F Y') : '-' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3 text-center mt-5">
                                                        <div class="col-3"><b>Nama Produk</b></div>
                                                        <div class="col-3"><b>Qty</b></div>
                                                        <div class="col-3"><b>Harga</b></div>
                                                        <div class="col-3"><b>Sub Total</b></div>
                                                    </div>
                                                    @foreach ($sale->detail_sales as $item)
                                                    <div class="row mb-1">
                                                        <div class="col-3 text-center">{{ $item->product->name }}</div>
                                                        <div class="col-3 text-center">{{ $item->amount }}</div>
                                                        <div class="col-3 text-center">Rp. {{ number_format($item->product->price, '0', ',', '.') }}</div>
                                                        <div class="col-3 text-center">Rp. {{ number_format($item->subtotal, '0', ',', '.') }}</div>
                                                    </div>
                                                    @endforeach
                                                    <div class="row text-center mt-3">
                                                        <div class="col-9 text-end"><b>Total</b></div>
                                                        <div class="col-3"><b>Rp. {{ number_format($sale->total_price, '0', ',', '.') }}</b></div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <center>
                                                            Dibuat pada : {{ $sale->created_at }}  <br> Oleh : {{ $sale->user->name ?? 'Pegawai Tidak Ada' }}
                                                        </center>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
