<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Tiket Travel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Pemesanan Tiket Travel</h1>

        <?php
        include 'koneksi.php'; // Sisipkan file koneksi database

        // --- LOGIKA MENAMBAH DATA (CREATE) ---
        if (isset($_POST['submit_add'])) {
            $tujuan  = $_POST['tujuan'];
            $tanggal = $_POST['tanggal'];
            $jumlah  = $_POST['jumlah'];
            $harga   = $_POST['harga'];

            $sql_add = "INSERT INTO tiket (tujuan, tanggal, jumlah, harga) VALUES ('$tujuan', '$tanggal', '$jumlah', '$harga')";
            if (mysqli_query($koneksi, $sql_add)) {
                echo "<p style='color: green;'>Tiket berhasil ditambahkan!</p>";
            } else {
                echo "<p style='color: red;'>Error: " . mysqli_error($koneksi) . "</p>";
            }
        }

        // --- LOGIKA MENGHAPUS DATA (DELETE) ---
        if (isset($_GET['op']) && $_GET['op'] == 'delete') {
            $id = $_GET['id'];
            $sql_delete = "DELETE FROM tiket WHERE id = '$id'";
            if (mysqli_query($koneksi, $sql_delete)) {
                echo "<p style='color: green;'>Tiket berhasil dihapus!</p>";
            } else {
                echo "<p style='color: red;'>Error: " . mysqli_error($koneksi) . "</p>";
            }
        }

        // --- LOGIKA MENGEDIT DATA (UPDATE) - Bagian Ambil Data untuk Form ---
        $edit_tujuan = '';
        $edit_tanggal = '';
        $edit_jumlah = '';
        $edit_harga = '';

        if (isset($_GET['op']) && $_GET['op'] == 'edit') {
            $id_edit_get = $_GET['id'];
            $sql_get_edit = "SELECT * FROM tiket WHERE id = '$id_edit_get'";
            $query_get_edit = mysqli_query($koneksi, $sql_get_edit);
            $data_edit = mysqli_fetch_assoc($query_get_edit);

            if ($data_edit) { // Pastikan data ditemukan
                $edit_tujuan = $data_edit['tujuan'];
                $edit_tanggal = $data_edit['tanggal'];
                $edit_jumlah = $data_edit['jumlah'];
                $edit_harga = $data_edit['harga'];
            } else {
                echo "<p style='color: red;'>Data tiket tidak ditemukan untuk diedit!</p>";
                // Redirect untuk membersihkan URL jika ID tidak valid
                echo "<script>window.location.href='index.php';</script>";
            }
        }

        // --- LOGIKA MENGEDIT DATA (UPDATE) - Bagian Update Data ke Database ---
        if (isset($_POST['submit_edit'])) {
            $id_update = $_POST['id_edit'];
            $tujuan_update = $_POST['tujuan'];
            $tanggal_update = $_POST['tanggal'];
            $jumlah_update = $_POST['jumlah'];
            $harga_update = $_POST['harga'];

            $sql_update = "UPDATE tiket SET tujuan='$tujuan_update', tanggal='$tanggal_update', jumlah='$jumlah_update', harga='$harga_update' WHERE id='$id_update'";
            if (mysqli_query($koneksi, $sql_update)) {
                echo "<p style='color: green;'>Tiket berhasil diperbarui!</p>";
                // Redirect untuk membersihkan URL dan form
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "<p style='color: red;'>Error: " . mysqli_error($koneksi) . "</p>";
            }
        }
        ?>

        <h2>Tambah Tiket Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="tujuan">Tujuan:</label>
                <input type="text" id="tujuan" name="tujuan" required
                       value="<?php echo htmlspecialchars($edit_tujuan); ?>">
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal Keberangkatan:</label>
                <input type="date" id="tanggal" name="tanggal" required
                       value="<?php echo htmlspecialchars($edit_tanggal); ?>">
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah Tiket:</label>
                <input type="number" id="jumlah" name="jumlah" min="1" required
                       value="<?php echo htmlspecialchars($edit_jumlah); ?>">
            </div>
            <div class="form-group">
                <label for="harga">Harga Per Tiket:</label>
                <input type="number" id="harga" name="harga" step="0.01" min="0" required
                       value="<?php echo htmlspecialchars($edit_harga); ?>">
            </div>
            <?php if (isset($_GET['op']) && $_GET['op'] == 'edit') { ?>
                <input type="hidden" name="id_edit" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                <button type="submit" name="submit_edit" class="btn btn-edit">Update Tiket</button>
                <a href="index.php" class="btn btn-delete">Batal Edit</a>
            <?php } else { ?>
                <button type="submit" name="submit_add" class="btn">Tambah Tiket</button>
            <?php } ?>
        </form>

        <hr>

        <h2>Daftar Tiket Tersedia</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // --- LOGIKA MENAMPILKAN DATA (READ) ---
                $sql_read = "SELECT * FROM tiket ORDER BY id DESC";
                $result = mysqli_query($koneksi, $sql_read);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tujuan']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['jumlah']) . "</td>";
                        echo "<td>" . number_format($row['harga'], 2, ',', '.') . "</td>";
                        echo "<td>
                                <a href='index.php?op=edit&id=" . htmlspecialchars($row['id']) . "' class='btn btn-edit'>Edit</a>
                                <a href='index.php?op=delete&id=" . htmlspecialchars($row['id']) . "' onclick='return confirmDelete()' class='btn btn-delete'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data tiket.</td></tr>";
                }

                // Tutup koneksi database
                mysqli_close($koneksi);
                ?>
            </tbody>
        </table>
    </div>

    <script src="js/script.js"></script>
</body>
</html>