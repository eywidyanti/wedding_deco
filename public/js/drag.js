// === Fungsi pergerakan drag ===
function dragMoveListener(event) {
    var target = event.target;
    var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
    var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

    target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);

    updateKeterangan();
}

function dragEndListener(event) {
    const id = event.target.id;

    // Kalau elemen berada di dalam dropzone, masukkan ke itemDrop
    const dropzone = document.getElementById("dropzone");
    const dropRect = dropzone.getBoundingClientRect();
    const elRect = event.target.getBoundingClientRect();

    const inside =
        elRect.left >= dropRect.left &&
        elRect.right <= dropRect.right &&
        elRect.top >= dropRect.top &&
        elRect.bottom <= dropRect.bottom;

    if (inside) {
        if (!itemDrop.includes(id)) {
            itemDrop.push(id);
        }
    } else {
        // kalau keluar area drop langsung hapus
        const index = itemDrop.indexOf(id);
        if (index !== -1) itemDrop.splice(index, 1);
    }

    updateKeterangan();
}


// === Dropzone Handler ===
function onDragEnter(event) {
    var draggableElement = event.relatedTarget;
    var dropzoneElement = event.target;
    dropzoneElement.classList.add("drop-target");
    draggableElement.classList.add("can-drop");
}

function onDragLeave(event) {
    event.target.classList.remove("drop-target");
    event.relatedTarget.classList.remove("can-drop");

    const index = itemDrop.indexOf(event.relatedTarget.id);
    if (index !== -1) itemDrop.splice(index, 1);
}

function onDrop(event) {
    event.target.classList.remove("drop-target");
    const droppedId = event.relatedTarget.id;

    // Tambahkan ke daftar drop jika belum ada
    if (!itemDrop.includes(droppedId)) {
        itemDrop.push(droppedId);
    }

    const el = document.getElementById(droppedId);
    const deskripsi = el.getAttribute("data-deskripsi");
    console.log("Item dijatuhkan ke Drop Zone:", deskripsi);
}


function lihatRekomendasi() {
    if (itemDrop.length === 0) {
        alert("Silakan drag minimal 1 item terlebih dahulu!");
        return;
    }

    // Gabungkan deskripsi semua item yang di-drop
    const deskripsiGabung = itemDrop.map(id => {
        const el = document.getElementById(id);
        return el.getAttribute("data-deskripsi") || "";
    }).join(" ");

    console.log("Deskripsi dikirim:", deskripsiGabung);

    // Kirim deskripsi ke backend untuk mendapatkan rekomendasi
    fetch("/rekomendasi/drag", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ description: deskripsiGabung })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Hasil rekomendasi:", data);
        tampilkanRekomendasi(data); // tampilkan hasilnya
    })
    .catch(err => console.error("Error:", err));
}

function tampilkanRekomendasi(list) {
    const panel = document.getElementById("list-keterangan");
    if (!panel) return;

    let html = "<hr><h4>Rekomendasi Dekorasi Mirip</h4><ul>";

    if (Array.isArray(list) && list.length > 0) {
        list.forEach(item => {
            html += `
                <li>${item.description}<br>
                    <small>Kemiripan: ${item.similarity.toFixed(3)}</small>
                </li>`;
        });
    } else {
        html += "<li>Tidak ditemukan dekorasi mirip.</li>";
    }

    html += "</ul>";
    panel.innerHTML = html; // ganti konten lama
}

// === Reset dan Re-inisialisasi Interact.js ===
function resetInteract() {
    // Bersihkan interaksi lama
    interact('.draggable').unset();

    // Dropzone utama
    interact('#dropzone').dropzone({
        accept: ".itemA",
        overlap: 0.75,
        ondragenter: onDragEnter,
        ondragleave: onDragLeave,
        ondrop: onDrop
    });

    // Aktifkan drag untuk semua item
    interact('.draggable').draggable({
        inertia: true,
        autoScroll: true,
        modifiers: [
            interact.modifiers.restrictRect({
                restriction: "parent",
                endOnly: true
            })
        ],
        listeners: { 
            move: dragMoveListener,
            end: dragEndListener }
    });

    console.log("Interact.js sudah direset dan aktif kembali.");
}

document.addEventListener("DOMContentLoaded", function () {
    resetInteract();
    restoreDropItems();
});

// === Fungsi untuk memulihkan item yang berada di dalam dropzone ===
function restoreDropItems() {
    const dropzone = document.getElementById("dropzone");
    if (!dropzone) return;

    const dropRect = dropzone.getBoundingClientRect();
    const draggableItems = document.querySelectorAll(".draggable");

    draggableItems.forEach(el => {
        const elRect = el.getBoundingClientRect();

        // ambil posisi yang sudah diset dari database (data-x dan data-y)
        const x = parseFloat(el.getAttribute("data-x")) || 0;
        const y = parseFloat(el.getAttribute("data-y")) || 0;

        // jika transform dari DB belum diterapkan, tetap set dulu
        el.style.transform = `translate(${x}px, ${y}px)`;

        // hitung posisi real di layar setelah translate
        const inside =
            elRect.left + x >= dropRect.left &&
            elRect.right + x <= dropRect.right &&
            elRect.top + y >= dropRect.top &&
            elRect.bottom + y <= dropRect.bottom;

        if (inside) {
            // tambahkan ke itemDrop
            if (!itemDrop.includes(el.id)) {
                itemDrop.push(el.id);
            }
        }
    });

    updateKeterangan(); // tampilkan kembali daftar item yang sudah drop
    console.log("Item drop berhasil dipulihkan:", itemDrop);
}
