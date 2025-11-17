{{-- PROFILE --}}
<div class="bg-light" style="height: 250px; overflow: hidden;">
    <img src="{{ asset('assets/images/gdungjti2.png') }}" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
</div>

<div class="text-center mt-n5">
    <img src="{{ asset(auth()->user()->admin->foto_profile ? 'storage/' . auth()->user()->admin->foto_profile : 'assets/images/user.png') }}"
        class="rounded-circle border border-primary shadow bg-white img-thumbnail mb-3"
        style="width: 160px; height: 160px; object-fit: cover;">
    <div class="d-flex justify-content-center align-items-center mt-2">
        <h4 class="mb-0 me-2">{{ auth()->user()->admin->nama }}</h4>
    </div>
    <p class="text-muted mt-2">{{ auth()->user()->admin->nim }}</p>
    <span class="badge bg-info text-dark mb-3">admin</span>
</div>

<div class="card-body">
    <div class="row mt-4">
        <div class="mb-3 col-md-6">
            <label class="form-label">Nama admin</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->admin->nama }}</div>
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label">Username</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->username }}</div>
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label">Email</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->admin->email }}</div>
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label">No. Telepon</label>
            <div type="text" class="form-control" style="background-color: #e9ecef">
                {{ auth()->user()->admin->no_tlp }}</div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <button class="btn btn-warning" onclick="modalPassword('{{ route('profile.admin.edit-password') }}')"><i class="fa fa-edit"></i>
            Ubah Password</button>
        <button class="btn btn-success" onclick="editProfile()"><i class="fa fa-edit"></i>
            Edit</button>
    </div>

</div>
{{-- /PROFILE --}}
