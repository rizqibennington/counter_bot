<?php
// fungsi mengkoneksikan query
$conn = mysqli_connect("localhost","root","","counter");
function query($query){
	global $conn;

	$result = mysqli_query($conn, $query);
	$rows = [];
	while($row = mysqli_fetch_assoc($result)){
		$rows[] = $row;
	}
	return $rows;
}


// fungsi menghitung jumlah tugas
function countbulan($bulanproses, $usernameproses){
    global $conn;
    if (($bulanproses=="jan")||($bulanproses=="januari")) {
        $bulanke = 01;
    }
    else if (($bulanproses=="feb")||($bulanproses=="februari")) {
        $bulanke = 02;
    }
    else if (($bulanproses=="mar")||($bulanproses=="maret")) {
        $bulanke = 03;
    }
    else if (($bulanproses=="apr")||($bulanproses=="april")) {
        $bulanke = 04;
    }
    else if ($bulanproses=="mei") {
        $bulanke = 05;
    }
    else if (($bulanproses=="jun")||($bulanproses=="juni")) {
        $bulanke = 06;
    }
    else if (($bulanproses=="jul")||($bulanproses=="juli")) {
        $bulanke = 07;
    }
    else if (($bulanproses=="agu")||($bulanproses=="agustus")) {
        $bulanke = '08';
    }
    else if (($bulanproses=="sep")||($bulanproses=="september")) {
        $bulanke = '09';
    }
    else if (($bulanproses=="okt")||($bulanproses=="oktober")) {
        $bulanke = '10';
    }
    else if (($bulanproses=="nov")||($bulanproses=="november")) {
        $bulanke = '11';
    }
    else if (($bulanproses=="des")||($bulanproses=="desember")) {
        $bulanke = '12';
    }
    else {
        $bulanke ='0';
    }
    $hasil = "$usernameproses tidak ada pada bulan yang diinginkan";
    $jml = count(query("SELECT * FROM list WHERE username = '$usernameproses' AND MONTH(waktu) = $bulanke"));
    if ($jml > 0) {
            $hasil = "👨🏻‍🔧 $usernameproses\n📊 $jml tugas diselesaikan\n📅 di bulan $bulanproses";
    }
    return $hasil;
}

function counthari($usernameproses){
	global $conn;
    $hasil = "$usernameproses tidak ada pada hari ini \nContoh : /counthari @username";
    $jml = count(query("SELECT * FROM list WHERE username = '$usernameproses' AND DATE(waktu) = CURRENT_DATE"));
    if ($jml > 0) {
            $hasil = "👨🏻‍🔧 $usernameproses\n📊 $jml Tugas diselesaikan\n📅 Hari ini";
    }
    return $hasil;
}

function counttanggal($tanggalproses, $usernameproses){
	global $conn;
    $hasil = "$usernameproses tidak ada pada tanggal $tanggalproses \nContoh : /counttanggal 15";
    $jml = count(query("SELECT * FROM list WHERE username = '$usernameproses' AND DAY(waktu) = '$tanggalproses'"));
    if ($jml > 0) {
            $hasil = "👨🏻‍🔧 $usernameproses\n📊 $jml Tugas diselesaikan\n📅 Tanggal $tanggalproses bulan ini";
    }
    return $hasil;
}

// fungsi menghitung jumlah detail
function detailbulan($detailproses){
    global $conn;
    $bulan = date('m');
    $hasil = "kata $detailproses tidak ditemukan pada database bulan ini";
    $jml = count(query("SELECT * FROM `list` WHERE MONTH(waktu) = $bulan AND `detail` LIKE '%$detailproses%'"));
    if ($jml > 0) {
            $hasil = "📊 Bulan ini kami menemukan : $jml hasil\n🔍 kata pencarian : ('$detailproses')";
    }
    return $hasil;
}

//menghitung detail hari
function detailhari($detailproses)
{
    global $conn;
    $hasil = "kata $detailproses tidak ditemukan pada database hari ini";
    $jml = count(query("SELECT * FROM `list` WHERE  DATE(waktu) = CURRENT_DATE AND `detail` LIKE '%$detailproses%'"));
    if ($jml > 0) {
        $hasil = "📊 Hari ini kami menemukan : $jml hasil\n🔍 kata pencarian : ".'"'."$detailproses".'"';
    }
    return $hasil;
}

// fungsi mencari data admin
function cariadmin($username)
{   
    global $conn;
    $hasil = "user tidak ada";
 	$datas = query("SELECT username from admin where username = ('$username')");

    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $hasil = "$data[username]";
        }  
    }
    return $hasil;
}

function carisolver($username)
{   
    global $conn;
    $hasil = "user tidak ada";
 	$datas = query("SELECT username from solver where username = ('$username')");

    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $hasil = "$data[username]";
        }  
    }
    return $hasil;
}

function carikorlap($username)
{   
    global $conn;
    $hasil = "user tidak ada";
 	$datas = query("SELECT username from korlap where username = ('$username')");

    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $hasil = "$data[username]";
        }  
    }
    return $hasil;
}

//fungsi insert admin
function admindaftar($username)
 {
    global $conn;
    $hasil = "INSERT INTO admin (username) VALUES('$username')";
    mysqli_query($conn, $hasil);
    return mysqli_affected_rows($conn);

}
//fungsi menghapus admin
function adminhapus($username)
{
    global $conn;
    $hasil = "Data tidak ada";
    $data = query("SELECT username from admin where username = ('$username')"); 
    $jml = count($data);
    if ($jml > 0){
        mysqli_query($conn, "DELETE FROM admin WHERE username ='$username'");   
        $hasil= mysqli_affected_rows($conn);
    } 
    return $hasil;
}

function solverdaftar($username)
 {
    global $conn;
    $query = "INSERT INTO `solver` (`username`, `hari`, `bulan`, `tanggal`) VALUES ('$username', '0', '0', CURRENT_DATE);";
    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function solverhapus($username)
{
    global $conn;
    $hasil = "Data tidak ada";
    $data = query("SELECT username from solver where username = ('$username')"); 
    $jml = count($data);
    if ($jml > 0){
        mysqli_query($conn, "DELETE FROM solver WHERE username ='$username'");   
        $hasil= mysqli_affected_rows($conn);
    } 
    return $hasil;
}

function korlapdaftar($username)
 {
    global $conn;
    $hasil = "INSERT INTO korlap (username) VALUES('$username')";
    mysqli_query($conn, $hasil);
    return mysqli_affected_rows($conn);

}

function korlaphapus($username)
{
    global $conn;
    $hasil = "Data tidak ada";
    $data = query("SELECT username from korlap where username = ('$username')"); 
    $jml = count($data);
    if ($jml > 0){
        mysqli_query($conn, "DELETE FROM korlap WHERE username ='$username'");   
        $hasil= mysqli_affected_rows($conn);
    } 
    return $hasil;
}

function tambahtugas($username, $status, $sc, $reply)
{
    global $conn;
    $query = "INSERT INTO `list` (`username`, `status`, `no_sc`, `detail`) VALUES 
    ('$username', '$status', '$sc', '$reply');";
    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function updtambahbln($username)
{
    global $conn;
    $hasil = "Data tidak ada";
    $bulan = date('m');
    $datas = query("SELECT bulan from solver where username = '$username' AND MONTH(tanggal) = $bulan"); 
    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $bulan = "$data[bulan]";
        }
        $hitung = $bulan+1;
        mysqli_query($conn, "UPDATE `solver` SET `bulan`= '$hitung ,`tanggal`= CURRENT_DATE()' WHERE username = '$username'");   
        $hasil= mysqli_affected_rows($conn);
    }
    else{
        mysqli_query($conn, "UPDATE `solver` SET `bulan`= '1' ,`tanggal`= CURRENT_DATE()  WHERE username = '$username'");   
        $hasil= mysqli_affected_rows($conn);
    } 
    return $hasil;
}

function updtambahhri($username)
{
    global $conn;
    $hasil = "Data tidak ada";
    $datas = query("SELECT hari from solver where username = '$username' AND DATE(tanggal) = CURRENT_DATE"); 
    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $hari = "$data[hari]";
        }
        $hitung = $hari+1;
        mysqli_query($conn, "UPDATE `solver` SET `hari`= '$hitung ,`tanggal`= CURRENT_DATE()' WHERE username = '$username'");   
        $hasil= mysqli_affected_rows($conn);
    }
    else{
        mysqli_query($conn, "UPDATE `solver` SET `hari`= '1' ,`tanggal`= CURRENT_DATE()  WHERE username = '$username'");   
        $hasil= mysqli_affected_rows($conn);
    } 
    return $hasil;
}

function updbatalbln($usernamesolver)
{
    global $conn;
    $hasil = "Data tidak ada";
    $datas = query("SELECT bulan from solver where username = '$usernamesolver'"); 
    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $bulan = "$data[bulan]";
        }
        $hitung = $bulan-1;
        mysqli_query($conn, "UPDATE `solver` SET `bulan`= '$hitung' WHERE username = '$usernamesolver'");   
        $hasil= mysqli_affected_rows($conn);
    }
    return $hasil;
}

function updbatalhri($usernamesolver)
{
    global $conn;
    $hasil = "Data tidak ada";
    $datas = query("SELECT hari from solver where username = '$usernamesolver'"); 
    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $hari = "$data[hari]";
        }
        $hitung = $hari-1;
        mysqli_query($conn, "UPDATE `solver` SET `hari`= '$hitung' WHERE username = '$usernamesolver'");   
        $hasil= mysqli_affected_rows($conn);
    }
    return $hasil;
}

// fungsi membatalkan hashtag yang baru di input
function bataltugas($sc){
	global $conn;
	 mysqli_query($conn, "DELETE FROM list WHERE no_sc='$sc'");

	 return mysqli_affected_rows($conn);
}

function carikode($sc)
{
    global $conn;
    $hasil = "kode tidak ada";
 	$datas = query("SELECT no_sc from list where no_sc = ('$sc')");

    $jml = count($datas);
    if ($jml > 0){
        foreach($datas as $data){
            $hasil = "$data[no_sc]";
        }  
    }
    return $hasil;
}

function carisolverlist($sc)
{
    global $conn;
    $hasil = "user tidak ada";
 	$datas = query("SELECT username from list where no_sc = ('$sc')");

    $jml = count($datas);
    if ($jml > 0 ){
        foreach($datas as $data){
            $hasil = "$data[username]";
        }  
    }
    return $hasil;
}

function listbulan()
{
    global $conn;
    $hasil = "Data tidak ada";
    $bulan = date('m');
    $datas = query("SELECT username, bulan from solver where MONTH(tanggal) = $bulan"); 
    $jml = count($datas);
     if ($jml >0 ){
        $hasil = "✍🏽 List kuantitas solver bulan ini :\n";
         $n = 0;
        foreach($datas as $data){
            $n++;
            $hasil .= "\n$n. $data[username] mengerjakan "."$data[bulan]";
        }  
    }
    return $hasil;
}

function listhari()
{
    global $conn;
    $hasil = "Data tidak ada";
    $bulan = date('m');
    $datas = query("SELECT username, hari from solver where tanggal = CURRENT_DATE() "); 
    $jml = count($datas);
     if ($jml >0 ){
        $hasil = "✍🏽 List kuantitas solver hari ini :\n";
         $n = 0;
        foreach($datas as $data){
            $n++;
            $hasil .= "\n$n. $data[username] mengerjakan "."$data[hari]";
        }  
    }
    return $hasil;
}

?>