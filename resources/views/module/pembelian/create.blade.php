@extends('main')
@section('title', '| Pilih Product')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <section>
                            <div class="text-center container">
                                <div class="row" id="product-list">
                                    {{-- @foreach ($products as $item)
                                    <p hidden id="id-product">{{ $item->id }}</p>
                                    <div class="col-lg-3 col-md-12 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">{{ $item->name }}</h5>
                                                <p>Stok {{ $item->stock }}</p>
                                                <h6 class="mb-3">Rp. {{ number_format($item->price,'0', ',', '.') }}</h6>
                                                <p id="price_{{ $item->id }}" hidden>{{ $item->price }}</p>
                                                <center>
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 0px 10px 0px 10px; cursor: pointer;" id="minus"><b>-</b></td>
                                                            <td style="padding: 0px 10px 0px 10px;" class="num" id="quantity_{{ $item->id }}">0</td>
                                                            <td style="padding: 0px 10px 0px 10px; cursor: pointer;" id="plus"><b>+</b></td>
                                                        </tr>
                                                    </table>
                                                </center>
                                                <br>
                                                <p>Sub Total <b><span id="total">Rp. 0</span></b></p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach --}}
                                    {{-- <div id="userList"></div> --}}
                                </div>
                            </div>
                        </section>

                    </div>
                    <div class="row fixed-bottom d-flex justify-content-end align-content-center"
                        style="margin-left: 18%; width: 83%; height: 70px; border-top: 3px solid #EEE4B1; background-color: white;">
                        <div class="col text-center" style="margin-right: 50px;">
                            <form action="{{ route('sales.store') }}" method="post">
                                @csrf
                                {{-- <input type="text" name="shop[]" id="shop"> --}}
                                <div id="shop"></div>
                                <button class="btn btn-primary">Selanjutnya</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let products = @json($products);
            $.each(products, function(key, item) {
                $("#product-list").append(`
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light">
                        <img src="{{ asset('image/`+item.image+`') }}" class="w-50" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-3">` + item.name + `</h5>
                        <p>Stok ` + item.stock + `</p>
                        <h6 class="mb-3">Rp. ` + formatRupiah(item.price) + `</h6>
                        <p id="price_` + item.id + `" hidden>` + item.price + `</p>
                        <center>
                            <table>
                                <tr>
                                    <td style="padding: 0px 10px 0px 10px; cursor: pointer;" id="minus_` + item.id + `"><b>-</b></td>
                                    <td style="padding: 0px 10px 0px 10px;" class="num" id="quantity_` + item.id + `">0</td>
                                    <td style="padding: 0px 10px 0px 10px; cursor: pointer;" id="plus_` + item.id + `"><b>+</b></td>
                                </tr>
                            </table>
                        </center>
                        <br>
                        <p>Sub Total <b><span id="total_` + item.id + `">Rp. 0</span></b></p>
                    </div>
                </div>

            </div>
        `);

                function formatRupiah(angka) {
                    let numberString = angka.toString();
                    let sisa = numberString.length % 3;
                    let rupiah = numberString.substr(0, sisa);
                    let ribuan = numberString.substr(sisa).match(/\d{3}/g);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    return rupiah;
                }

                $('#plus_' + item.id).click(function(e) {
                    const elem = $(this).prev();
                    const getId = elem.attr("id").split("_")[1]; // To find the price id
                    const price = $("#price_" + getId).html(); // price amount
                    let qty = parseInt(elem.html()) + 1;

                    // Cek apakah qty melebihi stok
                    if (qty > item.stock) {
                        alert("Stok kurang!");
                        elem.html(item.stock); // Set qty ke stok maksimum
                        qty = item.stock; // Set qty ke stok maksimum
                    }

                    elem.html(qty);
                    let total = price * qty;
                    $("#total_" + item.id).html("Rp. " + formatRupiah(
                    total)); // set total, assume total is qty * price

                    if (qty > 0) {
                        $('#shop').append(`
                            <input id="shop_item_${item.id}" name="shop[]" value="${item.id};${item.name};${item.price};${qty};${total}" type="hidden" />
                        `);
                    }

                });

                $('#minus_' + item.id).click(function(e) {
    const elem = $(this).next();
    const getId = elem.attr("id").split("_")[1];
    const price = parseInt($("#price_" + getId).html());
    let qty = parseInt(elem.html());

    if (qty > 0) {
        qty--;
    }

    elem.html(qty);
    let total = price * qty;
    $("#total_" + item.id).html("Rp. " + formatRupiah(total));

    // Hapus input lama agar tidak ada duplikasi
    $("#shop_item_" + item.id).remove();

    // Jika qty lebih dari 0, tambahkan input baru
    if (qty > 0) {
        $('#shop').append(`
            <input id="shop_item_${item.id}" name="shop[]" value="${item.id};${item.name};${item.price};${qty};${total}" type="hidden" />
        `);
    }
});

            });
        });
    </script>
@endpush
