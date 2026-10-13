const dataProduk = {
    Desktop: ["Dell", "HP", "Lenovo", "Apple iMac", "Rakitan"],
    Laptop: ["ASUS", "Lenovo", "HP", "Acer", "Apple MacBook", "MSI", "Dell"],
    Smartphone: ["Samsung", "Apple iPhone", "Xiaomi", "Oppo", "Vivo", "Realme"]
};

const jenisProdukSelect = document.getElementById("jenisProduk");
const merkSelect = document.getElementById("merk");

jenisProdukSelect.addEventListener("change", function () {
    merkSelect.innerHTML = "";

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Pilih Merk";
    merkSelect.appendChild(defaultOption);

    const selectedJenis = this.value;

    if (selectedJenis && dataProduk[selectedJenis]) {
        dataProduk[selectedJenis].forEach(function (merk) {
            const option = document.createElement("option");
            option.value = merk;
            option.textContent = merk;
            merkSelect.appendChild(option);
        });
    }
});
