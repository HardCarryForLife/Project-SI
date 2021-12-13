<?php
    session_start();
    //koneksi database
    $conn = mysqli_connect("localhost","root","","stockbarang");

    //menambah barang baru
    if(isset($_POST['addnewbarang'])){
        $namabarang = $_POST['namabarang'];
        $deskripsi = $_POST['deskripsi'];
        $stock = $_POST['stock'];

        $addtotable = mysqli_query($conn,"INSERT into stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
        if($addtotable){
            header('location:index.php');
        }
        else {
            echo 'Gagal';
            header('location:index.php');
        }
    }

    //menambah barang masuk
    if(isset($_POST['barangmasuk'])){
        $barangnya = $_POST['barangnya'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty'];

        $cekstocksekarang = mysqli_query($conn,"SELECT * from stock where idbarang='$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);  

        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

        $addtomasuk = mysqli_query($conn,"INSERT into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima', '$qty')");
        $updatestockmasuk = mysqli_query($conn,"UPDATE stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
        if($addtomasuk&&$updatestockmasuk){
            header('location:masuk.php');
        }
        else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    }

    //menambah barang keluar
    if(isset($_POST['addbarangkeluar'])){
        $barangnya = $_POST['barangnya'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty'];

        $cekstocksekarang = mysqli_query($conn,"SELECT * from stock where idbarang='$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);  

        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;

        $addtokeluar = mysqli_query($conn,"INSERT into keluar (idbarang, penerima, qty) values('$barangnya','$penerima', '$qty')");
        $updatestockmasuk = mysqli_query($conn,"UPDATE stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
        if($addtokeluar&&$updatestockmasuk){
            header('location:keluar.php');
        }
        else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }

    //Mengedit barang.
    if(isset($_POST['updatebarang'])){
        $idb = $_POST['idb'];
        $namabarang = $_POST['namabarang'];
        $deskripsi = $_POST['deskripsi'];

        $update = mysqli_query($conn,"UPDATE stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang = '$idb' ");
        if($update){
            header("location:index.php");
        }else {
            echo "Gagal";
            header("location:index.php");
        }
    }
    //hapus barang
    if (isset($_POST['hapusbarang'])) {
        $idb = $_POST['idb'];
        $hapus = mysqli_query($conn,"DELETE from stock where idbarang = '$idb'");
        if($hapus){
            header("location:index.php");
        }else {
            echo "Gagal";
            header("location:index.php");
        }
    }

    //edit barang masuk 
    if (isset($_POST['updatebarangmasuk'])) {
        $idb = $_POST['idb'];
        $idm = $_POST['idm'];
        $deskripsi = $_POST['keterangan'];
        $qty = $_POST['qty'];

        $lihatstock= mysqli_query($conn,"SELECT * from stock where idbarang='$idb'");
        $stocknya = mysqli_fetch_array($lihatstock);
        $stockskrng = $stocknya['stock'];
        
        $qtyskrng = mysqli_query($conn, "SELECT * from masuk where idbarang='$idb'");
        $qtynya = mysqli_fetch_array($qtyskrng);
        $qtyskrng= $qtynya['qty'];

        if ($qty >= $qtyskrng) {
            $selisih = $qty-$qtyskrng;
            $hitung = $stockskrng+$selisih;
            $kurangistocknya = mysqli_query($conn,"UPDATE stock set stock='$hitung' where idbarang='$idb'");
            $updatenya = mysqli_query($conn,"UPDATE masuk set qty='$qty', keterangan='$deskripsi' where idbarang='$idb'");
                if ($kurangistocknya&&$updatenya) {
                    header("location:masuk.php");
                }else {
                    echo "Gagal";
                    header("location:masuk.php");
                }
        }else if ($qty<$qtyskrng) {
            $selisih = $qtyskrng-$qty;
            $hitung = $stockskrng-$selisih;
            $kurangistocknya = mysqli_query($conn,"UPDATE stock set stock='$hitung' where idbarang='$idb'");
            $updatenya = mysqli_query($conn,"UPDATE masuk set qty='$qty', keterangan='$deskripsi' where idbarang='$idb'");
                if ($kurangistocknya&&$updatenya) {
                    header("location:masuk.php");
                }else {
                    echo "Gagal";
                    header("location:masuk.php");
                }
        }
    }

    //hapus barang masuk
    if (isset($_POST['hapusbarangmasuk'])) {
        $idb = $_POST['idb'];
        $idm = $_POST['idm'];

        $getdatastock= mysqli_query($conn,"SELECT * from stock where idbarang='$idb'");
        $data = mysqli_fetch_array($getdatastock);
        $stok = $data['stock'];

        $qtyskrng = mysqli_query($conn, "SELECT * from masuk where idmasuk='$idm'");
        $qtynya = mysqli_fetch_array($qtyskrng);
        $qtyskrng= $qtynya['qty'];

        $hitung = $stok - $qtyskrng;

        $update = mysqli_query($conn,"UPDATE stock set stock='$hitung' where idbarang='$idb'");
        $hapusdata = mysqli_query($conn,"DELETE from masuk where idmasuk='$idm'");
        if($update&&$hapusdata){
            header('location:masuk.php');
        }else {
            header('location:masuk.php');
        }
    }

    //data keluar
    if (isset($_POST['updatebarangkeluar'])) {
        $idb = $_POST['idb'];
        $idk = $_POST['idk'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty'];

        $lihatstock= mysqli_query($conn,"SELECT * from stock where idbarang='$idb' ");
        $stocknya = mysqli_fetch_array($lihatstock);
        $stockskrng = $stocknya['stock'];
        
        $qtyskrng = mysqli_query($conn, "SELECT * from keluar where idkeluar='$idk'");
        $qtynya = mysqli_fetch_array($qtyskrng);
        $qtyskrng= $qtynya['qty'];

        if ($qty >= $qtyskrng) {
            $selisih = $qty - $qtyskrng;
            $hitung = $stockskrng - $selisih;
            $kurangistocknya = mysqli_query($conn,"UPDATE stock set stock='$hitung' where idbarang='$idb'");
            $updatenya = mysqli_query($conn,"UPDATE keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
                if ($kurangistocknya&&$updatenya) {
                    header("location:keluar.php");
                }else {
                    echo "Gagal";
                    header("location:keluar.php");
                }
        }else {
            $selisih = $qtyskrng - $qty;
            $hitung = $stockskrng + $selisih;
            $kurangistocknya = mysqli_query($conn,"UPDATE stock set stock='$hitung' where idbarang='$idb'");
            $updatenya = mysqli_query($conn,"UPDATE keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
                if ($kurangistocknya&&$updatenya) {
                    header("location:keluar.php");
                }else {
                    echo "Gagal";
                    header("location:keluar.php");
                }
        }
    }

    //hapus barang keluar
    if (isset($_POST['hapusbarangkeluar'])) {
        $idb = $_POST['idb'];
        $idk = $_POST['idk'];

        $getdatastock= mysqli_query($conn,"SELECT * from stock where idbarang='$idb'");
        $data = mysqli_fetch_array($getdatastock);
        $stok = $data['stock'];

        $qtyskrng = mysqli_query($conn, "SELECT * from keluar where idkeluar='$idk'");
        $qtynya = mysqli_fetch_array($qtyskrng);
        $qtyskrng= $qtynya['qty'];

        $selisih = $stok+$qtyskrng;

        $update = mysqli_query($conn,"UPDATE stock set stock='$selisih' where idbarang='$idb'");
        $hapusdata = mysqli_query($conn,"DELETE from keluar where idkeluar='$idk'");
        if($update&&$hapusdata){
            header('location:keluar.php');
        }else {
            header('location:keluar.php');
        }
    }
?>