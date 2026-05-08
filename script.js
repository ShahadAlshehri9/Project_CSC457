// تفعيل الوضع الليلي وحفظه في المتصفح
function toggleNight() {
    document.body.classList.toggle("night");
    const isNight = document.body.classList.contains("night");
    try {
        localStorage.setItem("nightMode", isNight ? "1" : "0");
    } catch (e) {}
    const btn = document.getElementById("nightBtn");
    if (btn) btn.textContent = isNight ? "الوضع النهاري" : "الوضع الليلي";
}

// استرجاع الوضع المحفوظ عند تحميل الصفحة
(function () {
    try {
        if (localStorage.getItem("nightMode") === "1") {
            document.body.classList.add("night");
            window.addEventListener("DOMContentLoaded", function () {
                const btn = document.getElementById("nightBtn");
                if (btn) btn.textContent = "الوضع النهاري";
            });
        }
    } catch (e) {}
})();

// فلترة المعرض
function filterGallery() {
    const search = (document.getElementById("searchInput").value || "").trim().toLowerCase();
    const category = document.getElementById("categorySelect").value;
    const cards = document.querySelectorAll(".place-card");
    let visible = 0;

    cards.forEach(function (card) {
        const name = (card.dataset.name || "").toLowerCase();
        const desc = (card.dataset.desc || "").toLowerCase();
        const cat = card.dataset.category || "";
        const matchSearch = !search || name.indexOf(search) !== -1 || desc.indexOf(search) !== -1;
        const matchCategory = !category || category === "all" || cat === category;
        if (matchSearch && matchCategory) {
            card.style.display = "";
            visible++;
        } else {
            card.style.display = "none";
        }
    });

    const counter = document.getElementById("resultCount");
    if (counter) counter.textContent = "عدد النتائج: " + visible;
}

// تأكيد الحذف
function confirmDelete(id) {
    if (confirm("هل تريد حذف هذا السجل؟")) {
        window.location.href = "delete.php?id=" + id;
    }
    return false;
}
