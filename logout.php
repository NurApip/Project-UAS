<?php
session_start();

// Memeriksa apakah parameter logout ada di URL
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    
    // Memeriksa apakah sesi admin memang ada sebelum menghapusnya
    if (isset($_SESSION['admin_logged_in'])) {
        
        // Menghapus semua data sesi yang berkaitan dengan admin
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['admin_logged_in']);
        
        // Opsi lain: session_destroy(); // Ini akan menghapus SEMUA data session.
        
        // ================== PERBAIKAN UTAMA DI SINI ==================
        // Mengarahkan kembali ke halaman login admin, bukan ke index user.
        header('location: login.php?logout_success=Anda berhasil logout');
        exit;
        // ================== AKHIR PERBAIKAN ==================
    }
} else {
    // Jika file ini diakses tanpa parameter logout, kembalikan ke dashboard admin
    header('location: index.php');
    exit;
}
?>
