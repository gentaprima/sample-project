@extends('master')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Add Process Wax Room</h4>
        <a href="/wax-room" class="btn btn-secondary">Kembali</a>
    </div>

    <div id="all-processes">
        <div class="group-process">
            <button type="button" class="btn btn-sm btn-danger remove-process-btn" onclick="removeProcess(this)">
                &times;
            </button>

            <div class="form-floating mb-3">
                <select class="form-select" onchange="handleProcessChange(this)">
                    <option selected disabled>Pilih proses</option>
                    <option value="Injection">Injection</option>
                    <option value="Assembly">Assembly</option>
                </select>
                <label>Proses</label>
            </div>

            <div class="group-sub-process"></div>

            <button class="btn btn-sm btn-outline-primary mt-2" onclick="addSubProcess(this)" style="display: none;">+ Tambah Sub Proses</button>
        </div>
    </div>

    <button class="btn btn-sm btn-outline-success mt-2" onclick="addProcess()">+ Tambah Proses</button>
    <button class="btn btn-sm btn-primary mt-2" onclick="submitForm()">Submit</button>
    <!-- <pre id="json-output" class="mt-3 p-2 bg-light border rounded"></pre> -->


</div>
<script>
    function submitForm() {
        const allProcesses = document.querySelectorAll('.group-process');
        const finalData = {
            process: []
        };
        let processIndex = 1;

        allProcesses.forEach((groupProcess) => {
            const processName = groupProcess.querySelector('select').value;
            const subProcessContainers = groupProcess.querySelectorAll('.group-sub-process > div');
            // const processKey = `process_${processIndex++}`;
            const processKey = `process_name`;

            const processData = {
                [processKey]: processName,
                detail: []
            };

            let subIndex = 1;
            subProcessContainers.forEach(container => {
                const namaSubProcess = container.querySelector('.sub-process-select')?.value;
                const materials = Array.from(container.querySelectorAll('.material-select[multiple] option:checked')).map(opt => opt.value);
                // const hasilMaterial = container.querySelectorAll('select')[1]?.value;
                const hasilMaterial = container.querySelector('.hasil-material-select')?.value;
                const qty = container.querySelector('input[type="number"][placeholder="Qty"]')?.value;
                const waktu = container.querySelector('input[type="number"][placeholder="Waktu"]')?.value;
                // const namaPekerja = container.querySelector('input[type="text"]')?.value;
                const namaPekerja = Array.from(container.querySelectorAll('.officer-select[multiple] option:checked')).map(opt => opt.value);

                const subKey = `sub_process_${subIndex++}`;
                const subData = {};
                subData[subKey] = {
                    nama_sub_process: namaSubProcess,
                    material: materials,
                    hasil_material: hasilMaterial,
                    qty: qty,
                    waktu_pengerjaan: waktu,
                    nama_pekerja: namaPekerja
                };

                processData.detail.push(subData);
            });

            finalData.process.push(processData);
        });

        // document.getElementById("json-output").textContent = JSON.stringify(finalData, null, 2);

        Swal.fire({
            title: 'Menyimpan...',
            html: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "/process-add",
            method: "post",
            dataType: "json",
            data: {
                data: finalData,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(response) {
                Swal.close(); // ✅ Tutup loading
                Toast.fire({
                    icon: "success",
                    title: response.message
                });

                // RESET semua proses dan subproses
                const container = document.getElementById("all-processes");

                // Hapus semua .group-process kecuali satu (default)
                const allGroups = container.querySelectorAll(".group-process");
                allGroups.forEach((el, idx) => {
                    if (idx === 0) {
                        // Reset elemen pertama
                        el.querySelector("select").selectedIndex = 0;
                        el.querySelector(".group-sub-process").innerHTML = "";
                        el.querySelector("button.btn-outline-primary").style.display = "none";
                    } else {
                        el.remove();
                    }
                });

                // Reset variabel proses terpilih
                selectedProcesses = [];

                // Perbarui opsi select agar semua proses aktif lagi
                updateProcessSelectOptions();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        })
    }
    const subprocessMap = {
        Injection: ["Tooling Soluble", "Trimming", "Tooling Body", "Tooling Ring"],
        Assembly: ["Joining", "Moulding/Assembly"]
    };

    let materialOptions = [];
    let selectedProcesses = [];

    // Load material options from controller
    function loadMaterialOptions() {
        $.ajax({
            url: '/get-material',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    materialOptions = response.data.map(item => item.nama_material);
                    // Simpan data material lengkap untuk perhitungan
                    window.materialData = response.data;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading material options:', error);
            }
        });
    }

    // Fungsi untuk menghitung processing time berdasarkan hasil material
    function calculateProcessingTime(hasilMaterial, qty) {
        if (!window.materialData || !hasilMaterial || !qty || qty <= 0) {
            return 0;
        }

        const material = window.materialData.find(item => item.nama_material === hasilMaterial);
        if (material && material.processing_time) {
            return material.processing_time * qty;
        }

        return 0;
    }

    // Load material options when page loads
    $(document).ready(function() {
        loadMaterialOptions();
    });

    function handleProcessChange(select) {
        const process = select.value;
        const container = select.closest(".group-process");

        if (selectedProcesses.includes(process)) {
            alert("Proses sudah dipilih!");
            select.selectedIndex = 0;
            return;
        }

        selectedProcesses.push(process);

        const group = container.querySelector(".group-sub-process");
        group.innerHTML = "";
        group.appendChild(createSubProcessElement(process));

        container.querySelector("button.btn-outline-primary").style.display = "inline-block";
        updateProcessSelectOptions();
    }

    function createSubProcessElement(process) {



        const wrapperContainer = document.createElement("div");
        wrapperContainer.classList.add("mb-3", "position-relative", "border", "p-3", "rounded");

        const wrapper = document.createElement("div");
        wrapper.classList.add("form-floating", "mb-2");

        const select = document.createElement("select");
        select.classList.add("form-select", "sub-process-select");
        select.innerHTML = `<option selected disabled>Pilih sub proses</option>` +
            subprocessMap[process].map(opt => `<option value="${opt}">${opt}</option>`).join('');

        const label = document.createElement("label");
        label.textContent = "Sub Proses";

        const removeBtn = document.createElement("button");
        removeBtn.innerHTML = "&times;";
        removeBtn.type = "button";
        removeBtn.className = "btn btn-sm btn-danger remove-subprocess-btn";
        removeBtn.onclick = () => wrapperContainer.remove();

        wrapper.appendChild(select);
        wrapper.appendChild(label);
        wrapperContainer.appendChild(wrapper);
        wrapperContainer.appendChild(removeBtn);

        select.addEventListener("change", () => {

            // Hapus material-field sebelumnya kalau sudah ada
            const existingMaterialRow = wrapperContainer.querySelector(".material-row");
            if (existingMaterialRow) {
                existingMaterialRow.remove();
            }

            // Load material options if not already loaded
            if (materialOptions.length === 0) {
                loadMaterialOptions();
            }

            const materialFields = document.createElement("div");
            materialFields.classList.add("material-row");

            // Create material fields with current material options
            const createMaterialFields = () => {
                materialFields.innerHTML = `
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-floating mt-3">
                            <select class="form-select material-select" multiple>
                            ${materialOptions.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                            </select>
                            <label>Material</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-3">
                            <select class="form-select hasil-material-select">
                            <option value="">Pilih Hasil Material</option>
                            ${materialOptions.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                            </select>
                            <label>Hasil Material</label>
                        </div>
                    </div>
                       <div class="col-sm-4">
                         <div class="form-floating mt-3">
                            <input type="number" class="form-control" placeholder="Qty">
                            <label>Quantity</label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                          <div class="form-floating mt-3">
                            <input type="number" class="form-control" readonly placeholder="Waktu">
                            <label>Waktu Pengerjaan (menit) <small class="text-muted">*Otomatis berdasarkan hasil material</small></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating mt-3">
                            <select type="text" class="form-control officer-select" multiple>
                                <option>Genta</option>
                                <option>Alvin</option>
                            </select>
                            <label>Nama Pekerja</label>
                        </div>
                    </div>
                </div>
                
                `;

                wrapperContainer.appendChild(materialFields);
                $(materialFields).find('.material-select').select2({
                    width: '100%'
                });

                $(materialFields).find('.officer-select').select2({
                    width: '100%'
                });

                // Event listener untuk perhitungan otomatis berdasarkan hasil material
                const hasilMaterialSelect = materialFields.querySelector('.hasil-material-select');
                const qtyInput = materialFields.querySelector('input[placeholder="Qty"]');
                const waktuInput = materialFields.querySelector('input[placeholder="Waktu"]');

                // Fungsi untuk update processing time
                function updateProcessingTime() {
                    const hasilMaterial = hasilMaterialSelect.value;
                    const qty = parseInt(qtyInput.value) || 0;
                    
                    // Reset waktu jika qty kosong atau 0
                    if (!qtyInput.value || qty <= 0) {
                        waktuInput.value = '';
                        waktuInput.style.backgroundColor = '';
                        waktuInput.title = '';
                        return;
                    }
                    
                    const calculatedTime = calculateProcessingTime(hasilMaterial, qty);
                    
                    if (calculatedTime > 0) {
                        waktuInput.value = calculatedTime;
                        // waktuInput.style.backgroundColor = '#e8f5e8'; // Hijau muda untuk menandakan otomatis
                        waktuInput.title = 'Waktu dihitung otomatis berdasarkan hasil material dan qty';
                    } else {
                        waktuInput.value = '';
                        waktuInput.style.backgroundColor = ''; // Reset ke default
                        waktuInput.title = '';
                    }
                }

                // Event listeners
                hasilMaterialSelect.addEventListener('change', updateProcessingTime);
                qtyInput.addEventListener('input', updateProcessingTime);
                qtyInput.addEventListener('change', updateProcessingTime);
            };

            // If material options are already loaded, create fields immediately
            if (materialOptions.length > 0) {
                createMaterialFields();
            } else {
                // Wait for material options to load
                const checkMaterialOptions = setInterval(() => {
                    if (materialOptions.length > 0) {
                        createMaterialFields();
                        clearInterval(checkMaterialOptions);
                    }
                }, 100);
            }
        });

        return wrapperContainer;
    }

    function addSubProcess(btn) {
        const container = btn.closest(".group-process");
        const process = container.querySelector("select").value;
        const group = container.querySelector(".group-sub-process");
        group.appendChild(createSubProcessElement(process));
    }

    function addProcess() {
        const original = document.querySelector(".group-process");
        const newGroup = original.cloneNode(true);

        // Reset fields
        newGroup.querySelector("select").selectedIndex = 0;
        newGroup.querySelector(".group-sub-process").innerHTML = "";
        newGroup.querySelector("button.btn-outline-primary").style.display = "none";

        const removeBtn = newGroup.querySelector(".remove-process-btn");
        removeBtn.onclick = () => removeProcess(removeBtn);

        document.getElementById("all-processes").appendChild(newGroup);
    }

    function removeProcess(btn) {
        const container = btn.closest(".group-process");
        const selectedValue = container.querySelector("select").value;
        if (selectedValue) {
            selectedProcesses = selectedProcesses.filter(p => p !== selectedValue);
        }
        container.remove();
        updateProcessSelectOptions();
    }

    function updateProcessSelectOptions() {
        const selects = document.querySelectorAll("select");
        selects.forEach(select => {
            const current = select.value;
            select.querySelectorAll("option").forEach(opt => {
                if (selectedProcesses.includes(opt.value) && opt.value !== current) {
                    opt.disabled = true;
                } else {
                    opt.disabled = false;
                }
            });
        });
    }
</script>

@endsection