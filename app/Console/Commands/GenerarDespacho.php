<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\VehiculosCombustible\JobController;

class GenerarDespacho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrar:detalledespacho';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $objDetalleDespacho = new JobController();
        echo $objDetalleDespacho->guardarDetalleDespachoJob();
    }
}