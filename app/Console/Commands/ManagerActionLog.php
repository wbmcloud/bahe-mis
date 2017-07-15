<?php

namespace App\Console\Commands;

use App\Models\ActionLog;
use Cyberduck\LaravelExcel\ExporterFacade;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ManagerActionLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manager:action_log {ids*}';

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
        $ids = $this->argument('ids');
        $action_logs = ActionLog::whereIn('id', $ids)->get()->toArray();
        $action_logs = array_map(function ($action_log) {
            $action_log['params'] = decrypt($action_log['params']);
            return $action_log;
        }, $action_logs);

        Excel::create('action_log', function($excel) use ($action_logs) {

            $excel->sheet('action_log', function($sheet) use ($action_logs) {

                $sheet->fromArray($action_logs);

            });

        })->store('xlsx');
    }
}
