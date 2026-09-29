erDiagram
%% =====================================
%% MASTER ORGANISASI & PENGGUNA
%% =====================================
ORGANISASI ||--o{ ORGANISASI : hierarki
ORGANISASI ||--o{ PENGGUNA : menaungi

    JABATAN ||--o{ PENGGUNA_JABATAN : memiliki
    PENGGUNA ||--o{ PENGGUNA_JABATAN : menjabat

    %% =====================================
    %% MASTER SURAT
    %% =====================================
    JENIS_SURAT ||--o{ SURAT : bertipe
    ORGANISASI ||--o{ SURAT : pengirim
    PENGGUNA ||--o{ SURAT : pembuat

    ORGANISASI ||--o{ PENOMORAN_SURAT : aturan
    JENIS_SURAT ||--o{ PENOMORAN_SURAT : kategori

    %% =====================================
    %% DISTRIBUSI & WORKFLOW SURAT
    %% =====================================
    SURAT ||--o{ PENERIMA_SURAT : distribusi
    SURAT ||--o{ DISPOSISI : instruksi
    SURAT ||--o{ PENGESAHAN_SURAT : validasi
    SURAT ||--o{ LAMPIRAN_SURAT : lampiran

    %% =====================================
    %% AUDIT
    %% =====================================
    PENGGUNA ||--o{ LOG_AKTIVITAS : aktivitas

    %% =====================================
    %% DEFINISI ENTITAS (RINGKAS)
    %% =====================================
    ORGANISASI {
        int id PK
        int id_induk FK
        string nama_organisasi
        string jenis_organisasi
        string kode_organisasi
    }

    PENGGUNA {
        int id PK
        string nama_lengkap
        string nama_pengguna
    }

    JABATAN {
        int id PK
        string nama_jabatan
        int tingkat
    }

    PENGGUNA_JABATAN {
        int id_pengguna FK
        int id_jabatan FK
    }

    JENIS_SURAT {
        int id PK
        string nama_jenis
        string kode_jenis
    }

    SURAT {
        int id PK
        string nomor_surat
        string perihal
        string status
    }

    PENOMORAN_SURAT {
        int id PK
        int tahun
        int nomor_terakhir
    }

    PENERIMA_SURAT {
        int id PK
        string jenis_penerima
    }

    DISPOSISI {
        int id PK
        string status
    }

    PENGESAHAN_SURAT {
        int id PK
        string aksi
    }

    LAMPIRAN_SURAT {
        int id PK
        string nama_file
    }

    LOG_AKTIVITAS {
        int id PK
        string aksi
    }
