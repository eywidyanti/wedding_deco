// === Fungsi pergerakan drag ===
function dragMoveListener(event) {
    var target = event.target;
    var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
    var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

    target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
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
    if (!itemDrop.includes(event.relatedTarget.id)) {
        itemDrop.push(event.relatedTarget.id);
    }
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
        listeners: { move: dragMoveListener }
    });

    console.log("Interact.js sudah direset dan aktif kembali.");
}

// Jalankan pertama kali halaman dibuka
document.addEventListener("DOMContentLoaded", resetInteract);
