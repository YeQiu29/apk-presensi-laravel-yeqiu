<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically checks out employees who forgot to check out.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();
        $this->info("Running auto-checkout for date: {$today}");

        $attendances = DB::table('presensi')
            ->where('tgl_presensi', $today)
            ->whereNotNull('jam_in')
            ->whereNull('jam_out')
            ->get();

        if ($attendances->isEmpty()) {
            $this->info('No employees to auto-checkout.');
            return;
        }

        foreach ($attendances as $attendance) {
            DB::table('presensi')
                ->where('id', $attendance->id)
                ->update([
                    'jam_out' => '23:59:59',
                    'status_checkout' => 'auto',
                    'foto_out' => $attendance->foto_in,
                    'lokasi_out' => $attendance->lokasi_in
                ]);

            $this->info("Auto-checked out employee: {$attendance->nik}");
        }

        $this->info('Auto-checkout process completed.');
    }
}
