// ===============================
// DATA PRODUK
// ===============================
const products = [
    {
        id: "BRG001",
        barcode: "899123456001",
        name: "Indomie Goreng",
        price: 3500
    },
    {
        id: "BRG002",
        barcode: "899123456002",
        name: "Aqua 600ml",
        price: 4000
    },
    {
        id: "BRG003",
        barcode: "899123456003",
        name: "Teh Botol Sosro",
        price: 5000
    },
    {
        id: "BRG004",
        barcode: "899123456004",
        name: "Beras 5 Kg",
        price: 75000
    },
    {
        id: "BRG005",
        barcode: "899123456005",
        name: "Minyak Goreng 1 Liter",
        price: 18000
    },
    {
        id: "BRG006",
        barcode: "899123456006",
        name: "Gula Pasir 1 Kg",
        price: 17000
    }
];

// ===============================
// VARIABEL
// ===============================
let cart = [];
let selectedPaymentMethod = "Tunai";

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

    if (keyword === "") {
        resultBox.innerHTML = "";
        return;
    }

    const results = products.filter(product =>
        product.name.toLowerCase().includes(keyword) ||
        product.id.toLowerCase().includes(keyword) ||
        product.barcode.includes(keyword)
    );

    if (results.length === 0) {
        resultBox.innerHTML = `
            <div class="alert alert-warning">
                Produk tidak ditemukan
            </div>
        `;
        return;
    }

    resultBox.innerHTML = results.map(product => `
        <div class="product-result"
             onclick="addToCart('${product.id}')"
             style="
                padding:10px;
                border:1px solid #ddd;
                margin-top:5px;
                border-radius:8px;
                cursor:pointer;
                background:white;
             ">
            <strong>${product.name}</strong><br>
            <small>${product.id} | ${product.barcode}</small><br>
            <span>${formatRupiah(product.price)}</span>
        </div>
    `).join("");
}

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
                    <strong>${item.name}</strong><br>
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
function processPayment() {
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

    const change = payment - total;

    cetakStruk(
        total,
        payment,
        change
    );
}

// ===============================
// CETAK STRUK
// ===============================
function cetakStruk(total, payment, change) {

    const transactionNumber =
        "TRX-" + Date.now();

    const customer =
        document.getElementById("customer")?.value ||
        "Umum";

    const tanggal =
        new Date().toLocaleString("id-ID");

    let itemsHTML = "";

    cart.forEach(item => {
        itemsHTML += `
            <tr>
                <td>${item.name}</td>
                <td>${item.qty}</td>
                <td>${formatRupiah(item.price * item.qty)}</td>
            </tr>
        `;
    });

    const printWindow = window.open(
        "",
        "_blank",
        "width=500,height=700"
    );

    if (!printWindow) {
        alert(
            "Jendela cetak diblokir browser. Izinkan pop-up untuk website ini."
        );
        return;
    }

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Struk Pembayaran</title>

            <style>
                body {
                    font-family: Arial, sans-serif;
                    width: 80mm;
                    margin: auto;
                    padding: 10px;
                }

                h2 {
                    text-align: center;
                    margin-bottom: 5px;
                }

                p {
                    margin: 4px 0;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }

                th,
                td {
                    padding: 5px 0;
                    border-bottom: 1px dashed #999;
                    font-size: 12px;
                }

                .total {
                    font-weight: bold;
                    font-size: 14px;
                }

                .center {
                    text-align: center;
                }
            </style>
        </head>

        <body>

            <h2>TOKO SAYA</h2>

            <p class="center">
                Struk Pembayaran
            </p>

            <hr>

            <p>
                No: ${transactionNumber}
            </p>

            <p>
                Tanggal: ${tanggal}
            </p>

            <p>
                Pelanggan: ${customer}
            </p>

            <p>
                Metode: ${selectedPaymentMethod}
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    ${itemsHTML}
                </tbody>
            </table>

            <br>

            <p class="total">
                Total:
                ${formatRupiah(total)}
            </p>

            <p>
                Bayar:
                ${formatRupiah(payment)}
            </p>

            <p>
                Kembali:
                ${formatRupiah(change)}
            </p>

            <hr>

            <p class="center">
                Terima kasih
            </p>

        </body>
        </html>
    `);

    printWindow.document.close();

    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
    }, 500);
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