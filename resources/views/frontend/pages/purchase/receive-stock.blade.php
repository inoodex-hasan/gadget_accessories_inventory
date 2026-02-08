@extends('frontend.layouts.app')
@section('content')
    <div class="container-fluid col-sm-10">
        <div class="page-header mb-4">
            <h3>Receive Stock by Scan</h3>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label>Vendor <span class="text-danger">*</span></label>
                        <select id="vendor_id" class="form-select" required>
                            <option value="">Choose Vendor...</option>
                            @foreach (\App\Models\Vendor::all() as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Invoice No.</label>
                        <input type="text" id="invoice_no" class="form-control" placeholder="Optional">
                    </div>
                    <div class="col-md-4">
                        <label>Unit Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="unit_price" class="form-control" value="0" required>
                    </div>
                </div> --}}

                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Product <span class="text-danger">*</span></label>
                        <select id="product_id" class="form-select select2">
                            <option value="">Choose product...</option>
                            @foreach (\App\Models\Product::where('status', 1)->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->brand->name ?? '' }} {{ $p->name }}
                                    {{ $p->model_name ? '(' . $p->model_name . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Barcode <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="barcode" class="form-control" placeholder="Scan barcode..."
                                autofocus>
                            <button class="btn btn-primary" id="cam-btn">Camera</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label>Serial Number <small>(optional)</small></label>
                        <input type="text" id="serial" class="form-control" placeholder="Auto-fill or type...">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-success btn-lg" id="add-item">Add Unit</button>
                    </div>

                    <div class="col-12">
                        <div class="card mt-4">
                            <div class="card-header bg-success text-white">
                                Scanned Units: <span id="count">0</span>
                            </div>
                            <div class="card-body" id="list" style="max-height:400px;overflow:auto">
                                <p class="text-muted">Start scanning...</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button class="btn btn-primary btn-lg" id="save-all">Save All to Purchase & Inventory</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Scan Barcode</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="reader"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let items = [];

        $(function() {
            $('#product_id').select2({
                width: '100%'
            });

            $('#cam-btn').click(() => {
                new bootstrap.Modal('#cameraModal').show();
                setTimeout(startScanner, 500);
            });

            $('#barcode').keypress(e => {
                if (e.which == 13) $('#serial').focus();
            });
            $('#serial').keypress(e => {
                if (e.which == 13) addItem();
            });
            $('#add-item').click(addItem);
            $('#save-all').click(saveAll);
        });

        function startScanner() {
            const scanner = new Html5Qrcode("reader");
            scanner.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 280,
                        height: 100
                    }
                },
                text => {
                    $('#barcode').val(text);
                    autoFillSerial(text);
                    scanner.stop();
                    bootstrap.Modal.getInstance('#cameraModal').hide();
                    playBeep();
                }
            );
        }

        function autoFillSerial(barcode) {
            if (barcode.length > 10) {
                const serial = barcode.slice(-12);
                if (/^\d+$/.test(serial)) $('#serial').val(serial);
            }
        }

        function addItem() {
            const productId = $('#product_id').val();
            const barcode = $('#barcode').val().trim();
            const serial = $('#serial').val().trim();
            const price = $('#unit_price').val();

            if (!productId || !barcode || !price) return alert('Product, Barcode & Price required');

            if (items.some(i => i.barcode === barcode)) return alert('Already scanned');

            items.push({
                product_id: productId,
                barcode: barcode,
                serial: serial || null,
                price: price
            });
            renderList();

            $('#barcode').val('').focus();
            $('#serial').val('');
        }

        function renderList() {
            $('#count').text(items.length);
            if (!items.length) {
                $('#list').html('<p class="text-muted">Start scanning...</p>');
                return;
            }
            let html = '<ol class="mb-0">';
            items.forEach(i => {
                const name = $('#product_id option:selected').text();
                html +=
                    `<li><strong>${name}</strong><br>Barcode: ${i.barcode} ${i.serial?'<br>Serial: '+i.serial:''}</li><hr class="my-1">`;
            });
            html += '</ol>';
            $('#list').html(html);
        }

        function saveAll() {
            if (!items.length) return;

            const data = {
                _token: '{{ csrf_token() }}',
                vendor_id: $('#vendor_id').val(),
                invoice_no: $('#invoice_no').val() || null,
                items: items
            };

            $.post("{{ route('receive-stock.store') }}", data)
                .done(res => {
                    alert('Success! ' + items.length + ' units received');
                    items = [];
                    renderList();
                });
        }

        function playBeep() {
            const audio = new Audio("data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQAAAAA=");
            audio.play();
        }
    </script>
@endsection
