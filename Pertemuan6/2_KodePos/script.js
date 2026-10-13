const API_WILAYAH_BASE = 'https://www.emsifa.com/api-wilayah-indonesia/api';
const API_WILAYAH_BACKUP = 'https://raw.githubusercontent.com/emsifa/api-wilayah-indonesia/gh-pages/api';
const API_KODEPOS_SEARCH = 'https://kodepos.vercel.app/search/?q=';

const provSelect = document.getElementById('provinsi');
const kotaSelect = document.getElementById('kota');
const kecSelect = document.getElementById('kecamatan');
const keywordInput = document.getElementById('keyword');
const btnCari = document.getElementById('btn-cari');
const resultSection = document.getElementById('result-section');
const resultSummary = document.getElementById('result-summary');
const resultCardsContainer = document.getElementById('result-cards-container');

function toTitleCase(str) {
    if (!str) return '';
    return str
        .toLowerCase()
        .replace(/(?:^|\s|\/|-)\S/g, (char) => char.toUpperCase());
}

function cleanWilayahName(name) {
    if (!name) return '';
    return name
        .toLowerCase()
        .replace(/^(kabupaten|kab\.|kota|provinsi|adm\.|administrasi)\s+/i, '')
        .replace(/^(daerah\s+khusus\s+ibukota|dki)\s+/i, '')
        .replace(/^(daerah\s+istimewa|di)\s+/i, '')
        .trim();
}

function normalizeForComparison(name) {
    if (!name) return '';
    return cleanWilayahName(name)
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '');
}

function isRegionMatch(selectedRegionName, apiRegionName) {
    if (!selectedRegionName) return true;
    if (!apiRegionName) return false;

    const selectedNorm = normalizeForComparison(selectedRegionName);
    const apiNorm = normalizeForComparison(apiRegionName);

    return selectedNorm === apiNorm;
}

function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

async function fetchWithFallback(primaryUrl, fallbackUrl) {
    try {
        const res = await fetch(primaryUrl);
        if (res.ok) return await res.json();
    } catch (err) {
        console.warn(`Primary URL (${primaryUrl}) gagal diakses, mencoba fallback URL...`, err);
    }

    if (fallbackUrl) {
        const resFallback = await fetch(fallbackUrl);
        if (resFallback.ok) return await resFallback.json();
    }

    throw new Error('Gagal mengambil data dari server wilayah.');
}

async function initProvinsi() {
    provSelect.innerHTML = '<option value="">Loading...</option>';
    provSelect.disabled = true;

    try {
        const provinces = await fetchWithFallback(
            `${API_WILAYAH_BASE}/provinces.json`,
            `${API_WILAYAH_BACKUP}/provinces.json`
        );

        provSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
        provinces.forEach(prov => {
            const option = document.createElement('option');
            option.value = prov.id;
            option.dataset.nama = prov.name;
            option.textContent = toTitleCase(prov.name);
            provSelect.appendChild(option);
        });
        provSelect.disabled = false;
    } catch (error) {
        console.error('Error saat memuat provinsi:', error);
        provSelect.innerHTML = '<option value="">Gagal memuat provinsi</option>';
        alert('Gagal mengambil data wilayah. Periksa koneksi Anda.');
    }
}

provSelect.addEventListener('change', async function() {
    const selectedProvId = this.value;

    kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
    kotaSelect.disabled = true;
    kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kecSelect.disabled = true;

    if (!selectedProvId) return;

    kotaSelect.innerHTML = '<option value="">Loading...</option>';

    try {
        const regencies = await fetchWithFallback(
            `${API_WILAYAH_BASE}/regencies/${selectedProvId}.json`,
            `${API_WILAYAH_BACKUP}/regencies/${selectedProvId}.json`
        );

        kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        regencies.forEach(kota => {
            const option = document.createElement('option');
            option.value = kota.id;
            option.dataset.nama = kota.name;
            option.textContent = toTitleCase(kota.name);
            kotaSelect.appendChild(option);
        });
        kotaSelect.disabled = false;
    } catch (error) {
        console.error('Error saat memuat kota/kabupaten:', error);
        kotaSelect.innerHTML = '<option value="">Gagal memuat kota/kabupaten</option>';
        alert('Gagal mengambil data wilayah. Periksa koneksi Anda.');
    }
});

kotaSelect.addEventListener('change', async function() {
    const selectedKotaId = this.value;

    kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kecSelect.disabled = true;

    if (!selectedKotaId) return;

    kecSelect.innerHTML = '<option value="">Loading...</option>';

    try {
        const districts = await fetchWithFallback(
            `${API_WILAYAH_BASE}/districts/${selectedKotaId}.json`,
            `${API_WILAYAH_BACKUP}/districts/${selectedKotaId}.json`
        );

        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        districts.forEach(kec => {
            const option = document.createElement('option');
            option.value = kec.id;
            option.dataset.nama = kec.name;
            option.textContent = toTitleCase(kec.name);
            kecSelect.appendChild(option);
        });
        kecSelect.disabled = false;
    } catch (error) {
        console.error('Error saat memuat kecamatan:', error);
        kecSelect.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
        alert('Gagal mengambil data wilayah. Periksa koneksi Anda.');
    }
});

btnCari.addEventListener('click', lakukanPencarian);
keywordInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        lakukanPencarian();
    }
});

async function lakukanPencarian() {
    const keyword = keywordInput.value.trim();

    if (!keyword) {
        alert("Silakan masukkan kata kunci pencarian (Kode Pos atau Kelurahan).");
        return;
    }

    const selectedProvOption = provSelect.options[provSelect.selectedIndex];
    const selectedKotaOption = kotaSelect.options[kotaSelect.selectedIndex];
    const selectedKecOption = kecSelect.options[kecSelect.selectedIndex];

    const selectedProvName = (provSelect.value && selectedProvOption) ? (selectedProvOption.dataset.nama || '') : '';
    const selectedKotaName = (kotaSelect.value && selectedKotaOption) ? (selectedKotaOption.dataset.nama || '') : '';
    const selectedKecName = (kecSelect.value && selectedKecOption) ? (selectedKecOption.dataset.nama || '') : '';

    resultSection.style.display = 'block';
    resultSummary.textContent = `Sedang mencari kode pos untuk kata kunci "${keyword}"...`;
    resultCardsContainer.innerHTML = '<p>Loading...</p>';
    btnCari.disabled = true;

    try {
        const queries = [keyword];

        const contextWords = [];
        if (selectedKecName) contextWords.push(cleanWilayahName(selectedKecName));
        if (selectedKotaName) contextWords.push(cleanWilayahName(selectedKotaName));
        if (selectedProvName && !selectedKotaName) contextWords.push(cleanWilayahName(selectedProvName));

        if (contextWords.length > 0) {
            queries.push(`${keyword} ${contextWords.join(' ')}`);
        }

        const rawResults = [];
        const seenKeys = new Set();

        for (const q of queries) {
            try {
                const response = await fetch(`${API_KODEPOS_SEARCH}${encodeURIComponent(q)}`);
                if (response.ok) {
                    const json = await response.json();
                    if (json && Array.isArray(json.data)) {
                        json.data.forEach(item => {
                            const uniqueKey = `${item.code}_${item.village}_${item.district}_${item.regency}`.toLowerCase();
                            if (!seenKeys.has(uniqueKey)) {
                                seenKeys.add(uniqueKey);
                                rawResults.push(item);
                            }
                        });
                    }
                }
            } catch (queryErr) {
                console.warn(`Pencarian API untuk query "${q}" gagal:`, queryErr);
            }
        }

        const hasilPencarian = rawResults.filter(item => {
            if (selectedProvName && !isRegionMatch(selectedProvName, item.province)) {
                return false;
            }
            if (selectedKotaName && !isRegionMatch(selectedKotaName, item.regency)) {
                return false;
            }
            if (selectedKecName && !isRegionMatch(selectedKecName, item.district)) {
                return false;
            }
            return true;
        });

        tampilkanHasil(hasilPencarian, keyword);
    } catch (error) {
        console.error('Error saat pencarian:', error);
        resultSummary.textContent = 'Terjadi kesalahan saat memuat data.';
        resultCardsContainer.innerHTML = '<p>Gagal mengambil data wilayah. Periksa koneksi Anda.</p>';
        alert('Gagal mengambil data wilayah. Periksa koneksi Anda.');
    } finally {
        btnCari.disabled = false;
    }
}

function tampilkanHasil(hasil, keywordAsli) {
    resultSection.style.display = 'block';
    resultCardsContainer.innerHTML = '';

    resultSummary.textContent = `Hasil pencarian kode pos dengan menggunakan kata kunci "${keywordAsli}" telah ditemukan sebanyak ${hasil.length} buah.`;

    if (hasil.length === 0) {
        resultCardsContainer.innerHTML = '<p>Data kode pos tidak ditemukan untuk kata kunci tersebut.</p>';
        return;
    }

    hasil.forEach(item => {
        const kelurahan = item.village || item.kelurahan || '-';
        const kodePos = item.code || item.kodePos || '-';
        const kecamatan = item.district || item.kecamatan || '-';

        const card = document.createElement('div');
        card.className = 'result-card';
        card.innerHTML = `
            <div class="card-header">${escapeHtml(kelurahan)}</div>
            <div class="card-body">
                <p>Kode pos <strong>${escapeHtml(String(kodePos))}</strong> untuk daerah Kelurahan ${escapeHtml(kelurahan)}, Kecamatan ${escapeHtml(kecamatan)}.</p>
            </div>
        `;
        resultCardsContainer.appendChild(card);
    });
}

initProvinsi();
