<?php

namespace App\Http\Controllers;

use App\Models\PrestasiModel;
use Illuminate\Http\Request;

class PoinPrestasiController extends Controller
{
    // juara 1 internasional = 100
    // juara 2 internasional = 90
    // juara 3 internasional = 80
    // juara harapan 1,2,3 internasional = 50

    // juara 1 nasional = 80
    // juara 2 nasional = 70
    // juara 3 nasional = 60
    // juara harapan 1,2,3 nasional = 30

    // juara 1 provinsi = 60
    // juara 2 provinsi = 50
    // juara 3 provinsi = 40
    // juara harapan 1,2,3 provinsi = 10

    // juara 1 kota = 40
    // juara 2 kota = 30
    // juara 3 kota = 20
    // juara harapan 1,2,3 provinsi = 5
    public static function hitungPoin(PrestasiModel $prestasi)
    {
        $poin = 0;
        if ($prestasi->lomba->tingkat->tingkat_lomba_kode == 'INT') {
            if ($prestasi->juara == 1) {
                $poin = 100;
            } else if ($prestasi->juara == 2) {
                $poin = 90;
            } else if ($prestasi->juara == 3) {
                $poin = 80;
            } else if ($prestasi->juara == 4) {
                $poin = 50;
            } else {
                $poin = 0;
            }
        } else if ($prestasi->lomba->tingkat->tingkat_lomba_kode == 'NAS') {
            if ($prestasi->juara == 1) {
                $poin = 80;
            } else if ($prestasi->juara == 2) {
                $poin = 70;
            } else if ($prestasi->juara == 3) {
                $poin = 60;
            } else if ($prestasi->juara == 4) {
                $poin = 30;
            } else {
                $poin = 0;
            }
        } else if ($prestasi->lomba->tingkat->tingkat_lomba_kode == 'PRO') {
            if ($prestasi->juara == 1) {
                $poin = 60;
            } else if ($prestasi->juara == 2) {
                $poin = 50;
            } else if ($prestasi->juara == 3) {
                $poin = 40;
            } else if ($prestasi->juara == 4) {
                $poin = 10;
            } else {
                $poin = 0;
            }
        } else if ($prestasi->lomba->tingkat->tingkat_lomba_kode == 'KAB') {
            if ($prestasi->juara == 1) {
                $poin = 40;
            } else if ($prestasi->juara == 2) {
                $poin = 30;
            } else if ($prestasi->juara == 3) {
                $poin = 20;
            } else if ($prestasi->juara == 4) {
                $poin = 5;
            } else {
                $poin = 0;
            }
        } else {
            $poin = 0;
        }

        return $poin;
    }
}
