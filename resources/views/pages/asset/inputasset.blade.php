<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="inventory"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="INPUT INVENTORY"></x-navbars.navs.auth>
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
                            <form method="POST" action="{{ route('store_inventory') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="display: none;">
                                            <label for="period">Period</label>
                                            <input id="period" class="form-control border p-2" type="number" name="period" value="{{ old('period') }}">
                                            @if ($errors->has('period'))
                                            <div class="text-danger mt-2">{{ $errors->first('period') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input id="date" class="form-control border p-2" type="date" name="date" value="{{ old('date') }}" required>
                                            @if ($errors->has('date'))
                                            <div class="text-danger mt-2">{{ $errors->first('date') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="time">Time</label>
                                            <input id="time" class="form-control border p-2" type="time" name="time" value="{{ old('time') }}" required>
                                            @if ($errors->has('time'))
                                            <div class="text-danger mt-2">{{ $errors->first('time') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="pic">PIC</label>
                                            <input id="pic" class="form-control border p-2" type="text" name="pic" list="picList" required>
                                            <datalist id="picList">
                                                <option value="Reggie">
                                                <option value="Baya">
                                            </datalist>
                                            @if ($errors->has('pic'))
                                            <div class="text-danger mt-2">{{ $errors->first('pic') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="qty">Qty</label>
                                            <input id="qty" class="form-control border p-2" type="number" name="qty" value="{{ old('qty') }}" required>
                                            @if ($errors->has('qty'))
                                            <div class="text-danger mt-2">{{ $errors->first('qty') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input id="price" class="form-control border p-2" type="number" name="price" value="{{ old('price') }}" required>
                                            @if ($errors->has('price'))
                                            <div class="text-danger mt-2">{{ $errors->first('price') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <select id="location" class="form-control border p-2" name="location" required readonly>
                                                <option value="" selected disabled>Select Location</option>
                                                <option value="Head Office" {{ $userLocation == 'Head Office' ? 'selected' : 'disabled' }}>Head Office</option>
                                                <option value="Office Kendari" {{ $userLocation == 'Office Kendari' ? 'selected' : 'disabled' }}>Office Kendari</option>
                                                <option value="Site Molore" {{ $userLocation == 'Site Molore' ? 'selected' : 'disabled' }}>Site Molore</option>
                                            </select>
                                            @if ($errors->has('location'))
                                            <div class="text-danger mt-2">{{ $errors->first('location') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select id="category" class="form-control border p-2" name="category" required>
                                                <option value="" selected disabled>Select Category</option>
                                                <option value="ATK">ATK</option>
                                                <option value="PRL">PRL</option>
                                                <option value="SRGM">SRGM</option>
                                                <option value="AK">AK</option>
                                            </select>
                                            @if ($errors->has('category'))
                                            <div class="text-danger mt-2">{{ $errors->first('category') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" class="form-control border p-2" type="text" name="name" value="{{ old('name') }}" required>
                                            @if ($errors->has('name'))
                                            <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="unit">Unit</label>
                                            <select id="unit" class="form-control border p-2" name="unit" required>
                                                <option value="" selected disabled>Select Unit</option>
                                                <option value="PCS">PCS</option>
                                                <option value="RIM">RIM</option>
                                                <option value="PACK">PACK</option>
                                                <option value="ROLL">ROLL</option>
                                                <option value="DOS">DOS</option>
                                                <option value="SET">SET</option>
                                            </select>
                                            @if ($errors->has('unit'))
                                            <div class="text-danger mt-2">{{ $errors->first('unit') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="vendor">Vendor</label>
                                            <select id="vendor" class="form-control border p-2" name="vendor" required>
                                                <option value="" selected disabled>Select Vendor</option>
                                                @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->nama }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('vendor'))
                                            <div class="text-danger mt-2">{{ $errors->first('unit') }}</div>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-block">Add Inventory</button>
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
        document.getElementById('asset_category').addEventListener('change', function() {
            var usefulLifeInput = document.getElementById('useful_life');
            var selectedCategory = this.value;
            var usefulLife;

            switch (selectedCategory) {
                case 'Kendaraan':
                    usefulLife = 8;
                    break;
                case 'Peralatan':
                    usefulLife = 4;
                    break;
                case 'Bangunan':
                    usefulLife = 20;
                    break;
                case 'Mesin':
                    usefulLife = 16;
                    break;
                case 'Alat Berat':
                    usefulLife = 8;
                    break;
                case 'Alat Lab & Preparasi':
                    usefulLife = 16;
                    break;
                default:
                    usefulLife = '';
            }

            usefulLifeInput.value = usefulLife;
        });

        // Trigger change event to set initial value if a category is already selected
        document.getElementById('asset_category').dispatchEvent(new Event('change'));
    </script>
</x-layout>