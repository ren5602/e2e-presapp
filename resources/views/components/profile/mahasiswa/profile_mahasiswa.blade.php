{{-- PROFILE --}}
<div class="bg-light" style="height: 250px; overflow: hidden;">
    <img src="{{ asset('assets/images/gdungjti2.png') }}" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
</div>

<div class="text-center mt-n5">
    <img src="{{ asset(auth()->user()->mahasiswa->foto_profile ? 'storage/' . auth()->user()->mahasiswa->foto_profile : 'assets/images/user.png') }}"
        class="rounded-circle border border-primary shadow bg-white img-thumbnail mb-3"
        style="width: 160px; height: 160px; object-fit: cover;">
    <div class="d-flex justify-content-center align-items-center mt-2">
        <h4 class="mb-0 me-2">{{ auth()->user()->mahasiswa->nama }}</h4>
    </div>
    <p class="text-muted mt-2">{{ auth()->user()->mahasiswa->nim }}</p>
    <span class="badge bg-info text-dark mb-3">Mahasiswa</span>
</div>

<div class="card-body">
    <div class="row mt-4">
        <div class="mb-3 col-md-6">
            <label class="form-label">Nama Mahasiswa</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->mahasiswa->nama }}</div>
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label">NIM Mahasiswa</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->mahasiswa->nim }}</div>
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label">Username</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->username }}</div>
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label">Email</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->mahasiswa->email }}</div>
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label">No. Telepon</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->mahasiswa->no_tlp }}</div>
        </div>
        <div class="mb-3 col-md-3">
            <label class="form-label">Kelas</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->mahasiswa->kelas->kelas_nama }}</div>
        </div>
        <div class="mb-3 col-md-3">
            <label class="form-label">Angkatan</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->mahasiswa->tahun_angkatan }}</div>
        </div>
        <div class="mb-3 col-md-12">
            <label class="form-label">Alamat</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->mahasiswa->alamat }}</div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <button class="btn btn-warning" onclick="modalPassword('{{ route('profile.mahasiswa.edit-password') }}')"><i class="fa fa-edit"></i>
            Ubah Password</button>
        <button class="btn btn-success" onclick="editProfile()"><i class="fa fa-edit"></i>
            Edit</button>
    </div>

</div>
{{-- /PROFILE --}}
