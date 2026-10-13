const dataMahasiswa = [
    { nama: "Jovan Steve Antony", nrp: "5025251038", jurusan: "Teknik Informatika" },
    { nama: "Hilmy Fausta Pratama", nrp: "5025251052", jurusan: "Teknik Informatika" },
    { nama: "Rado Putra Yustisiawan", nrp: "5025251048", jurusan: "Teknik Informatika" },
    { nama: "Aston Justin Holiwono", nrp: "5025251035", jurusan: "Teknik Informatika" },
    { nama: "Nabil Khairie", nrp: "5025251066", jurusan: "Teknik Informatika" },
    { nama: "Husna 'Akifah", nrp: "5025251049", jurusan: "Teknik Informatika" },
    { nama: "Yahdilil Haq Sarifuddin", nrp: "5025251021", jurusan: "Teknik Informatika" },
    { nama: "Firsto Al Kautsar Jagad Kurniaji", nrp: "5025251020", jurusan: "Teknik Informatika" },
    { nama: "Bayu Setyo Nugroho", nrp: "5025251061", jurusan: "Teknik Informatika" },
    { nama: "Julianda Caesar Prakoso", nrp: "5025251028", jurusan: "Teknik Informatika" },
    { nama: "Farrel Marvellino Sugianto", nrp: "5025251018", jurusan: "Teknik Informatika" },
    { nama: "Fakhrian Elanta", nrp: "5025251019", jurusan: "Teknik Informatika" },
    { nama: "Benedictus Ryu Gunawan", nrp: "5054251001", jurusan: "Teknik Informatika" },
    { nama: "Aziz Rahmad Arifin", nrp: "5054251002", jurusan: "Teknik Informatika" },
    { nama: "Firdaus Mangkona", nrp: "5054251003", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Azka Ananta Khairon", nrp: "5054251004", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Fasya Atthaya Rosyada", nrp: "5054251006", jurusan: "Teknik Informatika" },
    { nama: "Hilal Tsabitul Azmi Arba'i", nrp: "5054251008", jurusan: "Teknik Informatika" },
    { nama: "Pranaja Adyatma Budiman", nrp: "5054251009", jurusan: "Teknik Informatika" },
    { nama: "Rakha Makarim", nrp: "5054251010", jurusan: "Teknik Informatika" },
    { nama: "Maria Putu Evelyne Corena Puryatma", nrp: "5054251011", jurusan: "Teknik Informatika" },
    { nama: "Evina Fitriyani", nrp: "5054251013", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Rafie Safaraz Barus", nrp: "5054251014", jurusan: "Teknik Informatika" },
    { nama: "Fahmi Alfayadh", nrp: "5054251015", jurusan: "Teknik Informatika" },
    { nama: "Vincent Valentino", nrp: "5054251016", jurusan: "Teknik Informatika" },
    { nama: "Rafa Rizki Aira Swala", nrp: "5054251017", jurusan: "Teknik Informatika" },
    { nama: "Dinnetza Araafya Yudetti", nrp: "5054251018", jurusan: "Teknik Informatika" },
    { nama: "Rasya Arya Ramadhan", nrp: "5054251019", jurusan: "Teknik Informatika" },
    { nama: "Sandi Suryo Nugroho", nrp: "5054251020", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Fardan Hafidz", nrp: "5054251021", jurusan: "Teknik Informatika" },
    { nama: "Daffa Aufa Afif", nrp: "5054251022", jurusan: "Teknik Informatika" },
    { nama: "Zico Diego Rio Ramadhonny", nrp: "5054251023", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Irzam Hafis Fabiansyah", nrp: "5054251024", jurusan: "Teknik Informatika" },
    { nama: "Rayyan Binar Ramadhan", nrp: "5054251025", jurusan: "Teknik Informatika" },
    { nama: "Jonathan Joyo Wibowo", nrp: "5054251026", jurusan: "Teknik Informatika" },
    { nama: "Faiz Farrosian Karim", nrp: "5054251027", jurusan: "Teknik Informatika" },
    { nama: "Malfino Muhammad Willianz", nrp: "5054251028", jurusan: "Teknik Informatika" },
    { nama: "Intifada Afkar Lazain Muhammad", nrp: "5054251029", jurusan: "Teknik Informatika" },
    { nama: "Raihan Nurhakim", nrp: "5054251030", jurusan: "Teknik Informatika" },
    { nama: "Rasya Gonawi", nrp: "5054251032", jurusan: "Teknik Informatika" },
    { nama: "Levina Zahrathul Huda", nrp: "5054251033", jurusan: "Teknik Informatika" },
    { nama: "Fauzan Tamma Harish Kunfaza", nrp: "5054251035", jurusan: "Teknik Informatika" },
    { nama: "Prima Surya Nusantara", nrp: "5054251036", jurusan: "Teknik Informatika" },
    { nama: "Lioneil Diyoel Trystan Tikupadang", nrp: "5054251037", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Dzaky Haidar", nrp: "5054251039", jurusan: "Teknik Informatika" },
    { nama: "Maulana Rhys Pradana", nrp: "5054251041", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Farid Wijdan", nrp: "5054251042", jurusan: "Teknik Informatika" },
    { nama: "Ahmad Zaki Fauzan Nabil", nrp: "5054251044", jurusan: "Teknik Informatika" },
    { nama: "Mahardika Indra Pratama Ilyasa", nrp: "5054251045", jurusan: "Teknik Informatika" },
    { nama: "Suhail Ainur Rofiq", nrp: "5054251046", jurusan: "Teknik Informatika" },
    { nama: "A. Toriq azhar", nrp: "5054251047", jurusan: "Teknik Informatika" },
    { nama: "Sulaiman Faiz Tsaqib", nrp: "5054251048", jurusan: "Teknik Informatika" },
    { nama: "Kadek Nathania Gavrila Astika", nrp: "5054251050", jurusan: "Teknik Informatika" },
    { nama: "Rayyan Muhtar Ali", nrp: "5054251051", jurusan: "Teknik Informatika" },
    { nama: "Rokhmatul Ilma Khanifa", nrp: "5053251001", jurusan: "Teknik Informatika" },
    { nama: "Selly Aisyah", nrp: "5053251002", jurusan: "Teknik Informatika" },
    { nama: "Aurelia Pradnyaswari Sanjaya Erawan", nrp: "5053251003", jurusan: "Teknik Informatika" },
    { nama: "M. Khauzaky Amkanaky", nrp: "5053251004", jurusan: "Teknik Informatika" },
    { nama: "Dheva Alvian Alfarizzy Excellent", nrp: "5053251005", jurusan: "Teknik Informatika" },
    { nama: "Aurelia Nurdiansyah Putri", nrp: "5053251006", jurusan: "Teknik Informatika" },
    { nama: "Abdul Ghofur Luqman Salim", nrp: "5053251007", jurusan: "Teknik Informatika" },
    { nama: "Sulthan Zakinun Nasywa", nrp: "5053251008", jurusan: "Teknik Informatika" },
    { nama: "Muchammad Naufal Aziz", nrp: "5053251009", jurusan: "Teknik Informatika" },
    { nama: "Achmad Mirza Arzheta Rahman", nrp: "5053251011", jurusan: "Teknik Informatika" },
    { nama: "Hilmi Taqiyuddin Haq", nrp: "5053251012", jurusan: "Teknik Informatika" },
    { nama: "Hilmi Fauzi Adha Simamora", nrp: "5053251013", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Rafi Riansjah", nrp: "5053251014", jurusan: "Teknik Informatika" },
    { nama: "Hafidz Nur Ikhsan Isnaeni", nrp: "5053251015", jurusan: "Teknik Informatika" },
    { nama: "Daffa Randika", nrp: "5053251017", jurusan: "Teknik Informatika" },
    { nama: "MHD. RAVY ENSTEIN WAHIEDA ELMUNIF", nrp: "5053251018", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Nafis Al Khalifi", nrp: "5053251019", jurusan: "Teknik Informatika" },
    { nama: "yuwand arteta hydri wahyu putra", nrp: "5053251020", jurusan: "Teknik Informatika" },
    { nama: "AHMAD ZAIDAAN", nrp: "5053251021", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Hanif Nasrullah", nrp: "5053251022", jurusan: "Teknik Informatika" },
    { nama: "Afrel Zharif Muflih", nrp: "5053251023", jurusan: "Teknik Informatika" },
    { nama: "Nathan Alden", nrp: "5053251024", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Fakhry Ziyad Dhiyaulhaq", nrp: "5053251025", jurusan: "Teknik Informatika" },
    { nama: "Safia Rashida Raya", nrp: "5053251026", jurusan: "Teknik Informatika" },
    { nama: "Deara Briliana Putri", nrp: "5053251027", jurusan: "Teknik Informatika" },
    { nama: "Yeremia Gunawan", nrp: "5053251028", jurusan: "Teknik Informatika" },
    { nama: "Alvian Faza Wafda Reonaldi", nrp: "5053251029", jurusan: "Teknik Informatika" },
    { nama: "La Ode Muhammad Ghofaruddin S", nrp: "5053251030", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Hisyam Nurdy", nrp: "5053251031", jurusan: "Teknik Informatika" },
    { nama: "Daniel Dhaniswara", nrp: "5053251032", jurusan: "Teknik Informatika" },
    { nama: "Vincent Anindito Priyamboddo", nrp: "5053251033", jurusan: "Teknik Informatika" },
    { nama: "Nabila Ainiya Rahman", nrp: "5053251034", jurusan: "Teknik Informatika" },
    { nama: "Azendra Kenar Arviant", nrp: "5053251035", jurusan: "Teknik Informatika" },
    { nama: "Farrel Aryasatya Raharjo", nrp: "5053251036", jurusan: "Teknik Informatika" },
    { nama: "Marsya Safina Maulidiyah", nrp: "5053251038", jurusan: "Teknik Informatika" },
    { nama: "Devin Faza Raditya", nrp: "5053251039", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Zaydan Anugrah Pratama", nrp: "5053251040", jurusan: "Teknik Informatika" },
    { nama: "Moch Siril Wafa Zidane Feliano", nrp: "5053251041", jurusan: "Teknik Informatika" },
    { nama: "AHMAD BALYA MALKAN", nrp: "5053251042", jurusan: "Teknik Informatika" },
    { nama: "Muhammad Farel Al Farisi", nrp: "5053251043", jurusan: "Teknik Informatika" },
    { nama: "Fauzan Hasyim", nrp: "5053251044", jurusan: "Teknik Informatika" },
    { nama: "Dzakwan Ghonim Fath'han Mubina", nrp: "5053251045", jurusan: "Teknik Informatika" },
    { nama: "Reifan Al-fattii Cahyadewa", nrp: "5053251046", jurusan: "Teknik Informatika" },
    { nama: "Achmad Raffi Darmawan", nrp: "5053251047", jurusan: "Teknik Informatika" },
    { nama: "Rilo Zidane Afrianta Tambunan", nrp: "5053251048", jurusan: "Teknik Informatika" },
    { nama: "Matahari Gracio Sinaga", nrp: "5053251049", jurusan: "Teknik Informatika" },
    { nama: "Reza", nrp: "5053251050", jurusan: "Teknik Informatika" }
];

const dataDosen = [
    "Dr. Budi Santoso",
    "Prof. Siti Aminah",
    "Ridwan Kamil, M.Kom",
    "Rina Wati, S.T., M.T.",
    "Ahmad Fauzi, Ph.D."
];

const inputNama = document.getElementById("nama");
const inputNrp = document.getElementById("nrp");
const inputJurusan = document.getElementById("jurusan");
const inputMataKuliah = document.getElementById("mata_kuliah");
const inputDosen = document.getElementById("dosen");
const rekomendasiContainer = document.getElementById("rekomendasi");
const rekomendasiDosenContainer = document.getElementById("rekomendasi-dosen");
const form = document.getElementById("registrasiForm");

if (inputNama && rekomendasiContainer) {
    inputNama.addEventListener("input", function() {
        let val = this.value;
        closeAllLists();
        if (!val) { return false; }
        
        let matches = dataMahasiswa.filter(mhs => mhs.nama.toLowerCase().includes(val.toLowerCase()));
        
        matches.forEach(mhs => {
            let div = document.createElement("DIV");
            div.innerHTML = mhs.nama;
            div.innerHTML += "<input type='hidden' value='" + mhs.nama.replace(/'/g, "&#39;") + "'>";
            
            div.addEventListener("click", function() {
                inputNama.value = mhs.nama;
                if (inputNrp) inputNrp.value = mhs.nrp;
                if (inputJurusan) inputJurusan.value = mhs.jurusan;
                closeAllLists();
            });
            rekomendasiContainer.appendChild(div);
        });
    });
}

if (inputDosen && rekomendasiDosenContainer) {
    inputDosen.addEventListener("input", function() {
        let val = this.value;
        closeAllLists();
        if (!val) { return false; }
        
        let matches = dataDosen.filter(dosen => dosen.toLowerCase().includes(val.toLowerCase()));
        
        matches.forEach(dosen => {
            let div = document.createElement("DIV");
            div.innerHTML = dosen;
            div.innerHTML += "<input type='hidden' value='" + dosen.replace(/'/g, "&#39;") + "'>";
            
            div.addEventListener("click", function() {
                inputDosen.value = dosen;
                closeAllLists();
            });
            rekomendasiDosenContainer.appendChild(div);
        });
    });
}

function closeAllLists() {
    if (rekomendasiContainer) {
        while (rekomendasiContainer.firstChild) {
            rekomendasiContainer.removeChild(rekomendasiContainer.firstChild);
        }
    }
    if (rekomendasiDosenContainer) {
        while (rekomendasiDosenContainer.firstChild) {
            rekomendasiDosenContainer.removeChild(rekomendasiDosenContainer.firstChild);
        }
    }
}

document.addEventListener("click", function (e) {
    if (e.target !== inputNama && e.target !== inputDosen) {
        closeAllLists();
    }
});

if (form) {
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        
        if (!inputNama || inputNama.value.trim() === "") {
            alert("Nama Tidak Boleh Kosong");
            return;
        }
        if (!inputNrp || inputNrp.value.trim() === "") {
            alert("NRP Tidak Boleh Kosong");
            return;
        }
        if (!inputJurusan || inputJurusan.value.trim() === "") {
            alert("Jurusan Tidak Boleh Kosong");
            return;
        }
        if (!inputMataKuliah || inputMataKuliah.value.trim() === "") {
            alert("Mata Kuliah Tidak Boleh Kosong");
            return;
        }
        if (!inputDosen || inputDosen.value.trim() === "") {
            alert("Dosen Tidak Boleh Kosong");
            return;
        }
        
        alert("Berhasil Submit Form Registrasi!");
        form.reset();
        closeAllLists();
    });

    form.addEventListener("reset", function() {
        closeAllLists();
    });
}
