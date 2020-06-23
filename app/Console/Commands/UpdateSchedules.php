<?php

namespace App\Console\Commands;

use App\Entities\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use Illuminate\Console\Command;

class UpdateSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'machines-schedules:update-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os agendamentos das máquinas.';

    /**
     * @var ScheduleRepositoryContract
     */
    private $scheduleRepository;

    /**
     * Create a new command instance.
     *
     * @param ScheduleRepositoryContract $scheduleRepository
     */
    public function __construct(ScheduleRepositoryContract $scheduleRepository)
    {
        parent::__construct();
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now()->setTime(23, 59, 59)->toDateTimeString();

        $this->scheduleRepository->findWhere([
            ['date', '<=', $now],
            ['status', '!=', 0]
        ])->map(function (Schedule $schedule) {
            $schedule->update([
                'status' => 0
            ]);
        });
    }
}
