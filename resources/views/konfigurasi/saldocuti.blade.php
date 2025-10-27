@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">
            Info Saldo Cuti
          </h2>
        </div>
      </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="/konfigurasi/saldocuti" method="GET" autocomplete="off">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                            </svg>
                                        </span>
                                        <input type="text" value="{{ Request('nama_karyawan') }}" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" name="nama_karyawan">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <button class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                <path d="M21 21l-6 -6" />
                                            </svg>
                                            Cari Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Sisa Cuti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawan as $d)
                            <tr>
                                <td>{{ $loop->iteration + $karyawan->firstItem() - 1 }}</td>
                                <td>{{ $d->nik }}</td>
                                <td>{{ $d->nama_lengkap }}</td>
                                <td>{{ $d->jabatan }}</td>
                                <td>{{ $d->saldo_cuti }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary edit-btn" data-nik="{{ $d->nik }}" data-saldo="{{ $d->saldo_cuti }}">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  
                                        stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                        <path d="M16 5l3 3" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $karyawan->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-edit-saldo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Saldo Cuti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/konfigurasi/saldocuti/update" method="POST">
                @csrf
                <input type="hidden" id="nik_form" name="nik">
                <div class="form-group">
                    <label for="saldo_cuti">Saldo Cuti</label>
                    <input type="number" id="saldo_cuti_form" name="saldo_cuti" class="form-control">
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100" type="submit">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('.edit-btn').click(function(e) {
            e.preventDefault();
            var nik = $(this).data('nik');
            var saldo = $(this).data('saldo');
            $('#nik_form').val(nik);
            $('#saldo_cuti_form').val(saldo);
            $('#modal-edit-saldo').modal('show');
        });
    });
</script>
@endpush