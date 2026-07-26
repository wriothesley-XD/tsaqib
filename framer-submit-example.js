// Ditaruh di komponen Embed Framer, dijalankan saat tombol submit
// form pendaftaran diklik.
//
// GANTI apiUrl sesuai situasi (lihat catatan di README).

async function submitPendaftaran(formData) {
  const apiUrl = 'http://127.0.0.1:8000/api/submit'; // ganti sesuai situasi

  const payload = {
    komunitas: formData.komunitasSlug,       // contoh: "tahfidz"
    role: formData.roleSlug,                 // contoh: "hafidz"
    nama_lengkap: formData.namaLengkap,
    nama_panggilan: formData.namaPanggilan,
    instagram: formData.instagram,
    alasan: formData.alasan,
  };

  try {
    const response = await fetch(apiUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const result = await response.json();

    if (!response.ok || !result.success) {
      // tampilkan pesan error dari Laravel (result.message atau result.errors)
      console.error('Gagal submit:', result);
      return { success: false, message: result.message || 'Terjadi kesalahan.' };
    }

    // sukses -> di sini biasanya trigger perpindahan ke screen "Thank You"
    return { success: true };

  } catch (err) {
    console.error('Network error:', err);
    return { success: false, message: 'Tidak bisa terhubung ke server.' };
  }
}
