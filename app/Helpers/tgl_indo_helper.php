<?php

if (!function_exists('date_indo')) {
    function date_indo($tgl)
    {
        // Memastikan hanya mengambil bagian tanggal (YYYY-MM-DD)
        $tgl = substr($tgl, 0, 10);
        $pecah = explode("-", $tgl);
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (!function_exists('bulan')) {
    function bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Januari";
            case 2:
                return "Februari";
            case 3:
                return "Maret";
            case 4:
                return "April";
            case 5:
                return "Mei";
            case 6:
                return "Juni";
            case 7:
                return "Juli";
            case 8:
                return "Agustus";
            case 9:
                return "September";
            case 10:
                return "Oktober";
            case 11:
                return "November";
            case 12:
                return "Desember";
        }
    }
}

if (!function_exists('shortdate_indo')) {
    /**
     * Format date to short Indonesian date format
     *
     * @param string $tgl
     * @return string
     */
    function shortdate_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = short_bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . '/' . $bulan . '/' . $tahun;
    }
}

if (!function_exists('short_bulan')) {
    /**
     * Get short month number in Indonesian
     *
     * @param int $bln
     * @return string
     */
    function short_bulan($bln)
    {
        return str_pad($bln, 2, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('mediumdate_indo')) {
    /**
     * Format date to medium Indonesian date format
     *
     * @param string $tgl
     * @return string
     */
    function mediumdate_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = medium_bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . '-' . $bulan . '-' . $tahun;
    }
}

if (!function_exists('medium_bulan')) {
    /**
     * Get medium month name in Indonesian
     *
     * @param int $bln
     * @return string
     */
    function medium_bulan($bln)
    {
        $bulan = [
            1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "Mei",
            6 => "Jun", 7 => "Jul", 8 => "Ags", 9 => "Sep", 10 => "Okt",
            11 => "Nov", 12 => "Des"
        ];
        return $bulan[$bln];
    }
}

if (!function_exists('longdate_indo')) {
    /**
     * Format date to long Indonesian date format
     *
     * @param string $tanggal
     * @return string
     */
    function longdate_indo($tanggal)
    {
        $ubah = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tgl = $pecah[2];
        $bln = $pecah[1];
        $thn = $pecah[0];
        $bulan = bulan($pecah[1]);

        $nama_hari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $hari = $nama_hari[date("l", mktime(0, 0, 0, $bln, $tgl, $thn))];

        return $hari . ', ' . $tgl . ' ' . $bulan . ' ' . $thn;
    }
}
