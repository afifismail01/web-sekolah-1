# Website Pendaftaran Siswa Baru (PSB) Sekolah

Project ini merupakan sistem **pendaftaran siswa baru berbasis web** yang dikembangkan menggunakan **Laravel** sebagai backend dan **TailwindCSS** untuk tampilan antarmuka. Sistem ini memiliki fitur pengelolaan pendaftaran siswa, upload berkas, notifikasi WhatsApp, serta integrasi pembayaran menggunakan API Edupay.

---

## Teknologu yang Digunakan

### Backend
- **Laravel** (Blade & Filament Admin Panel)
- **MySQL** (Database)
- **Edupay API** (Integrasi pembayaran)
- **Fontee API** (Kirim pesan WhatsApp)

### Frontend
- **HTML, CSS**
- **TailwindCSS**
- **Alpine.js**
- **Lucide Icons**

---

## Fitur Utama
- Formulir pendaftaran online siswa baru
- Upload berkas persyaratan (Berdasarkan jalur pendaftaran)
- Manajemen data siswa melalui panel admin (Filament)
- Integrasi notifikasi WhatsApp via Fonnte API
- Integrasi pembayaran dengan Edupay API
- Status pembayaran otomatis diperbarui (production mode)
- Laporan data pendaftar dan status kelulusan

---
## Instalasi & Setup Project
1. Clone Repository
   ```bash
   git clone https://github.com/afifismail01/web-sekolah-1.git
   cd web-sekolah-1
2. Install Dependensi
   ```bash
   composer install
   npm install
3. Copy File .env
   ```bash
   cp .env.example .env
4. Atur Konfigurasi .env (isi variable sesuai kebutuhan)*
   *Edupay **tidak menyediakan sandbox**, sehingga proses pembayaran hanya dapat diuji secara manual dengan menginput data ke database selama tahap pengembangan
5. Generat App Key
   ```bash
   php artisan key:generate
6. Jalankan Migrasi dan Seeder 
   ```bash
   php artisan migrate --seed
7. Jalankan Server
   ```bash
   php artisan serve

---

## Catatan Pengembangan
- Proyek ini masih dalam tahap pengembangan
- Modul pembayaran saat ini menggunakan *manual input* di tahap development
- Integrasi penuh dengan Edupay hanya berlaku untuk mode produksi
- Fitur notifikasi WhatsApp memerlukan API aktif dari Fonnte

---

## Lisensi
Project ini bersifat open source untuk keperluan pembelajaran dan pengembangan pribadi.

---

*"Code with purpose, design with clarity."*
