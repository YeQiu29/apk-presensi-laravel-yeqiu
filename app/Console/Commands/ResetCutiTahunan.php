<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetCutiTahunan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuti:reset-tahunan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset saldo cuti tahunan untuk semua karyawan menjadi 12';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('karyawan')->update(['saldo_cuti' => 12]);
        $this->info('Saldo cuti tahunan semua karyawan telah direset menjadi 12.');
    }
}
