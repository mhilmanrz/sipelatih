<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sipelatih', function () {
    return view('sipelatih');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/pengajuan-diklat', function () {
    return view('usulan/pengajuan/index');
});

Route::get('/pengajuan-diklat/create', function () {
    return view('usulan/pengajuan/create');
});

Route::get('/pengajuan-diklat/edit', function () {
    return view('usulan/pengajuan/edit');
});

Route::get('/monitoring-pengajuan', function () {
    return view('usulan/monitoring/index');
});
        Route::get('/monitoring-pengajuan/detail/kegiatan', function () {
            return view('usulan/monitoring/detail/kegiatan');
        });

        Route::get('/monitoring-pengajuan/detail/sasaran-profesi', function () {
            return view('usulan/monitoring/detail/sasaran-profesi');
        });

        Route::get('/monitoring-pengajuan/detail/kak', function () {
            return view('usulan/monitoring/detail/kak');
        });

        Route::get('/monitoring-pengajuan/detail/narasumber', function () {
            return view('usulan/monitoring/detail/narasumber');
        });

        Route::get('/monitoring-pengajuan/detail/peserta', function () {
            return view('usulan/monitoring/detail/peserta');
        });

        Route::get('/monitoring-pengajuan/detail/pengiriman', function () {
            return view('usulan/monitoring/detail/pengiriman');
        });

        Route::get('/monitoring-pengajuan/detail/penilaian', function () {
            return view('usulan/monitoring/detail/penilaian');
        });

        Route::get('/monitoring-pengajuan/detail/sertifikat', function () {
            return view('usulan/monitoring/detail/sertifikat');
        });
