const STORAGE_KEY = 'LIBRARYREG_MEMBERS_DATA';

function escapeHtml(text) {
  if (text === null || text === undefined) return '';
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function getMembers() {
  const storedData = localStorage.getItem(STORAGE_KEY);
  if (storedData === null) {
    const defaultData = [
      {
        nama: "Ahmad Dahlan",
        nomorInduk: "5025211001",
        prodi: "Teknik Informatika",
        kategori: "Mahasiswa",
        email: "ahmad.dahlan@student.its.ac.id",
        telepon: "081234567890",
        alamat: "Keputih Gang 2 No. 15, Sukolilo, Surabaya"
      },
      {
        nama: "Dr. Siti Aminah, S.Kom., M.Kom.",
        nomorInduk: "198503152010122001",
        prodi: "Sistem Informasi",
        kategori: "Dosen",
        email: "siti.aminah@its.ac.id",
        telepon: "081298765432",
        alamat: "Perumdos ITS Blok U No. 12, Surabaya"
      },
      {
        nama: "Budi Santoso, S.T.",
        nomorInduk: "199001012015011002",
        prodi: "Teknologi Informasi",
        kategori: "Tenaga Kependidikan",
        email: "budi.s@its.ac.id",
        telepon: "081345678901",
        alamat: "Gebang Wetan No. 45, Sukolilo, Surabaya"
      }
    ];
    saveMembers(defaultData);
    return defaultData;
  }

  try {
    const parsed = JSON.parse(storedData);
    return Array.isArray(parsed) ? parsed : [];
  } catch (error) {
    console.error("Gagal membaca data localStorage:", error);
    return [];
  }
}

function saveMembers(data) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
  updateLandingStats();
}

function updateLandingStats() {
  const statMahasiswa = document.getElementById('statMahasiswa');
  const statDosen = document.getElementById('statDosen');
  const statTendik = document.getElementById('statTendik');

  if (!statMahasiswa && !statDosen && !statTendik) {
    return;
  }

  const members = getMembers();

  const countMahasiswa = members.filter((m) => m.kategori === 'Mahasiswa').length;
  const countDosen = members.filter((m) => m.kategori === 'Dosen').length;
  const countTendik = members.filter((m) => m.kategori === 'Tenaga Kependidikan').length;

  if (statMahasiswa) {
    statMahasiswa.textContent = countMahasiswa;
  }
  if (statDosen) {
    statDosen.textContent = countDosen;
  }
  if (statTendik) {
    statTendik.textContent = countTendik;
  }
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

//Pendaftaran
const formPendaftaran = document.getElementById('formPendaftaran');
if (formPendaftaran) {
  const selectProdi = document.getElementById('prodi');
  const divProdiManual = document.getElementById('divProdiManual');
  const inputProdiManual = document.getElementById('prodiManual');
  const alertContainer = document.getElementById('alertContainer');

  selectProdi.addEventListener('change', function () {
    if (this.value === 'Lainnya') {
      divProdiManual.classList.remove('d-none');
      inputProdiManual.setAttribute('required', 'true');
      inputProdiManual.focus();
    } else {
      divProdiManual.classList.add('d-none');
      inputProdiManual.removeAttribute('required');
      inputProdiManual.value = '';
    }
  });

  formPendaftaran.addEventListener('submit', function (e) {
    e.preventDefault();

    const nama = document.getElementById('namaLengkap').value.trim();
    const nomorInduk = document.getElementById('nomorInduk').value.trim();
    const kategori = document.getElementById('jenisAnggota').value;
    let prodi = selectProdi.value;
    const email = document.getElementById('email').value.trim();
    const telepon = document.getElementById('telepon').value.trim();
    const alamat = document.getElementById('alamat').value.trim();

    if (prodi === 'Lainnya') {
      prodi = inputProdiManual.value.trim();
    }

    if (!nama || !nomorInduk || !kategori || !prodi || !email || !telepon || !alamat) {
      alertContainer.innerHTML = `
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <i class="fa-solid fa-triangle-exclamation me-2"></i>
          Harap isi seluruh kolom yang bertanda bintang (*) dengan lengkap!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    if (!isValidEmail(email)) {
      alertContainer.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fa-solid fa-circle-xmark me-2"></i>
          Format alamat email tidak valid! Contoh format yang benar: <strong>nama@domain.com</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
      document.getElementById('email').focus();
      return;
    }

    const members = getMembers();
    const isDuplicate = members.some(
      (m) => m.nomorInduk.trim().toLowerCase() === nomorInduk.toLowerCase()
    );

    if (isDuplicate) {
      alertContainer.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fa-solid fa-circle-xmark me-2"></i>
          NRP / NIP <strong>${escapeHtml(nomorInduk)}</strong> sudah terdaftar dalam sistem! Harap gunakan nomor induk Anda yang sah.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
      document.getElementById('nomorInduk').focus();
      return;
    }

    const newMember = { nama, nomorInduk, prodi, kategori, email, telepon, alamat };
    members.push(newMember);
    saveMembers(members);
    updateLandingStats();

    alertContainer.innerHTML = `
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>
        Pendaftaran berhasil! Selamat datang di LibraryReg, <strong>${escapeHtml(nama)}</strong>. Data keanggotaan Anda telah disimpan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;

    formPendaftaran.reset();
    divProdiManual.classList.add('d-none');
    inputProdiManual.removeAttribute('required');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

//Login petugas
const formLogin = document.getElementById('formLogin');
if (formLogin) {
  formLogin.addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const loginAlert = document.getElementById('loginAlert');

    if (username.toLowerCase() === 'admin' && (password === '123' || password === 'admin')) {
      window.location.href = 'pengelolaan.html';
    } else {
      loginAlert.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fa-solid fa-circle-xmark me-2"></i>
          Kredensial login tidak valid! Gunakan ID: <strong>admin</strong> dan Sandi: <strong>123</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
    }
  });
}

//Kelola
const tableBody = document.getElementById('tableBody');
if (tableBody) {
  const searchInput = document.getElementById('searchInput');
  const emptyMessage = document.getElementById('emptyMessage');
  const manageAlert = document.getElementById('manageAlert');

  function updateStatistics(members) {
    const totalEl = document.getElementById('statTotal');
    const mhsEl = document.getElementById('statMahasiswa');
    const dosenEl = document.getElementById('statDosen');
    const tendikEl = document.getElementById('statTendik');

    if (totalEl) totalEl.innerText = members.length;
    if (mhsEl) mhsEl.innerText = members.filter((m) => m.kategori === 'Mahasiswa').length;
    if (dosenEl) dosenEl.innerText = members.filter((m) => m.kategori === 'Dosen').length;
    if (tendikEl) tendikEl.innerText = members.filter((m) => m.kategori === 'Tenaga Kependidikan').length;
  }

  function renderTable(filterQuery = '') {
    const members = getMembers();
    updateStatistics(members);
    tableBody.innerHTML = '';

    const query = filterQuery.trim().toLowerCase();
    const filteredMembers = members.filter(
      (m) =>
        m.nama.toLowerCase().includes(query) ||
        m.nomorInduk.toLowerCase().includes(query)
    );

    if (filteredMembers.length === 0) {
      emptyMessage.classList.remove('d-none');
    } else {
      emptyMessage.classList.add('d-none');

      filteredMembers.forEach((member, index) => {
        let badgeClass = 'badge-mahasiswa';
        if (member.kategori === 'Dosen') badgeClass = 'badge-dosen';
        if (member.kategori === 'Tenaga Kependidikan') badgeClass = 'badge-tendik';

        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="ps-3 fw-bold text-muted">${index + 1}</td>
          <td>
            <div class="fw-bold text-dark">${escapeHtml(member.nama)}</div>
            <small class="text-muted">
              <i class="fa-solid fa-envelope me-1"></i>${escapeHtml(member.email)} | 
              <i class="fa-solid fa-phone me-1"></i>${escapeHtml(member.telepon)}
            </small>
          </td>
          <td>
            <span class="badge bg-light text-dark border fw-bold font-monospace px-2 py-1">
              ${escapeHtml(member.nomorInduk)}
            </span>
          </td>
          <td>
            <span class="badge ${badgeClass} px-2 py-1">
              ${escapeHtml(member.kategori)}
            </span>
          </td>
          <td>${escapeHtml(member.prodi)}</td>
          <td class="text-center">
            <div class="btn-group" role="group">
              <button class="btn btn-sm btn-outline-secondary" title="Detail Anggota" onclick="detailMember('${escapeHtml(member.nomorInduk)}')">
                <i class="fa-solid fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-outline-its" title="Edit Anggota" onclick="editMember('${escapeHtml(member.nomorInduk)}')">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" title="Hapus Anggota" onclick="deleteMember('${escapeHtml(member.nomorInduk)}')">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
      });
    }
  }

  if (searchInput) {
    searchInput.addEventListener('input', function (e) {
      renderTable(e.target.value);
    });
  }

  renderTable();

  window.deleteMember = function (nrp) {
    const isConfirmed = confirm(
      `Apakah Anda yakin ingin menghapus data anggota dengan NRP/NIP ${nrp}?\nTindakan ini tidak dapat dibatalkan.`
    );

    if (isConfirmed) {
      let members = getMembers();
      const targetMember = members.find((m) => m.nomorInduk === nrp);
      members = members.filter((m) => m.nomorInduk !== nrp);
      saveMembers(members);

      renderTable(searchInput ? searchInput.value : '');

      manageAlert.innerHTML = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fa-solid fa-circle-check me-2"></i>
          Data anggota <strong>${escapeHtml(targetMember ? targetMember.nama : nrp)}</strong> (${escapeHtml(nrp)}) berhasil dihapus.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
    }
  };

  window.detailMember = function (nrp) {
    const members = getMembers();
    const member = members.find((m) => m.nomorInduk === nrp);

    if (member) {
      document.getElementById('detailNrp').innerText = member.nomorInduk;
      document.getElementById('detailNama').innerText = member.nama;
      document.getElementById('detailKategori').innerText = member.kategori;
      document.getElementById('detailProdi').innerText = member.prodi;
      document.getElementById('detailEmail').innerText = member.email;
      document.getElementById('detailTelepon').innerText = member.telepon;
      document.getElementById('detailAlamat').innerText = member.alamat;

      const modalEl = document.getElementById('modalDetail');
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    }
  };

  window.editMember = function (nrp) {
    const members = getMembers();
    const member = members.find((m) => m.nomorInduk === nrp);

    if (member) {
      const editAlert = document.getElementById('editAlert');
      if (editAlert) editAlert.innerHTML = '';

      document.getElementById('editNrp').value = member.nomorInduk;
      document.getElementById('editNama').value = member.nama;
      document.getElementById('editKategori').value = member.kategori;
      document.getElementById('editProdi').value = member.prodi;
      document.getElementById('editEmail').value = member.email;
      document.getElementById('editTelepon').value = member.telepon;
      document.getElementById('editAlamat').value = member.alamat;

      const modalEl = document.getElementById('modalEdit');
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    }
  };

  const formEdit = document.getElementById('formEdit');
  if (formEdit) {
    formEdit.addEventListener('submit', function (e) {
      e.preventDefault();

      const nrp = document.getElementById('editNrp').value;
      const nama = document.getElementById('editNama').value.trim();
      const kategori = document.getElementById('editKategori').value;
      const prodi = document.getElementById('editProdi').value.trim();
      const email = document.getElementById('editEmail').value.trim();
      const telepon = document.getElementById('editTelepon').value.trim();
      const alamat = document.getElementById('editAlamat').value.trim();
      const editAlert = document.getElementById('editAlert');

      if (!nama || !kategori || !prodi || !email || !telepon || !alamat) {
        if (editAlert) {
          editAlert.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
              <i class="fa-solid fa-triangle-exclamation me-2"></i>Semua kolom wajib diisi!
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          `;
        }
        return;
      }

      if (!isValidEmail(email)) {
        if (editAlert) {
          editAlert.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
              <i class="fa-solid fa-circle-xmark me-2"></i>Format email tidak valid! Gunakan format nama@domain.com
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          `;
        }
        document.getElementById('editEmail').focus();
        return;
      }

      const members = getMembers();
      const index = members.findIndex((m) => m.nomorInduk === nrp);

      if (index !== -1) {
        members[index] = {
          ...members[index],
          nama,
          kategori,
          prodi,
          email,
          telepon,
          alamat
        };

        saveMembers(members);
        renderTable(searchInput ? searchInput.value : '');

        const modalEl = document.getElementById('modalEdit');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
          modalInstance.hide();
        }

        manageAlert.innerHTML = `
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            Data anggota <strong>${escapeHtml(nama)}</strong> (${escapeHtml(nrp)}) berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        `;
      }
    });
  }
}

document.addEventListener('DOMContentLoaded', function () {
  updateLandingStats();

  if (document.body.getAttribute('data-bs-spy') === 'scroll' && typeof bootstrap !== 'undefined') {
    bootstrap.ScrollSpy.getOrCreateInstance(document.body);
  }
});
