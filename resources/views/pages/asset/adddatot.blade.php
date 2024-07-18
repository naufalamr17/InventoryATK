<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        #interactive {
            width: 100%;
            height: 400px;
            overflow: hidden;
            position: relative;
        }

        #interactive video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #result {
            margin-top: 20px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        @media only screen and (max-width: 600px) {
            .modal-content {
                width: 90%;
                max-width: none;
                height: 40vh;
                overflow-y: auto;
            }
        }
    </style>

    <x-navbars.sidebar activePage="data_out"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Data Keluar"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="p-6">
                            <form method="POST" action="{{ route('dataout.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="pic">PIC</label>
                                    <input id="pic" class="form-control border p-2" type="text" name="pic" list="picList" required>
                                    <datalist id="picList">
                                        <option value="Reggie">
                                        <option value="Nurbaya">
                                    </datalist>
                                    @if ($errors->has('pic'))
                                    <div class="text-danger mt-2">{{ $errors->first('pic') }}</div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input id="nik" class="form-control border p-2" type="text" name="nik" required>
                                    @if ($errors->has('nik'))
                                    <div class="text-danger mt-2">{{ $errors->first('nik') }}</div>
                                    @endif

                                    <button type="button" id="openModalButton" class="btn btn-danger mt-2">
                                        <i class="fas fa-camera"></i>
                                    </button>

                                    <div id="myModal" class="modal">
                                        <div class="modal-content">
                                            <span class="close">&times;</span>
                                            <div id="interactive" class="viewport"></div>
                                            <div id="result"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="dynamic-form">
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="code">Code</label>
                                                <select class="form-control code" name="code[]" required>
                                                    <option value="" selected disabled>Select Code</option>
                                                    @foreach ($inventory as $item)
                                                    <option value="{{ $item->code }}">{{ $item->name }} - {{ $item->code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="date">Date</label>
                                                <input class="form-control border p-2" type="date" name="date[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="time">Time</label>
                                                <input class="form-control border p-2" type="time" name="time[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label for="qty">Qty</label>
                                                <input class="form-control border p-2" type="number" name="qty[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-12 mt-2">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-danger mt-4" onclick="removeFormRow(this)">X</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" onclick="addFormRow()">Tambah Baris</button>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-block">Update Data</button>
                                    <a href="{{ route('inventory') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

    <!-- Pastikan jQuery diinisialisasi terlebih dahulu -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Kemudian baru inisialisasi Selectize.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            // Inisialisasi Selectize.js pada elemen select dengan class 'code'
            $('.code').selectize({
                create: false,
                sortField: 'text',
                placeholder: 'Search or select code...',
                allowClear: true,
                dropdownParent: 'body',
                dropdownAutoWidth: false
                // Add more options as needed
            });
        });

        function addFormRow() {
            var row = `<div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="code">Code</label>
                            <select class="form-control code" name="code[]" required>
                                <option value="" selected disabled>Select Code</option>
                                @foreach ($inventory as $item)
                                <option value="{{ $item->code }}">{{ $item->name }} - {{ $item->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input class="form-control border p-2" type="date" name="date[]" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="time">Time</label>
                            <input class="form-control border p-2" type="time" name="time[]" required>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input class="form-control border p-2" type="number" name="qty[]" required>
                        </div>
                    </div>
                    <div class="col-md-1 col-12 mt-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger mt-4" onclick="removeFormRow(this)">X</button>
                        </div>
                    </div>
                </div>`;
            $('#dynamic-form').append(row);

            // Find the newly added select element with class 'code'
            var newCodeSelect = $('#dynamic-form .code').last();

            // Initialize Selectize.js on the newly added element
            newCodeSelect.selectize({
                create: false,
                sortField: 'text',
                placeholder: 'Search or select code...',
                allowClear: true,
                dropdownParent: 'body',
                dropdownAutoWidth: false
            });
        }

        function removeFormRow(button) {
            $(button).closest('.row').remove();
        }

        // Script untuk QR Code Scanner
        // ...
    </script>

    <script>
        document.getElementById('openModalButton').addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            startScanner();
        });

        document.getElementsByClassName('close')[0].addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
            html5QrCode.stop().catch(err => console.error(err));
        });

        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = "none";
                html5QrCode.stop().catch(err => console.error(err));
            }
        };

        function startScanner() {
            const html5QrCode = new Html5Qrcode("interactive");

            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                (decodedText, decodedResult) => {
                    document.getElementById('result').innerText = 'QR Code detected: ' + decodedText;
                    var searchBox = document.getElementById('nik');
                    searchBox.value = decodedText;

                    var modal = document.getElementById('myModal');
                    modal.style.display = "none";
                    html5QrCode.stop().catch(err => console.error(err));
                },
                (errorMessage) => {
                    console.warn(`QR Code no longer in front of camera: ${errorMessage}`);
                }
            ).then(() => {
                setTimeout(() => {
                    html5QrCode.applyVideoConstraints({
                        focusMode: "continuous",
                        advanced: [{
                            zoom: 2.0
                        }]
                    }).catch(err => console.error(err));
                }, 2000);
            }).catch(err => {
                console.error(`Unable to start scanning, error: ${err}`);
            });
        }
    </script>
</x-layout>