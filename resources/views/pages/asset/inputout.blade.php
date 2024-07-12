<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <script src="https://cdn.jsdelivr.net/npm/quagga/dist/quagga.min.js"></script>
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

        /* Modal styles */
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

        /* Media query for landscape orientation on mobile devices */
        @media only screen and (max-width: 600px) {
            .modal-content {
                width: 90%;
                max-width: none;
                height: 40vh;
                overflow-y: auto;
            }
        }
    </style>

    <x-navbars.sidebar activePage="inventory"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Data Keluar"></x-navbars.navs.auth>
        <!-- End Navbar -->
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
                            <form method="POST" action="{{ route('store_out', $inventory->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nik">NIK</label>
                                            <input id="nik" class="form-control border p-2" type="text" name="nik" required>
                                            @if ($errors->has('nik'))
                                            <div class="text-danger mt-2">{{ $errors->first('nik') }}</div>
                                            @endif

                                            <div class="mb-2 me-2 mt-3">
                                                <button id="openModalButton" class="btn btn-danger">
                                                    <i class="fas fa-camera"></i>
                                                </button>
                                            </div>

                                            <div id="myModal" class="modal">
                                                <div class="modal-content">
                                                    <span class="close">&times;</span>
                                                    <div id="interactive" class="viewport"></div>
                                                    <div id="result"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="period">Period</label>
                                            <input id="period" class="form-control border p-2" type="number" name="period" value="{{ old('period', $inventory->period) }}" required>
                                            @if ($errors->has('period'))
                                            <div class="text-danger mt-2">{{ $errors->first('period') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input id="date" class="form-control border p-2" type="date" name="date" value="{{ old('date', $inventory->date) }}" required>
                                            @if ($errors->has('date'))
                                            <div class="text-danger mt-2">{{ $errors->first('date') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="time">Time</label>
                                            <input id="time" class="form-control border p-2" type="time" name="time" value="{{ old('time', $inventory->time) }}" required>
                                            @if ($errors->has('time'))
                                            <div class="text-danger mt-2">{{ $errors->first('time') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="pic">PIC</label>
                                            <input id="pic" class="form-control border p-2" type="text" name="pic" value="{{ old('pic', $inventory->pic) }}" required>
                                            @if ($errors->has('pic'))
                                            <div class="text-danger mt-2">{{ $errors->first('pic') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="qty">Qty</label>
                                            <input id="qty" class="form-control border p-2" type="number" name="qty" required>
                                            @if ($errors->has('qty'))
                                            <div class="text-danger mt-2">{{ $errors->first('qty') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <select id="location" class="form-control border p-2" name="location" required readonly>
                                                <option value="" disabled>Select Location</option>
                                                <option value="Head Office" {{ $inventory->location == 'Head Office' ? 'selected' : '' }}>Head Office</option>
                                                <option value="Office Kendari" {{ $inventory->location == 'Office Kendari' ? 'selected' : '' }}>Office Kendari</option>
                                                <option value="Site Molore" {{ $inventory->location == 'Site Molore' ? 'selected' : '' }}>Site Molore</option>
                                            </select>
                                            @if ($errors->has('location'))
                                            <div class="text-danger mt-2">{{ $errors->first('location') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select id="category" class="form-control border p-2" name="category" readonly>
                                                <option value="" disabled>Select Category</option>
                                                <option value="ATK" {{ $inventory2->category == 'ATK' ? 'selected' : 'disabled' }}>ATK</option>
                                                <option value="PRL" {{ $inventory2->category == 'PRL' ? 'selected' : 'disabled' }}>PRL</option>
                                                <option value="SRGM" {{ $inventory2->category == 'SRGM' ? 'selected' : 'disabled' }}>SRGM</option>
                                                <option value="AK" {{ $inventory2->category == 'AK' ? 'selected' : 'disabled' }}>AK</option>
                                            </select>
                                            @if ($errors->has('category'))
                                            <div class="text-danger mt-2">{{ $errors->first('category') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" class="form-control border p-2" type="text" name="name" value="{{ old('name', $inventory->name) }}" required readonly>
                                            @if ($errors->has('name'))
                                            <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="unit">Unit</label>
                                            <select id="unit" class="form-control border p-2" name="unit" required readonly>
                                                <option value="" disabled>Select Unit</option>
                                                <option value="PCS" {{ $inventory->unit == 'PCS' ? 'selected' : 'disabled' }}>PCS</option>
                                                <option value="RIM" {{ $inventory->unit == 'RIM' ? 'selected' : 'disabled' }}>RIM</option>
                                                <option value="PACK" {{ $inventory->unit == 'PACK' ? 'selected' : 'disabled' }}>PACK</option>
                                                <option value="ROLL" {{ $inventory->unit == 'ROLL' ? 'selected' : 'disabled' }}>ROLL</option>
                                                <option value="DOS" {{ $inventory->unit == 'DOS' ? 'selected' : 'disabled' }}>DOS</option>
                                                <option value="SET" {{ $inventory->unit == 'SET' ? 'selected' : 'disabled' }}>SET</option>
                                            </select>
                                            @if ($errors->has('unit'))
                                            <div class="text-danger mt-2">{{ $errors->first('unit') }}</div>
                                            @endif
                                        </div>
                                    </div>
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

    <script>
        document.getElementById('openModalButton').addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            startScanner();
        });

        document.getElementsByClassName('close')[0].addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
            Quagga.stop();
        });

        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = "none";
                Quagga.stop();
            }
        };

        function startScanner() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#interactive'), // Target the div element, not the video directly
                    constraints: {
                        facingMode: "environment" // Ensure back camera is used
                    }
                },
                decoder: {
                    readers: [
                        "code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "code_39_vin_reader",
                        "codabar_reader", "upc_reader", "upc_e_reader", "i2of5_reader"
                    ]
                }
            }, function(err) {
                if (err) {
                    console.error(err);
                    return;
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            });

            Quagga.onDetected(function(result) {
                if (result.codeResult) {
                    var code = result.codeResult.code;
                    document.getElementById('result').innerText = 'Barcode detected: ' + code;

                    // Set the code in the search box
                    var searchBox = document.getElementById('nik');
                    searchBox.value = code;

                    // Close the modal
                    var modal = document.getElementById('myModal');
                    modal.style.display = "none";
                    Quagga.stop();x
                }
            });
        }
    </script>
</x-layout>