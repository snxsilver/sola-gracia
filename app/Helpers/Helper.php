<?php

namespace App\Helpers;

class Helper
{
  private static function penyebut($nilai)
  {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
      $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
      $temp = self::penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
      $temp = self::penyebut($nilai / 10) . " puluh" . self::penyebut($nilai % 10);
    } else if ($nilai < 200) {
      $temp = " seratus" . self::penyebut($nilai - 100);
    } else if ($nilai < 1000) {
      $temp = self::penyebut($nilai / 100) . " ratus" . self::penyebut($nilai % 100);
    } else if ($nilai < 2000) {
      $temp = " seribu" . self::penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
      $temp = self::penyebut($nilai / 1000) . " ribu" . self::penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
      $temp = self::penyebut($nilai / 1000000) . " juta" . self::penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
      $temp = self::penyebut($nilai / 1000000000) . " milyar" . self::penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
      $temp = self::penyebut($nilai / 1000000000000) . " trilyun" . self::penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
  }

  public static function terbilang($nilai)
  {

    if ($nilai < 0) {
      $hasil = "minus " . trim(self::penyebut($nilai));
    } else {
      $hasil = trim(self::penyebut($nilai));
    }
    return $hasil;
  }

  public static function numberToRoman($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
  }

  public static function formatHari($tanggal){
    $bulan = array (
      1 =>   'Jan',
      'Feb',
      'Mar',
      'Apr',
      'Mei',
      'Jun',
      'Jul',
      'Agt',
      'Sep',
      'Okt',
      'Nov',
      'Des'
    );
    $date = date('Y-m-d', strtotime($tanggal));

    $pecahkan = explode('-', $date);
    $hari = date("D", strtotime($tanggal));
    
    switch($hari){
      case 'Sun':
        $hari_ini = "Mng";
      break;
   
      case 'Mon':			
        $hari_ini = "Sen";
      break;
   
      case 'Tue':
        $hari_ini = "Sel";
      break;
   
      case 'Wed':
        $hari_ini = "Rab";
      break;
   
      case 'Thu':
        $hari_ini = "Kam";
      break;
   
      case 'Fri':
        $hari_ini = "Jum";
      break;
   
      case 'Sat':
        $hari_ini = "Sab";
      break;
      
      default:
        $hari_ini = "Tidak di ketahui";		
      break;
    }
   
    return $hari_ini . ', ' . (int)$pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ];
  }

  public static function validasiDuaArray($array1, $array2){
    if(is_array($array1) && is_array($array2)){
      if(count($array1) === count($array2)){
        $c = [];
        for($x = 0; $x < count($array1); $x++){
          $c[] = $array1[$x].'-'.$array2[$x];
        }

        if(count($c) === count(array_unique($c))){
          return true;
        } else {
          return false;
        }
      }
    }
    return false;
  }

  public static function toHuruf($angka){
    if (!is_int($angka)){
      $angka = int($angka);
    }
    $array = range(0,25);
    $array2 = range(26,51);
    $huruf = range('A','Z');
    $huruf2 = [];
    foreach($huruf as $h){
      $huruf2[] = 'A'.$h;
    }
    if (in_array($angka,$array)){
      return $huruf[$angka];
    } elseif(in_array($angka, $array2)){
      $angka = $angka - 26;
      return $huruf2[$angka];
    }
    return false;
  }
}
