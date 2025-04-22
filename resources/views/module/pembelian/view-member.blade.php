@extends('main')
@section('title', '| Pembelian')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('sales.print.show', ['id' => $sale['id']]) }}" method="GET">
                            @csrf
                            @if (Session::get('error'))
                                <div class="alert alert-danger">
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="table table-bordered">
                                        <table>
                                            <tr class="tabletitle">
                                                <td class="item">
                                                    Nama Produk
                                                </td>
                                                <td class="item">
                                                    QTy
                                                </td>
                                                <td class="item">
                                                    Harga
                                                </td>
                                                <td class="item">
                                                    Sub Total
                                                </td>
                                            </tr>
                                            @foreach ($sale['detail_sales'] as $item)
                                                <tr class="service">
                                                    <td class="tableitem">
                                                        <p class="itemtext">{{ $item['product']['name'] }}</p>
                                                    </td>
                                                    <td class="tableitem">
                                                        <p class="itemtext">{{ $item['amount'] }}</p>
                                                    </td>
                                                    <td class="tableitem">
                                                        <p class="itemtext">Rp.
                                                            {{ number_format($item['product']['price'], 0, ',', '.') }}
                                                        </p>
                                                    </td>
                                                    <td class="tableitem">
                                                        <p class="itemtext">
                                                            Rp. {{ number_format($item['amount'] * $item['product']['price'], 0, ',', '.') }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="tabletitle">
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <h4>Total Harga</h4>
                                                </td>
                                                <td>
                                                    <h4>Rp. {{ number_format($sale['total_price'], 0, ',', '.') }}</h4>
                                                </td>
                                            </tr>
                                            <tr class="tabletitle">
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <h4>Total Bayar</h4>
                                                </td>
                                                <td>
                                                    <h4>Rp. {{ number_format($sale['total_pay'], 0, ',', '.') }}</h4>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="row">
                                        <input type="hidden" name="sale_id" value="{{ $sale['id'] }}">
                                        <input type="hidden" name="customer_id" value="{{ $sale['customer_id'] }}">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nama Member (identitas)</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                required value="{{ $sale['customer']['name'] }}">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label class="form-label">Pending Point</label>
                                            <input type="text" class="form-control" value="{{ $sale['customer']['pending_point'] ?? 0 }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Available Point</label>
                                            <input type="text" class="form-control" value="{{ $sale['customer']['available_point'] ?? 0 }}" disabled>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="Ya" id="check2"
                                                {{ $notFirst ? '' : 'disabled' }} name="check_poin">
                                            <label class="form-check-label" for="check2">
                                                Gunakan poin
                                            </label>
                                            <small class="text-danger">
                                                {{ $notFirst ? '' : 'Poin tidak dapat digunakan pada pembelanjaan pertama.' }}
                                            </small>
                                        </div>
                                        
                                      
                                        
                                        
                                    </div>
                                    <div class="row text-end">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" type="submit">Selanjutnya</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
