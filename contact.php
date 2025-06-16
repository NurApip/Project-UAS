<?php
    include('layouts/header.php');
?>

    

    <!-- Contact Section Begin -->
        <div class="container">
            <div class="row align-items-center">
                <!-- Text & Form -->
                <div class="col-lg-6 col-md-6 mb-5 mb-md-0">
                    <div class="contact__text">
                        <div class="section-title mb-4">
                            <h2 style="color: #222;">Kepuasan Anda selalu menjadi prioritas kami</h2>
                            <p style="color: #555;">Untuk pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami menggunakan formulir di bawah ini. Kami akan menghubungi Anda sesegera mungkin.</p>
                        </div>
                        <div class="contact__form">
                            <form action="#" method="POST">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <input type="text" placeholder="Nama Anda" class="form-control" style="padding: 12px; border-radius: 8px; border: 1px solid #ccc;" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="email" placeholder="Email Anda" class="form-control" style="padding: 12px; border-radius: 8px; border: 1px solid #ccc;" required>
                                    </div>
                                    <div class="col-lg-12">
                                        <textarea placeholder="Pesan Anda" class="form-control" rows="5" style="padding: 12px; border-radius: 8px; border: 1px solid #ccc;" required></textarea>
                                    </div>
                                    <div class="col-lg-12 text-end mt-3">
                                        <button type="submit" class="site-btn" style="background-color: #1a1a2e; color: #fff; padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600;">Kirim Pesan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Image -->
                <div class="col-lg-6 col-md-6 text-center">
                    <img src="img/kepuasan.png" alt="Contact Illustration" style="max-width: 100%; border-radius: 12px; 0 0 15px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    <!-- Contact Section End -->

<?php
    include('layouts/footer.php');
?>