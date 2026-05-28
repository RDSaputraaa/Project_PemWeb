<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Reservasi - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="payment-page" style="background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh;">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Gelora Sport</div>
        <div class="nav-menu">
            <span style="color:#007c60; font-weight:600;">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
            <a href="index.php?page=beranda" class="login-link"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?page=logout" class="register-btn">Keluar</a>
        </div>
    </nav>

    <div style="max-width: 650px; margin: 40px auto; padding: 0 20px;">
        
        <?php if (!empty($error)): ?>
            <div style="padding:14px 20px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:10px;margin-bottom:20px;font-weight:500;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= e($error) ?>
            </div>
        <?php endif; ?>

        <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eaeaea; overflow: hidden; margin-bottom: 20px;">
            
            <!-- Header Section -->
            <div style="background: linear-gradient(135deg, #e67e22, #d35400); padding: 30px; color: white;">
                <h2 style="margin: 0; font-size: 1.4rem; font-weight: 700;"><i class="fa-solid fa-clock"></i> Selesaikan Pembayaran</h2>
                <p style="margin: 5px 0 0; opacity: 0.9; font-size: 0.9rem;">Reservasi Anda telah dicatat dengan Kode: <strong><?= e($res['kode_reservasi']) ?></strong>. Harap lakukan pembayaran transfer.</p>
            </div>

            <!-- Payment Instructions -->
            <div style="padding: 30px; border-bottom: 1px solid #eaeaea;">
                <h3 style="margin-top:0; margin-bottom: 15px; color: #222; font-size: 1.1rem; padding-bottom: 5px;">Langkah Transfer Pembayaran</h3>
                
                <div style="background: #fff9f4; border: 1px solid #ffe8d6; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                    <div style="font-size: 0.9rem; color: #666; margin-bottom: 5px;">Jumlah yang harus ditransfer:</div>
                    <div style="font-size: 1.6rem; font-weight: 700; color: #d35400;"><?= formatRupiah((int)$res['total_harga']) ?></div>
                    <div style="font-size: 0.75rem; color: #e67e22; margin-top: 3px; font-weight: 500;"><i class="fa-solid fa-circle-info"></i> Transfer harus tepat sesuai jumlah di atas.</div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 15px; background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 8px;">
                        <div style="font-size: 24px; color: #007c60; width: 40px; text-align: center;"><i class="fa-solid fa-building-columns"></i></div>
                        <div>
                            <div style="font-size: 0.8rem; color: #888;">Bank Tujuan</div>
                            <strong style="color:#222; font-size: 0.95rem;">BCA (Bank Central Asia)</strong>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 15px; background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 8px;">
                        <div style="font-size: 24px; color: #007c60; width: 40px; text-align: center;"><i class="fa-solid fa-credit-card"></i></div>
                        <div style="flex-grow: 1;">
                            <div style="font-size: 0.8rem; color: #888;">Nomor Virtual Account</div>
                            <strong style="color:#222; font-size: 1.1rem; letter-spacing: 1px;" id="vaNumber">8077 0812 3456 7890</strong>
                        </div>
                        <button type="button" onclick="copyVA()" style="background: none; border: 1px solid #007c60; color: #007c60; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;"
                                onmouseover="this.style.background='#e8f5e9'" onmouseout="this.style.background='none'">Salin</button>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <form method="POST" action="index.php?page=payment&id=<?= $res['id'] ?>" enctype="multipart/form-data" style="padding: 30px;">
                <h3 style="margin-top:0; margin-bottom: 15px; color: #222; font-size: 1.1rem; padding-bottom: 5px;">Unggah Bukti Transfer</h3>
                
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px;">Pilih File Bukti Pembayaran (JPG/PNG/GIF, maks 2MB)</label>
                    <div style="border: 2px dashed #ddd; padding: 25px; border-radius: 10px; text-align: center; background: #fafafa; cursor: pointer; position: relative;" id="dropzone">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 32px; color: #aaa; margin-bottom: 10px; display: block;"></i>
                        <span id="file-label" style="font-size: 0.9rem; color: #666;">Klik atau seret file ke sini untuk memilih foto</span>
                        <input type="file" name="bukti_bayar" accept="image/*" required style="position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer;" onchange="previewFile(this)">
                    </div>
                    <div id="imagePreviewContainer" style="display: none; margin-top: 15px; text-align: center;">
                        <img id="imagePreview" src="#" alt="Pratinjau Bukti" style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid #ddd; padding: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                    <a href="index.php?msg=Booking+disimpan.+Silakan+lakukan+pembayaran+nanti." style="text-decoration:none; padding: 12px 20px; border: 1px solid #ddd; border-radius: 8px; color: #666; font-weight: 600; font-size: 0.9rem; transition: background 0.2s;"
                       onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'">Bayar Nanti (Ke Beranda)</a>
                    <button type="submit" style="padding: 12px 30px; background: #007c60; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9rem; transition: background 0.2s;"
                            onmouseover="this.style.background='#005b48'" onmouseout="this.style.background='#007c60'">Kirim Bukti Pembayaran</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function copyVA() {
        const va = document.getElementById("vaNumber").innerText.replace(/\s/g, '');
        navigator.clipboard.writeText(va).then(() => {
            alert("Nomor Virtual Account disalin: " + va);
        }).catch(err => {
            alert("Gagal menyalin. Silakan salin manual.");
        });
    }

    function previewFile(input) {
        const file = input.files[0];
        const label = document.getElementById("file-label");
        const preview = document.getElementById("imagePreview");
        const container = document.getElementById("imagePreviewContainer");
        
        if (file) {
            label.innerText = file.name + " (" + (file.size/1024/1024).toFixed(2) + " MB)";
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = "block";
            }
            reader.readAsDataURL(file);
        } else {
            label.innerText = "Klik atau seret file ke sini untuk memilih foto";
            container.style.display = "none";
        }
    }
    </script>
</body>
</html>
