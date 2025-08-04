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
                const waktu = container.querySelector('input[type="number"]')?.value;
                // const namaPekerja = container.querySelector('input[type="text"]')?.value;
                const namaPekerja = Array.from(container.querySelectorAll('.officer-select[multiple] option:checked')).map(opt => opt.value);

                const subKey = `sub_process_${subIndex++}`;
                const subData = {};
                subData[subKey] = {
                    nama_sub_process: namaSubProcess,
                    material: materials,
                    hasil_material: hasilMaterial,
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

    const materialOptions = ["D2-49 Soluble Wax", "D2-49 Body Wax", "D2-49 Ring Wax", "D2-49 WAX", "D2-49"];
    let selectedProcesses = [];

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

            const materialFields = document.createElement("div");
            materialFields.classList.add("material-row");

            materialFields.innerHTML = `
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-floating mt-3">
                        <select class="form-select material-select" multiple>
                        ${materialOptions.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                        </select>
                        <label>Material</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-floating mt-3">
                        <select class="form-select hasil-material-select">
                        <option value="">Pilih Hasil Material</option>
                        ${materialOptions.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                        </select>
                        <label>Hasil Material</label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                     <div class="form-floating mt-3">
                        <input type="number" class="form-control" placeholder="Waktu">
                        <label>Waktu Pengerjaan (menit)</label>
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

            wrapperContainer.appendChild(materialFields);
            $(materialFields).find('.officer-select').select2({
                width: '100%'
            });
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