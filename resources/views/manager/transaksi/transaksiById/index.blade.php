<!-- Modal -->
<div class="modal fade" id="transaksiById" aria-labelledby="transaksiByIdLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transaksiByIdLabel">Transaksi ID <span id="transaksiID"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container">
                <h4>Transaksi di Pegawai <span id="namaPegawai"></span></h4><br>
                <div class="row" id="bodyTransaksiById">

                </div>
            </div>
        <hr>
        <div class="modal-divider">
            <h4>Meja Nomor <span id="mejaNomor"></span></h4>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="bayarKeranjang" onclick="bayar()">Bayar</button>
        </div>
      </div>
    </div>
  </div>
