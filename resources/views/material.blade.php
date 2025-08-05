@extends('master')

@section('content')
<div>
    <h4 class="fw-semibold">Material</h4>
    <div class="table-container">
        <div class="table-actions">
            <button class="add-button" data-bs-toggle="modal" onclick="showFormAdd()" data-bs-target="#addMaterialModal">+ Add</button>
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
                    <th>Name</th>
                    <th>Type</th>
                    <th>Stock</th>
                    <th>Processing Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableMaterial">
                <?php $i = 1;
                foreach ($dataMaterial as $row) { ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->nama_material ?></td>
                        <td><?= $row->type ?></td>
                        <td>
                            <?php if ($row->type == "finished") {  ?>
                                <?= $row->stock ?> Pcs
                            <?php } else { ?>
                                -
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($row->type == "finished") {  ?>
                                <?= $row->processing_time ?> Menit
                            <?php } else { ?>
                                -
                            <?php } ?>
                        </td>
                        <td>
                            <button onclick="showFormEdit('<?= $row->id ?>','<?= $row->nama_material ?>','<?= $row->type ?>','<?= $row->processing_time ?>')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-pen"></i></button>
                            <button data-id="<?= $row->id ?>" id="delete-material" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <h4 class="fw-semibold mt-4">Employee</h4>
    <div class="table-container">
        <div class="table-actions">
            <button class="add-button" data-bs-toggle="modal" onclick="showFormAdd()" data-bs-target="#addMaterialModal">+ Add</button>
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
                    <th>Nama Karyawan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Alvin Kurniawan</td>
                    <td>
                        <button onclick="showFormEdit('')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-pen"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Genta Prima Syahnur</td>
                    <td>
                        <button onclick="showFormEdit('')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-pen"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <h4 class="fw-semibold mt-4">Proses</h4>
    <div class="table-container">
        <div class="table-actions">
            <button class="add-button" data-bs-toggle="modal" onclick="showFormAdd()" data-bs-target="#addMaterialModal">+ Add</button>
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
                    <th>Nama Proses</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Injection</td>
                    <td>
                        <button onclick="showFormEdit('')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-pen"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Assembly</td>
                    <td>
                        <button onclick="showFormEdit('')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-pen"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMaterialModalLabel">Add Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="materialName" name="material_name" required>
                        <label for="floatingInput">Material Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="type" name="type" aria-label="Select Type">
                            <option selected>Open this select Type</option>
                            <option value="raw">Bahan Mentah</option>
                            <option value="finished">Bahan Jadi/Produk</option>
                        </select>
                        <label for="floatingSelect">Pilih tipe</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input type="text" name="processing_time" id="processing_time" class="form-control">
                            <label for="floatingInput">Processing Time</label>
                        </div>
                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                    </div> -->

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="materialName" name="material_name" required>
                        <label for="materialName">Material Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="type" name="type" aria-label="Select Type">
                            <option selected disabled>Pilih Tipe</option>
                            <option value="raw">Bahan Mentah</option>
                            <option value="finished">Bahan Jadi/Produk</option>
                        </select>
                        <label for="type">Pilih tipe</label>
                    </div>

                    <div class="input-group mb-3 d-none" id="processingTimeWrapper">
                        <div class="form-floating">
                            <input type="text" name="processing_time" id="processing_time" class="form-control">
                            <label for="processing_time">Processing Time</label>
                        </div>
                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="primary-button" id="buttonSave">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // addData()

    $(document).ready(function() {
        $('#type').on('change', function() {
            const selectedType = $(this).val();
            const processingInput = $('#processingTimeWrapper');

            if (selectedType === 'finished') {
                processingInput.removeClass('d-none');
            } else {
                processingInput.addClass('d-none');
                $('#processing_time').val(''); // kosongkan jika hidden
            }
        });
    });

    function showFormAdd() {
        document.getElementById("addMaterialModalLabel").innerHTML = "Add Material";
        document.getElementById("buttonSave").innerHTML = "Save";
        document.getElementById("form").action = "/add-material";
        document.getElementById("materialName").value = "";
        document.getElementById("type").value = ""
        document.getElementById("processing_time").value = ""
    }

    function showFormEdit(id, namaMaterial, type, processing_time) {
        document.getElementById("addMaterialModalLabel").innerHTML = "Update Material";
        document.getElementById("buttonSave").innerHTML = "Update";
        document.getElementById("form").action = "/edit-material/" + id;
        document.getElementById("materialName").value = namaMaterial;
        document.getElementById("type").value = type
        document.getElementById("processing_time").value = processing_time

        // const selectedType = $(this).val();
        const processingInput = $('#processingTimeWrapper');

        if (type === 'finished') {
            processingInput.removeClass('d-none');
        } else {
            processingInput.addClass('d-none');
            $('#processing_time').val(''); // kosongkan jika hidden
        }
    }

    $(document).on('click', '#delete-material', function() {

        const itemId = $(this).data('id');

        Swal.fire({
            title: 'Hapus data ini?',
            text: "Jika diproses data tidak bisa dikembalikan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
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
                    url: '/delete-material/' + itemId,
                    method: 'get',
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

                        loadData();
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

    function loadData() {
        $.ajax({
            url: "/get-material",
            type: "get",
            success: function(response) {
                let tableBody = $('#tableMaterial');
                tableBody.empty(); // kosongkan dulu
                if (response.length === 0) {
                    tableBody.append('<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>');
                    return;
                }

                response.data.forEach((item, index) => {
                    let stock = '';
                    let processingTime = '';
                    if (item.type === 'finished') {
                        stock = `${item.stock} Pcs`;
                        processingTime = `${item.processing_time} Menit`;
                    } else {
                        stock = `-`;
                        processingTime = `-`;
                    }

                    let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nama_material}</td>
                        <td>${item.type}</td>
                        <td>${stock}</td>
                        <td>${processingTime}</td>
                        
                        <td>
                             <button onclick="showFormEdit('${item.id}','${item.nama_material}','${item.type}','${item.processing_time}')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-eye"></i></button>
                             <button data-id="${item.id}" id="delete-material" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
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