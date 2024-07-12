<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="employee"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="INPUT EMPLOYEE"></x-navbars.navs.auth>
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
                            <form method="POST" action="{{ route('employee.update', $employee->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nik">NIK</label>
                                            <input id="nik" class="form-control border p-2" type="text" name="nik" value="{{ $employee->nik }}" required>
                                            @if ($errors->has('nik'))
                                            <div class="text-danger mt-2">{{ $errors->first('nik') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="nama">Nama</label>
                                            <input id="nama" class="form-control border p-2" type="text" name="nama" value="{{ $employee->nama }}" required>
                                            @if ($errors->has('nama'))
                                            <div class="text-danger mt-2">{{ $errors->first('nama') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="area">Area</label>
                                            <select id="area" class="form-control border p-2" name="area" required readonly>
                                                <option value="" selected disabled>Select Area</option>
                                                <option value="Head Office" {{ $employee->area == 'Head Office' ? 'selected' : 'disabled' }}>Head Office</option>
                                                <option value="Office Kendari" {{ $employee->area == 'Office Kendari' ? 'selected' : 'disabled' }}>Office Kendari</option>
                                                <option value="Site Molore" {{ $employee->area == 'Site Molore' ? 'selected' : 'disabled' }}>Site Molore</option>
                                            </select>
                                            @if ($errors->has('area'))
                                            <div class="text-danger mt-2">{{ $errors->first('area') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dept">Dept</label>
                                            <input id="dept" class="form-control border p-2" type="text" name="dept" value="{{ $employee->dept }}" required>
                                            @if ($errors->has('dept'))
                                            <div class="text-danger mt-2">{{ $errors->first('dept') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="jabatan">Jabatan</label>
                                            <input id="jabatan" class="form-control border p-2" type="text" name="jabatan" value="{{ $employee->jabatan }}" required>
                                            @if ($errors->has('jabatan'))
                                            <div class="text-danger mt-2">{{ $errors->first('jabatan') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-block">Update Employee</button>
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
</x-layout>