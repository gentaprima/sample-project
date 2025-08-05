@extends('master')

@section('content')
<div>
    <h4 class="fw-semibold">Material</h4>
    <div class="table-container">
        <div class="table-actions">
            <a href="/add-wax-room" class="add-button">+ Add</a>
            <div class="search-container">
                <div class="search-box">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <input type="text" placeholder="Search..." />
                </div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bagian</th>
                    <th>Jalur Proses</th>
                    <th>Nama Proses</th>
                    <th>Jumlah Pekerja</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="processTableBody">
                <?php $i = 1;
                foreach ($dataWax as $row) { ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>WAX ROOM</td>
                        <td><?= $row->production_code ?></td>
                        <td><?= $row->process_name ?></td>
                        <td><?= $row->officer_amount ?></td>
                        <td><?= $row->total_time ?> Menit</td>
                        <td>
                            <?php if ($row->status == "IN_PROCESS") { ?>
                                <span class="badge rounded-pill border border-info text-info">IN_PROCESS</span>
                            <?php } else if ($row->status == "NOT_PROCESS") { ?>
                                <span class="badge rounded-pill border border-danger text-danger">NOT_PROCESS</span>
                            <?php } else { ?>
                                <span class="badge rounded-pill border border-success text-success">DONE</span>
                            <?php } ?>
                        </td>
                        <td>
                            <button onclick="showData('<?= $row->id ?>')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>


</div>

<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMaterialModalLabel">Detail Process</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Sub Proses</th>
                                <th>Material</th>
                                <th>Hasil Material</th>
                                <th>Jumlah Hasil</th>
                                <th>Waktu</th>
                                <th>Pekerja</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="subprocessTableBody">

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>

                                </td>
                                <td>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                    <!-- <button type="submit" class="primary-button" id="buttonSave">Save</button> -->
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showData(id) {
        let tableBody = $('#subprocessTableBody');
        tableBody.html(`
                <tr>
                    <td colspan="8" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </td>
                </tr>
            `); // Tampilkan loading di tengah tabel

        $.ajax({
            url: '/get-data-subprocess/' + id,
            method: 'get',
            success: function(response) {
                tableBody.empty(); // Kosongkan loading setelah dapat response

                if (response.length === 0 || response.data.length === 0) {
                    tableBody.append('<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>');
                    return;
                }

                response.data.forEach((item, index) => {
                    let statusBadge = '';

                    if (item.status_subprocess === 'IN_PROCESS') {
                        statusBadge = `<span class="badge rounded-pill border border-info text-info">IN_PROCESS</span>`;
                    } else if (item.status_subprocess === 'NOT_PROCESS') {
                        statusBadge = `<span class="badge rounded-pill border border-danger text-danger">NOT_PROCESS</span>`;
                    } else if (item.status_subprocess === 'DONE') {
                        statusBadge = `<span class="badge rounded-pill border border-success text-success">DONE</span>`;
                    }

                    let buttonProcess = '';
                    if (item.status_subprocess === 'IN_PROCESS') {
                        buttonProcess = `
                        <button type="button" class="primary-button btn-sm change-status-btn" data-id="${item.id}">
                            <i class="bi bi-repeat"></i>
                        </button>`;
                    }

                    let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.subprocess_name}</td>
                    <td>${item.material_name}</td>
                    <td>${item.material_results}</td>
                    <td>${item.qty} Pcs</td>
                    <td>${item.processing_time} menit</td>
                    <td>${item.officer_name}</td>
                    <td>${statusBadge}</td>
                    <td>${buttonProcess}</td>
                </tr>`;
                    tableBody.append(row);
                });
            },
            error: function(xhr) {
                tableBody.html(`<tr><td colspan="8" class="text-center text-danger">Gagal mengambil data</td></tr>`);
                console.error(xhr);
            }
        });
    }

    $(document).on('click', '.change-status-btn', function() {

        const itemId = $(this).data('id');

        Swal.fire({
            title: 'Selesaikan Proses ini?',
            text: "Status akan diperbarui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ffc107'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    html: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: '/change-status',
                    method: 'POST',
                    data: {
                        id: itemId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Update UI langsung di modal tanpa tutup modal
                        // Misalnya reload baris tabel atau panggil ulang fungsi
                        Swal.close(); // ✅ Tutup loading
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Contoh update badge status secara langsung

                        showData(response.parent_id);
                        loadDataProgress();

                        // Optional: reload table kalau pakai DataTable
                        // $('#tableStatus').DataTable().ajax.reload(null, false);
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Tidak dapat mengubah status.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    function changeStatus(id) {
        Swal.fire({
            title: 'Ingin menyelesaikan proses?',
            text: "Data akan diperbarui!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, selesaikan!',
            backdrop: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Panggil AJAX untuk update status
                $.ajax({
                    url: '/update-subprocess-status/' + id,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token Laravel
                    },
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Status berhasil diubah.',
                            icon: 'success'
                        });
                        // Bisa refresh data tabel lagi
                        showData(res.id_process); // Misal id_process dikembalikan dari response
                        loadDataProgress();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Gagal mengubah status', 'error');
                    }
                });
            }
        });
    }

    function loadDataProgress() {
        $.ajax({
            url: "/get-data-process",
            type: "get",
            success: function(response) {
                let tableBody = $('#processTableBody');
                tableBody.empty(); // kosongkan dulu
                if (response.length === 0) {
                    tableBody.append('<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>');
                    return;
                }

                response.data.forEach((item, index) => {
                    let statusBadge = '';

                    if (item.status === 'IN_PROCESS') {
                        statusBadge = `<span class="badge rounded-pill border border-info text-info">IN_PROCESS</span>`;
                    } else if (item.status === 'NOT_PROCESS') {
                        statusBadge = `<span class="badge rounded-pill border border-danger text-danger">NOT_PROCESS</span>`;
                    } else if (item.status === 'DONE') {
                        statusBadge = `<span class="badge rounded-pill border border-success text-success">DONE</span>`;
                    }

                    let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>WAX ROOM</td>
                        <td>${item.production_code}</td>
                        <td>${item.process_name}</td>
                        <td>${item.officer_amount}</td>
                        <td>${item.total_time} menit</td>
                        <td>${statusBadge}</td>
                        
                        <td>
                             <button onclick="showData('${item.id}')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-eye"></i></button>
                             <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
                    tableBody.append(row);
                });
            }
        })
    }
</script>

@endsection