<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="vendor"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Edit Vendor"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('update_vendor', ['id' => $vendor->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama">Nama</label>
                                            <input id="nama" class="form-control border p-2" type="text" name="nama" value="{{ $vendor->nama }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat</label>
                                            <input id="alamat" class="form-control border p-2" type="text" name="alamat" value="{{ $vendor->alamat }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_tel">No. Tel</label>
                                            <input id="no_tel" class="form-control border p-2" type="text" name="no_tel" value="{{ $vendor->no_tel }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="pic">PIC</label>
                                            <input id="pic" class="form-control border p-2" type="text" name="pic" value="{{ $vendor->pic }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">Update Vendor</button>
                                    <a href="{{ route('vendor') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-footers.auth></x-footers.auth>
</x-layout>