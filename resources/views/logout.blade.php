<!-- Modal Logout -->
<form action="{{ url('/logout') }}" method="POST" id="form_logout">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Logout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="modal-body text-center">
            <i class="fas fa-sign-out-alt" style="font-size: 3rem; color: #dc3545;"></i>
            <p class="h5 mt-3">Apakah Anda yakin ingin keluar dari sistem?</p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Logout</button>
    </div>
</form>