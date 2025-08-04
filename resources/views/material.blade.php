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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($dataMaterial as $row) { ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->nama_material ?></td>
                        <td><?= $row->type ?></td>
                        <td><?= $row->stock ?></td>
                        <td>
                            <button onclick="showFormEdit('<?= $row->id ?>','<?= $row->nama_material ?>','<?= $row->type ?>')" data-bs-toggle="modal" data-bs-target="#addMaterialModal" class="primary-button btn-sm"><i class="bi bi-pen"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
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
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="materialName" name="material_name" required>
                        <label for="floatingInput">Material Name</label>
                    </div>
                    <div class="form-floating">
                        <select class="form-select" id="type" name="type" aria-label="Select Type">
                            <option selected>Open this select menu</option>
                            <option value="raw">Bahan Mentah</option>
                            <option value="finished">Bahan Jadi/Produk</option>
                        </select>
                        <label for="floatingSelect">Pilih tipe</label>
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

    function showFormAdd() {
        document.getElementById("addMaterialModalLabel").innerHTML = "Add Material";
        document.getElementById("buttonSave").innerHTML = "Save";
        document.getElementById("form").action = "/add-material";
    }

    function showFormEdit(id,namaMaterial,type) {
        document.getElementById("addMaterialModalLabel").innerHTML = "Update Material";
        document.getElementById("buttonSave").innerHTML = "Update";
        document.getElementById("form").action = "/edit-material/"+id;
        document.getElementById("materialName").value = namaMaterial;
        document.getElementById("type").value = type
    }
</script>

@endsection