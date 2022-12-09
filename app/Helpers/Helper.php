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
}
