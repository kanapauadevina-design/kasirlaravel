// ===============================
// DATA PRODUK
// ===============================
let products = window.kasirProducts.map(product => ({
    ...product,
    id: Number(product.id),
    price: Number(product.price)
}));

// ===============================
// VARIABEL
// ===============================
let cart = [];
let selectedPaymentMethod = "Tunai";
let pendingReceipt = null;

// ===============================
// FORMAT RUPIAH
// ===============================
function formatRupiah(number) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
    }).format(number);
}

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, character => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    })[character]);
}

// ===============================
// PENCARIAN PRODUK
// ===============================
const searchInput = document.getElementById("searchProduct");

if (searchInput) {
    searchInput.addEventListener("input", function () {
        searchProduct(this.value);
    });
}

function searchProduct(keyword) {
    const resultBox = document.getElementById("productResults");

    if (!resultBox) return;

    keyword = keyword.toLowerCase().trim();

    const results = products.filter(product =>
        product.name.toLowerCase().includes(keyword) ||
        String(product.id).includes(keyword)
    );

    if (results.length === 0) {
        resultBox.innerHTML = `
            <p class="empty-cart">${keyword ? "Produk tidak ditemukan." : "Belum ada produk aktif di katalog."}</p>
        `;
        return;
    }

    resultBox.innerHTML = results.map(product => `
        <div class="product-result"
             onclick="addToCart(${Number(product.id)})"
             style="
                padding:10px;
                border:1px solid #ddd;
                margin-top:5px;
                border-radius:8px;
                cursor:pointer;
                background:white;
             ">
            <strong>${escapeHtml(product.name)}</strong><br>
            <small>ID: ${product.id}</small><br>
            <span>${formatRupiah(product.price)}</span>
        </div>
    `).join("");
}

async function refreshProductCatalog() {
    if (document.hidden) {
        return;
    }

    try {
        const response = await fetch(window.kasirCatalogUrl, {
            credentials: "same-origin",
            cache: "no-store",
            headers: {
                "Accept": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error("Server gagal memperbarui katalog produk.");
        }

        const result = await response.json();
        const latestProducts = result.products.map(product => ({
            ...product,
            id: Number(product.id),
            price: Number(product.price)
        }));
        const latestById = new Map(latestProducts.map(product => [product.id, product]));
        const removedCount = cart.filter(item => !latestById.has(item.id)).length;
        const changedCount = cart.filter(item => {
            const latest = latestById.get(item.id);
            return latest && (latest.name !== item.name || latest.price !== item.price);
        }).length;

        cart = cart
            .filter(item => latestById.has(item.id))
            .map(item => ({
                ...latestById.get(item.id),
                qty: item.qty
            }));
        products = latestProducts;

        searchProduct(searchInput?.value || "");

        if (removedCount || changedCount) {
            renderCart();
            updateTotal();
        }

        const status = document.getElementById("receiptStatus");
        if ((removedCount || changedCount) && status) {
            status.className = "receipt-status";
            status.textContent = [
                removedCount ? `${removedCount} produk dihapus/nonaktif dan dikeluarkan dari keranjang.` : "",
                changedCount ? `${changedCount} produk di keranjang diperbarui mengikuti data admin.` : ""
            ].filter(Boolean).join(" ");
        }
    } catch (error) {
        const status = document.getElementById("receiptStatus");
        if (status) {
            status.className = "receipt-status is-error";
            status.textContent = "Katalog produk gagal diperbarui. Periksa koneksi, lalu muat ulang halaman kasir.";
        }
    }
}

searchProduct(searchInput?.value || "");
refreshProductCatalog();
window.setInterval(refreshProductCatalog, 5000);

// ===============================
// TAMBAH PRODUK KE KERANJANG
// ===============================
function addToCart(productId) {
    const product = products.find(item => item.id === productId);

    if (!product) return;

    const existingProduct = cart.find(item => item.id === productId);

    if (existingProduct) {
        existingProduct.qty++;
    } else {
        cart.push({
            ...product,
            qty: 1
        });
    }

    const search = document.getElementById("searchProduct");

    if (search) {
        search.value = "";
    }

    const results = document.getElementById("productResults");

    if (results) {
        results.innerHTML = "";
    }

    renderCart();
    updateTotal();
}

// ===============================
// TAMPILKAN KERANJANG
// ===============================
function renderCart() {
    const cartBody = document.getElementById("cartBody");

    if (!cartBody) return;

    cartBody.innerHTML = "";

    if (cart.length === 0) {
        cartBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">
                    Belum ada produk
                </td>
            </tr>
        `;

        updateItemCount();
        return;
    }

    cart.forEach((item, index) => {
        const subtotal = item.price * item.qty;

        cartBody.innerHTML += `
            <tr>
                <td>${index + 1}</td>

                <td>
                    <strong>${escapeHtml(item.name)}</strong><br>
                    <small>${item.id}</small>
                </td>

                <td>
                    ${formatRupiah(item.price)}
                </td>

                <td>
                    <button
                        type="button"
                        onclick="changeQty(${index}, -1)"
                        class="btn btn-sm btn-secondary">
                        -
                    </button>

                    <input
                        type="number"
                        min="1"
                        value="${item.qty}"
                        onchange="changeQtyInput(${index}, this.value)"
                        style="width:60px;text-align:center;"
                    >

                    <button
                        type="button"
                        onclick="changeQty(${index}, 1)"
                        class="btn btn-sm btn-secondary">
                        +
                    </button>
                </td>

                <td>
                    ${formatRupiah(subtotal)}
                </td>

                <td>
                    <button
                        type="button"
                        onclick="removeItem(${index})"
                        class="btn btn-sm btn-danger">
                        Hapus
                    </button>
                </td>
            </tr>
        `;
    });

    updateItemCount();
}

// ===============================
// UBAH JUMLAH PRODUK
// ===============================
function changeQty(index, amount) {
    if (!cart[index]) return;

    cart[index].qty += amount;

    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    }

    renderCart();
    updateTotal();
}

// ===============================
// INPUT JUMLAH
// ===============================
function changeQtyInput(index, value) {
    let qty = parseInt(value);

    if (isNaN(qty) || qty < 1) {
        qty = 1;
    }

    cart[index].qty = qty;

    renderCart();
    updateTotal();
}

// ===============================
// HAPUS PRODUK
// ===============================
function removeItem(index) {
    if (!cart[index]) return;

    cart.splice(index, 1);

    renderCart();
    updateTotal();
}

// ===============================
// JUMLAH ITEM
// ===============================
function updateItemCount() {
    const itemCount = document.getElementById("itemCount");
    const totalQty = document.getElementById("totalQty");

    const total = cart.reduce(
        (sum, item) => sum + item.qty,
        0
    );

    if (itemCount) {
        itemCount.textContent = total;
    }

    if (totalQty) {
        totalQty.textContent = total;
    }
}

// ===============================
// HITUNG TOTAL
// ===============================
function updateTotal() {
    const subtotalElement = document.getElementById("subtotal");
    const discountPercentElement =
        document.getElementById("discountPercent");
    const discountAmountElement =
        document.getElementById("discountAmount");
    const taxElement = document.getElementById("tax");
    const otherFeeElement = document.getElementById("otherFee");
    const grandTotalElement = document.getElementById("grandTotal");

    const subtotal = cart.reduce(
        (sum, item) => sum + (item.price * item.qty),
        0
    );

    const discountPercent =
        parseFloat(discountPercentElement?.value) || 0;

    const discountAmount =
        subtotal * discountPercent / 100;

    const tax =
        parseFloat(taxElement?.value) || 0;

    const otherFee =
        parseFloat(otherFeeElement?.value) || 0;

    const grandTotal =
        subtotal -
        discountAmount +
        tax +
        otherFee;

    if (subtotalElement) {
        subtotalElement.textContent =
            formatRupiah(subtotal);
    }

    if (discountAmountElement) {
        discountAmountElement.textContent =
            formatRupiah(discountAmount);
    }

    if (grandTotalElement) {
        grandTotalElement.textContent =
            formatRupiah(grandTotal);
    }

    updateChange();
}

// ===============================
// EVENT DISKON / PAJAK / BIAYA
// ===============================
document
    .getElementById("discountPercent")
    ?.addEventListener("input", updateTotal);

document
    .getElementById("tax")
    ?.addEventListener("input", updateTotal);

document
    .getElementById("otherFee")
    ?.addEventListener("input", updateTotal);

// ===============================
// HITUNG KEMBALIAN
// ===============================
function updateChange() {
    const paymentElement =
        document.getElementById("payment");

    const changeElement =
        document.getElementById("change");

    const totalElement =
        document.getElementById("grandTotal");

    if (!paymentElement || !changeElement || !totalElement) {
        return;
    }

    const payment =
        parseFloat(paymentElement.value) || 0;

    const total =
        parseFloat(
            totalElement.textContent
                .replace(/[^\d]/g, "")
        ) || 0;

    const change = payment - total;

    if (change >= 0) {
        changeElement.textContent =
            formatRupiah(change);
    } else {
        changeElement.textContent =
            "Uang kurang " + formatRupiah(Math.abs(change));
    }
}

// ===============================
// EVENT PEMBAYARAN
// ===============================
document
    .getElementById("payment")
    ?.addEventListener("input", updateChange);

// ===============================
// PILIH METODE PEMBAYARAN
// ===============================
function selectPayment(button, method) {
    selectedPaymentMethod = method;

    document
        .querySelectorAll(".payment-method")
        .forEach(btn => {
            btn.classList.remove("active");
        });

    if (button) {
        button.classList.add("active");
    }
}

const defaultPaymentButton = document.querySelector(".payment-method.active");
if (defaultPaymentButton) {
    selectedPaymentMethod = defaultPaymentButton.textContent.trim();
}

// ===============================
// AMBIL TOTAL
// ===============================
function getGrandTotal() {
    const subtotal = cart.reduce(
        (sum, item) => sum + (item.price * item.qty),
        0
    );

    const discountPercent =
        parseFloat(
            document.getElementById("discountPercent")?.value
        ) || 0;

    const discount =
        subtotal * discountPercent / 100;

    const tax =
        parseFloat(
            document.getElementById("tax")?.value
        ) || 0;

    const otherFee =
        parseFloat(
            document.getElementById("otherFee")?.value
        ) || 0;

    return subtotal - discount + tax + otherFee;
}

// ===============================
// BAYAR & CETAK
// ===============================
async function processPayment() {
    if (cart.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    const total = getGrandTotal();

    const payment =
        parseFloat(
            document.getElementById("payment")?.value
        ) || 0;

    if (payment < total) {
        alert(
            "Pembayaran kurang sebesar " +
            formatRupiah(total - payment)
        );
        return;
    }

    const printWindow = window.open("", "_blank", "width=420,height=700");
    if (!printWindow) {
        statusMessage(
            "Popup cetak diblokir browser. Izinkan popup untuk situs ini, lalu coba lagi.",
            "error"
        );
        return;
    }

    const printButton = document.querySelector(".btn-pay");
    const receiptActions = document.getElementById("receiptActions");
    pendingReceipt = null;
    if (receiptActions) {
        receiptActions.hidden = true;
    }

    if (printButton) {
        printButton.disabled = true;
    }

    statusMessage("Memvalidasi transaksi, lalu membuka pilihan printer...", "");

    try {
        const response = await fetch(window.kasirReceiptPrepareUrl, {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            },
            body: JSON.stringify({
                items: cart.map(item => ({
                    id: item.id,
                    qty: item.qty
                })),
                customer: document.getElementById("customer").value || "Umum",
                payment_method: selectedPaymentMethod,
                discount_percent: Number(document.getElementById("discountPercent").value) || 0,
                tax: Number(document.getElementById("tax").value) || 0,
                other_fee: Number(document.getElementById("otherFee").value) || 0,
                payment
            })
        });

        const result = await response.json();

        if (!response.ok) {
            const validationMessage = Object.values(result.errors || {})
                .flat()
                .join(" ");
            throw new Error(validationMessage || result.message || "Transaksi gagal divalidasi.");
        }

        pendingReceipt = result.receipt;
        printReceiptInWindow(printWindow, pendingReceipt);
    } catch (error) {
        printWindow.close();
        statusMessage(error.message || "Transaksi gagal divalidasi.", "error");
    } finally {
        if (printButton) {
            printButton.disabled = false;
        }
    }
}

function statusMessage(message, type) {
    const status = document.getElementById("receiptStatus");
    if (!status) return;

    status.className = type ? `receipt-status is-${type}` : "receipt-status";
    status.textContent = message;
}

function printReceiptInWindow(printWindow, receipt) {
    const rows = receipt.items.map(item => `
        <div class="item">
            <div>${escapeHtml(item.name)}</div>
            <div class="item-detail">
                <span>${item.quantity} x ${formatRupiah(item.unit_price_cents / 100)}</span>
                <span>${formatRupiah(item.line_total_cents / 100)}</span>
            </div>
        </div>
    `).join("");

    const amountRow = (label, cents, className = "") => `
        <div class="amount ${className}">
            <span>${label}</span>
            <span>${formatRupiah(cents / 100)}</span>
        </div>
    `;

    printWindow.document.open();
    printWindow.document.write(`
        <!doctype html>
        <html lang="id">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Struk ${escapeHtml(receipt.transaction_number)}</title>
            <style>
                @page { size: 80mm auto; margin: 3mm; }
                * { box-sizing: border-box; }
                body { width: 74mm; margin: 0 auto; color: #000; font: 14px/1.5 Arial, Helvetica, sans-serif; font-weight: 700; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                header, .center { text-align: center; }
                h1 { margin: 0; font-size: 22px; font-weight: 900; }
                p { margin: 4px 0; }
                .divider { margin: 9px 0; border-top: 2px dashed #000; }
                .meta { overflow-wrap: anywhere; font-weight: 700; }
                .item { margin: 7px 0; overflow-wrap: anywhere; font-weight: 700; }
                .item-detail, .amount { display: flex; justify-content: space-between; gap: 8px; }
                .item-detail { padding-left: 6px; }
                .item-detail span:last-child, .amount span:last-child { text-align: right; }
                .total { font-weight: 900; font-size: 16px; }
                .barcode { margin: 12px auto 4px; text-align: center; }
                .barcode img { max-width: 100%; }
                @media screen {
                    body { margin: 16px auto; padding: 8px; border: 1px solid #ddd; }
                }
            </style>
        </head>
        <body>
            <header>
                <h1>VioraMart</h1>
                <p>Struk Pembayaran</p>
            </header>
            <div class="divider"></div>
            <div class="meta">
                <p>No: ${escapeHtml(receipt.transaction_number)}</p>
                <p>Tanggal: ${escapeHtml(new Date(receipt.created_at).toLocaleString("id-ID"))}</p>
                <p>Kasir: ${escapeHtml(receipt.cashier_name)}</p>
                <p>Pelanggan: ${escapeHtml(receipt.customer)}</p>
                <p>Metode: ${escapeHtml(receipt.payment_method)}</p>
            </div>
            <div class="divider"></div>
            ${rows}
            <div class="divider"></div>
            ${amountRow("Subtotal", receipt.subtotal_cents)}
            ${amountRow("Diskon", -receipt.discount_cents)}
            ${amountRow("Pajak", receipt.tax_cents)}
            ${amountRow("Biaya lain", receipt.other_fee_cents)}
            ${amountRow("TOTAL", receipt.total_cents, "total")}
            ${amountRow("Bayar", receipt.payment_cents)}
            ${amountRow("Kembali", receipt.change_cents)}
            <div class="divider"></div>
            <div class="center">
                <p>Terima kasih telah berbelanja</p>
                <p>${escapeHtml(receipt.transaction_number)}</p>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();

    printWindow.addEventListener("afterprint", () => {
        printWindow.close();
        statusMessage(
            "Dialog printer ditutup. Periksa hasil cetak, lalu cetak ulang atau selesaikan transaksi.",
            "success"
        );
        const receiptActions = document.getElementById("receiptActions");
        if (receiptActions) {
            receiptActions.hidden = false;
        }
    }, { once: true });

    window.setTimeout(() => {
        printWindow.focus();
        printWindow.print();
    }, 250);
}

function reprintReceipt() {
    if (!pendingReceipt) return;

    const printWindow = window.open("", "_blank", "width=420,height=700");
    if (!printWindow) {
        statusMessage("Popup cetak diblokir browser. Izinkan popup untuk situs ini.", "error");
        return;
    }

    printReceiptInWindow(printWindow, pendingReceipt);
}

function finishReceipt() {
    if (!pendingReceipt) return;

    cart = [];
    pendingReceipt = null;
    document.getElementById("payment").value = "";
    document.getElementById("discountPercent").value = 0;
    document.getElementById("tax").value = 0;
    document.getElementById("otherFee").value = 0;

    const receiptActions = document.getElementById("receiptActions");
    if (receiptActions) {
        receiptActions.hidden = true;
    }

    renderCart();
    updateTotal();
    statusMessage("Transaksi selesai.", "success");
}

// ===============================
// TAHAN TRANSAKSI
// ===============================
function holdTransaction() {
    if (cart.length === 0) {
        alert("Tidak ada transaksi untuk ditahan.");
        return;
    }

    alert("Transaksi berhasil ditahan.");
}

// ===============================
// BATAL TRANSAKSI
// ===============================
function cancelTransaction() {

    const confirmCancel =
        confirm("Apakah Anda yakin ingin membatalkan transaksi?");

    if (!confirmCancel) {
        return;
    }

    cart = [];

    const payment =
        document.getElementById("payment");

    const discount =
        document.getElementById("discountPercent");

    if (payment) {
        payment.value = "";
    }

    if (discount) {
        discount.value = 0;
    }

    renderCart();
    updateTotal();

    alert("Transaksi berhasil dibatalkan.");
}

// ===============================
// TANGGAL
// ===============================
const dateElement =
    document.getElementById("transactionDate");

if (dateElement) {
    dateElement.textContent =
        new Date().toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric"
        });
}

// ===============================
// KEYBOARD SHORTCUT
// ===============================
document.addEventListener("keydown", function (event) {

    // F2 = fokus pencarian
    if (event.key === "F2") {
        event.preventDefault();

        const search =
            document.getElementById("searchProduct");

        if (search) {
            search.focus();
        }
    }

    // F4 = fokus pembayaran
    if (event.key === "F4") {
        event.preventDefault();

        const payment =
            document.getElementById("payment");

        if (payment) {
            payment.focus();
        }
    }

    // Escape = batal
    if (event.key === "Escape") {
        cancelTransaction();
    }
});

// ===============================
// JALANKAN SAAT HALAMAN DIBUKA
// ===============================
renderCart();
updateTotal();