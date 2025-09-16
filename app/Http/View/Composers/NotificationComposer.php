<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class NotificationComposer
{
    public function compose(View $view)
    {
        $today = date('Y-m-d');

        // 1. Get pending leave/sick requests
        $pending_requests = DB::table('pengajuan_izin')
            ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->where('status_approved', 0)
            ->orderBy('tgl_izin', 'desc')
            ->get();

        // 2. Get employees who were late today
        $late_employees = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $today)
            ->where('jam_in', '>', '07:00')
            ->orderBy('jam_in', 'desc')
            ->get();

        $view->with('pending_requests', $pending_requests)
             ->with('late_employees', $late_employees);
    }
}
