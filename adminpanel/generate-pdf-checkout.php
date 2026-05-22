<?php
    session_start();
    require "../koneksi.php";

    require "../vendor/autoload.php"; // Dompdf via Composer

    use Dompdf\Dompdf;
    use Dompdf\Options;
    // Include PHPMailer via Composer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Cek user login dan keranjang
    if (!isset($_SESSION['user_id']) || empty($_SESSION['keranjang'])) {
        echo "<script>alert('Silakan login dan isi keranjang terlebih dahulu.'); window.location='produk.php';</script>";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $query_user = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
    $data_user = mysqli_fetch_assoc($query_user);

    $keranjang = $_SESSION['keranjang'];
    $tanggal = date("Y-m-d");

    // Ambil data dari form checkout (POST)
    if (!isset($_POST['nama'], $_POST['paypal_id'], $_POST['alamat'], $_POST['bank'], $_POST['contact_no'], $_POST['metode'])) {
        echo "<script>alert('Data checkout tidak lengkap.'); window.location='produk.php';</script>";
        exit;
    }

    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $paypal_id = mysqli_real_escape_string($con, $_POST['paypal_id']);
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
    $bank = mysqli_real_escape_string($con, $_POST['bank']);
    $contact_no = mysqli_real_escape_string($con, $_POST['contact_no']);
    $metode = mysqli_real_escape_string($con, $_POST['metode']);


    $total = 0;
    $order_items = [];

    foreach ($keranjang as $id => $jumlah) {
        $produkQuery = mysqli_query($con, "SELECT * FROM produk WHERE id = '$id'");
        $produk = mysqli_fetch_assoc($produkQuery);
        if (!$produk) continue;

        $subtotal = $produk['harga'] * $jumlah;
        $total += $subtotal;

        $order_items[] = [
            'produk_id' => $produk['id'],
            'nama' => $produk['nama'],
            'jumlah' => $jumlah,
            'harga' => $produk['harga'],
            'subtotal' => $subtotal
        ];
    }

    // Simpan ke tabel orders
    mysqli_query($con, "INSERT INTO orders (user_id, nama, paypal_id, alamat, bank, contact_no, metode, tanggal, total, status_pengiriman) 
        VALUES ('$user_id', '$nama', '$paypal_id', '$alamat', '$bank', '$contact_no', '$metode', '$tanggal', '$total', 'Belum Dikirim')");
    $order_id = mysqli_insert_id($con);

    // Simpan ke tabel order_items
    foreach ($order_items as $item) {
        mysqli_query($con, "INSERT INTO order_items (order_id, produk_id, jumlah, harga, subtotal)
            VALUES ('$order_id', '{$item['produk_id']}', '{$item['jumlah']}', '{$item['harga']}', '{$item['subtotal']}')");
    }

    $user_id = $_SESSION['user_id']; // atau ambil dari DB
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $nomor_kontak = $_POST['contact_no'];
    $tanggal = date('Y-m-d');
    $paypal_id = $_POST['paypal_id'];
    $nama_bank = $_POST['bank']; // dari dropdown
    $cara_bayar = $_POST['metode']; // dari dropdown

    // Generate HTML for PDF
    $html = '
    
    <style>
        @page { margin: 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .column { width: 48%; float: left; margin-bottom: 15px; }
        .column-right { float: right; }
        .field { margin-bottom: 6px; }
        .section-title {text-align:center;margin: top 40px;}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f2f2f2; }
        th, td { padding: 8px; }
        td:nth-child(3), td:nth-child(4), td:nth-child(5) {
            text-align: right;
        }
        .total-row { background-color: #e9ecef; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; clear: both; }

        
    </style>

    <div class="header">
        <h2>Laporan Belanja ' . htmlspecialchars($nama) . '</h2>
    </div>

    <div class="row">
        <div class="column">
            <div class="field"><strong>User ID:</strong> ' . htmlspecialchars($user_id) . '</div>
            <div class="field"><strong>Nama:</strong> ' . htmlspecialchars($nama) . '</div>
            <div class="field"><strong>Alamat:</strong> ' . (htmlspecialchars($alamat)) . '</div>
            <div class="field"><strong>Nomor Kontak:</strong> ' . htmlspecialchars($nomor_kontak) . '</div>
        </div>
        <div class="column column-right">
            <div class="field"><strong>Tanggal:</strong> ' . date("d-m-Y", strtotime($tanggal)) . '</div>
            <div class="field"><strong>ID Paypal:</strong> ' . htmlspecialchars($paypal_id) . '</div>
            <div class="field"><strong>Nama Bank:</strong> ' . htmlspecialchars($nama_bank) . '</div>
            <div class="field"><strong>Cara Bayar:</strong> ' . htmlspecialchars($cara_bayar) . '</div>
        </div>
    </div>

    <div style="clear: both;"></div>
    <h3 style="text-align: center; margin-top: 40px;">Detail Belanja</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk (ID)</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>';

    $no = 1;
    foreach ($order_items as $item) {
        $html .= "<tr>
            <td>{$no}</td>
            <td>" . htmlspecialchars($item['nama']) . " (" . $item['produk_id'] . ")</td>
            <td>" . $item['jumlah'] . "</td>
            <td>Rp " . number_format($item['harga'], 0, ',', '.') . "</td>
            <td>Rp " . number_format($item['subtotal'], 0, ',', '.') . "</td>
        </tr>";
        $no++;
    }

    $html .= '
            <tr class="total-row">
                <td colspan="4" align="right">Total (termasuk pajak):</td>
                <td>Rp ' . number_format($total, 0, ',', '.') . '</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>TANDA TANGAN TOKO</strong></p>
        <p>HEALTH CARE FROM TSABI</p>
    </div>
    ';


    // Generate PDF
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Simpan file PDF sementara
    if (!file_exists('../laporan')) {
        mkdir('../laporan', 0777, true); // Buat folder jika belum ada
    }

    $output = $dompdf->output();
    $pdfPath = "../laporan/laporan-checkout-" . time() . ".pdf";
    file_put_contents($pdfPath, $output);
    

    // if (file_exists($pdfPath)) {
    //     // unlink($pdfPath);
    // }


    // Kirim email (gunakan PHPMailer atau fungsi mail biasa)
    $to = $data_user['email'];
    $subject = "Laporan Pembelian Anda - TSABI";
    $body = "Halo " . $data_user['username'] . ",\n\nTerima kasih atas pembelian Anda. Terlampir laporan pembelian dalam format PDF.\n\nSalam,\nTSABI HEALTH CARE";


    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tsabitarp28@gmail.com'; // GANTI
        $mail->Password = 'frjhhmceovazcmcb';    // GANTI DENGAN APP PASSWORD GMAIL
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set debug level
        $mail->SMTPDebug = 0; 
        $mail->Debugoutput = 'html';


        $mail->setFrom('tsabitarp28@gmail.com', 'TSABI HEALTH CARE');
        $mail->addAddress($to, $data_user['username']);

        if (file_exists($pdfPath)) {
            $mail->addAttachment($pdfPath);
        } else {
            throw new Exception("File PDF tidak ditemukan.");
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <p>Halo <strong>{$data_user['username']}</strong>,</p>
            <p>Terima kasih atas pembelian Anda di <strong>TSABI HEALTH CARE</strong>.</p>
            <p>Berikut terlampir invoice pembelian Anda dalam format PDF.</p>
            <p>Salam hangat,<br>TSABI HEALTH CARE</p>
        ";
        $mail->AltBody = "Halo {$data_user['username']}, terima kasih atas pembelian Anda. Invoice terlampir.";

        $mail->send();

    } catch (Exception $e) {
        echo "<script>alert('Email gagal dikirim. Error: {$mail->ErrorInfo}');</script>";
    }

    // // KALO nggak DOWNLOAD OTOMATIS
    // Kosongkan keranjang
    unset($_SESSION['keranjang']);

    // Redirect ke halaman sukses
    echo "<script>
        alert('Pesanan berhasil dikonfirmasi dan laporan dikirim ke email Anda.');
        window.location.href = '$pdfPath';
    </script>";
    exit;

    // // KALO MAU DOWNLOAD OTOMATIS
    // // Kosongkan keranjang
    // unset($_SESSION['keranjang']);

    // // Force download PDF
    // header('Content-Type: application/pdf');
    // header('Content-Disposition: attachment; filename="laporan-checkout.pdf"');
    // echo $output;
    // exit;


    

?>

