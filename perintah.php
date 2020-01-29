<?php  
$usernamebot = "@CounterWordBot";
    $idchat = $sumber['chat']['id'];
    $namadepan = $sumber['from']['first_name'];
    $iduser = $sumber['from']['id'];
    $username = '@'.$sumber['from']['username'];
    //proses ini hanya terjadi jika reply ada isinya
    if (isset($sumber['reply_to_message']['text'])){
        $reply = $sumber['reply_to_message']['text'];
        // $in = explode("IN", $reply)[1];
        // $in = "IN".substr($in, 0, 8);
        if(isset(explode("#", $reply)[1])){
            $sc = explode("#", $reply)[1];
            $sc = "SC".substr($sc, 0, 9);
        }
        else{
            $sc = "kosong";
        }
    }
    if (isset($sumber['text'])) {
        $pesan = $sumber['text'];
        $admin = cariadmin($username);
        $solver = carisolver($username);
        $korlap = carikorlap($username);

        //buat ngecount per waktu
        $kata = explode(" ", $pesan, 3);
        $firstword = ($kata[0]);
        switch ($firstword) {

            //fungsi mulai
            case '/start':
            case '/start' . $usernamebot:
                $text = "Hai $namadepan...👋\nTerimakasih sudah menggunakan bot ini❤️\nUntuk melihat pilihan menu ketik: /help";
                $hasil = sendMessage($idchat, $text);
            break;

            //menu bantuan
            case '/help':
            case '/help' . $usernamebot:
                $text = '💁 Aku adalah bot penghitung tugas ' . myVERSI . "\n";
                $text .= "🎓 Oleh Rizqi dan Syifa UNSIKA 2017\n📅" . lastUPDATE . "\n\n";
                $text .= "💌 Berikut menu yang tersedia .\n";
                $text .= "\n👷🏻‍♂️ Admin : \n";
                $text .= "/register [@username] [jabatan] - untuk mendaftarkan Akun\n";
                $text .= "/kick [@username] [jabatan] - untuk mengeluarkan Akun\n";
                $text .= "/listbulan - melihat kuantitas solver pada bulan ini\n";
                $text .= "/listhari - melihat kuantitas solver pada hari ini\n";
                $text .= "Akses semua fungsi korlap dan solver\n";
                $text .= "\n👨🏻‍💻 Korlap :\n";
                $text .= "❌ Reply tugas lalu ketik [cancel] untuk membatalkan\n";
                $text .= "\n👨🏻‍🔧 Solver :\n";
                $text .= "➕ Reply tugas lalu ketik [done] untuk mencatat pekerjaan\n";
                $text .= "\nAll Users :\n";
                $text .= "/hitungdetail [hari/bulan] [problem] - menghitung problem\n";
                $text .= "/countbulan - menghitung jumlah pencapaian perbulan\n";
                $text .= "/counthari - menghitung jumlah pencapaian perhari ini\n";
                $text .= "/counttanggal - menghitung jumlah pencapaian pertanggal\n";
                $text .= "/help info penggunaan bot ini\n";
                $hasil = sendMessage($idchat, $text);
            break;

                // command listbulan
            case '/listbulan':
            case '/listbulan' . $usernamebot:
                if ($username==$admin) {
                    $text = listbulan();
                }
                else {
                    $text = "Maaf anda bukan admin";
                }
                $hasil = sendMessage($idchat, $text);
            break;

                // command listhari
            case '/listhari':
            case '/listhari' . $usernamebot:
                if($username==$admin){
                    $text = listhari();
                }
                else {
                    $text = "Maaf anda bukan admin";
                }
                $hasil = sendMessage($idchat, $text);
            break;

            //case saat melakukan done
            case 'done':
            case 'Done':
                if ($username == $solver) {
                    $status = "done";
                    if (isset($sc)) {
                        $kodesc = carikode($sc);
                        if ($sc == "kosong") {
                            // $text = "gunakan reply done pada gangguan yang diatasi";
                        } else {
                            if ($kodesc == $sc) {
                                $text = "No $sc sudah diselesaikan, silahkan hubungi korlap untuk cancel terlebih dahulu jika dirasa perlu";
                                $hasil = sendMessage($idchat, $text);
                            } else {
                                tambahtugas($username, $status, $sc, $reply);
                                updtambahbln($username);
                                updtambahhri($username);
                                $text = "$username telah menyelesaikan tugas dengan nomor $sc ✅";
                                $hasil = sendMessage($idchat, $text);
                            }
                        }
                    } else {
                        // $text = "anda harus mereply gangguan terlebih dahulu";
                    }
                } else {
                    $text = "$username bukan solver, silahkan hubungi admin untuk mendaftarkan username anda";
                    $hasil = sendMessage($idchat, $text);
                }
            break;

            //command melakukan cancel oleh korlap
            case 'cancel':
            case 'Cancel':
                if ($username == $korlap) {
                    $status = "done";
                    if (isset($sc)) {
                        if ($sc == "kosong") {
                            // $text = "gunakan reply cancel pada gangguan yang diatasi";
                        } else {
                            $usernamesolver = carisolverlist($sc);
                            updbatalbln($usernamesolver);
                            updbatalhri($usernamesolver);
                            $r = bataltugas($sc);
                            if ($r == 1) {
                                $text = "$username telah membatalakan $sc yang diselesaikan";
                            } else {
                                $text = "Tugas dengan nomor $sc tidak ada di database";
                            }
                            $hasil = sendMessage($idchat, $text);
                        }
                    } else {
                        // $text = "anda harus mereply gangguan terlebih dahulu";
                    }
                } else {
                    $text = "$username bukan korlap, silahkan hubungi admin untuk mendaftarkan username anda";
                    $hasil = sendMessage($idchat, $text);
                }
            break;

            //command register
            case '/register':
            case '/register'.$usernamebot:
                if (isset($kata[1])) {
                    $usernameproses = $kata[1];
                    if(isset($kata[2])){
                        $jabatanproses = $kata[2];
                        if ($jabatanproses=="admin") {
                            if ($username==$admin){  
                                admindaftar($usernameproses);
                                solverdaftar($usernameproses);
                                korlapdaftar($usernameproses);
                                $text = "✅ $usernameproses telah berhasil ditambahkan sebagai admin!";
                            }else{
                                $text = "$username bukan admin ";
                            }
                        
                        }else if ($jabatanproses=="solver") {
                            if ($username==$admin){  
                                solverdaftar($usernameproses);
                                $text = "✅ $usernameproses telah berhasil ditambahkan sebagai solver!";
                            }else{
                                $text = "$username bukan admin ";
                            }                        
                        }else if ($jabatanproses=="korlap") {
                            if ($username==$admin){  
                                korlapdaftar($usernameproses);
                                $text = "✅ $usernameproses telah berhasil ditambahkan sebagai korlap!";
                            }else{
                                $text = "$username bukan admin ";
                            }
                        }else{
                            $text = "Contoh : /register (spasi) @username (spasi) admin/solver/korlap";
                        }
                    }else{
                        $text = "Contoh : /register (spasi) @username (spasi) admin/solver/korlap";
                    }
                }else {
                    $text = "Contoh : /register (spasi) @username (spasi) admin/solver/korlap";
                } 
                $hasil = sendMessage($idchat, $text);
            break;

            //command kick
            case '/kick':
            case '/kick'.$usernamebot:
                if (isset($kata[1])) {
                    $usernameproses = $kata[1];
                    if(isset($kata[2])){
                        $jabatanproses = $kata[2];
                        if ($jabatanproses=="admin") {
                            if ($username==$admin){  
                                if($usernameproses==$username){
                                    $text = "untuk keamanan database Admin tidak bisa mengeluarkan dirinya sendiri";
                                }
                                else{
                                    $r = adminhapus($usernameproses);
                                    if($r==1){
                                        solverhapus($usernameproses);
                                        korlaphapus($usernameproses);
                                        $text = "⛔️ $usernameproses berhasil dihapus dari Admin";
                                    }
                                    else{
                                        $text = "⛔️ $usernameproses tidak ada di database";
                                    }    
                                }
                            }else{
                                $text = "$username bukan admin ";
                            }
                        
                        }else if ($jabatanproses=="solver") {
                            if ($username==$admin){  
                                $r = solverhapus($usernameproses);
                                if($r==1){
                                    $text = "⛔️ $usernameproses berhasil dihapus dari solver";
                                }
                                else{
                                    $text = "⛔️ $usernameproses tidak ada di database";
                                }
                            }else{
                                $text = "$username bukan admin ";
                            }                        
                        }else if ($jabatanproses=="korlap") {
                            if ($username==$admin){  
                                $r = korlaphapus($usernameproses);
                                if($r==1){
                                    $text = "⛔️ $usernameproses berhasil dihapus dari korlap";
                                }
                                else{
                                    $text = "⛔️ $usernameproses tidak ada di database";
                                }
                            }else{
                                $text = "$username bukan admin ";
                            }
                        }else{
                            $text = "Contoh : /kick (spasi) @username (spasi) admin/solver/korlap";
                        }
                    }else{
                        $text = "Contoh : /kick (spasi) @username (spasi) admin/solver/korlap";
                    }
                }else {
                    $text = "Contoh : /kick (spasi) @username (spasi) admin/solver/korlap";
                } 
                $hasil = sendMessage($idchat, $text);
            break;
            
            //fungsi untuk menghitung berdasarkan hari ini
            case '/counthari':
            case '/counthari'.$usernamebot:
                if(isset($kata[1])){
                    if($username==$admin){
                        $usernameproses=$kata[1];
                        $text = counthari($usernameproses);
                    }
                    else{
                        $text = "Anda Bukan Admin! Solver hanya bisa menghitung dirinya sendiri\nContoh /counthari";
                    }
                }
                else{
                    if($username==$admin){
                        $text = "Contoh : /counthari @username";
                    }
                    else{
                        $text = counthari($username);
                    }
                }
                $hasil = sendMessage($idchat, $text);
            break;

            //fungsi untuk menghitung berdasarkan tanggal
            case '/counttanggal':
            case '/counttanggal'.$usernamebot:
                if(isset($kata[1])){
                    $tanggalproses = $kata[1];
                    if(isset($kata[2])){
                        if($username==$admin){
                            $usernameproses = $kata[2];
                            $text = counttanggal($tanggalproses, $usernameproses);
                        }
                        else{
                            $text = "Anda bukan admin, solver hanya bisa menghitung dirinya sendiri\nContoh : /counttanggal 15";
                        }
                    }
                    else{
                        $text = counttanggal($tanggalproses, $username);
                    }
                }
                else{
                    $text = "Solver = Contoh : /counttanggal 15\nAdmin = Contoh : /counttanggal 15 @username";
                }
                $hasil = sendMessage($idchat, $text);   
            break;

                //fungsi menghitung berdasarkan bulan
            case '/countbulan':
            case '/countbulan' . $usernamebot:
                if (isset($kata[1])) {
                    $bulanproses = $kata[1];
                    if (isset($kata[2])) {
                        if ($username == $admin) {
                            $usernameproses = $kata[2];
                            $text = countbulan($bulanproses, $usernameproses);
                        } else {
                            $text = "Anda bukan admin, solver hanya bisa menghitung dirinya sendiri\nContoh : /countbulan januari";
                        }
                    } else {
                        $text = countbulan($bulanproses, $username);
                    }
                } else {
                    $text = "Solver = Contoh : /countbulan januari\nAdmin = Contoh : /countbulan januari @username";
                }
                $hasil = sendMessage($idchat, $text);
            break;

            case '/hitungdetail':
            case '/hitungdetail' . $usernamebot:
                if (isset($kata[1])) {
                    $pilihan=$kata[1];
                    if (isset($kata[2])) {
                        $detailproses = $kata[2];
                        if ($pilihan=="hari") {
                            $text = detailhari($detailproses);
                        }
                        elseif ($pilihan=="bulan") {
                            $text = detailbulan($detailproses);
                        }
                        else {
                            $text = "masukkan kata hari/bulan\nContoh /hitungdetail (spasi) hari/bulan (spasi) fallout";
                        }
                    }
                    else {
                        $text = "Masukkan detail prosesnya\nContoh /hitungdetail (spasi) hari/bulan (spasi) fallout";
                    }
                } 
                else {
                    $text = "Contoh : /hitungdetail (spasi) bulan/hari (spasi) fallout";
                }
                $hasil = sendMessage($idchat, $text);
            break;
        
        }
    } 
?>