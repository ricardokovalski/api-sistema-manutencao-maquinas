<?php

namespace App\Console\Commands;

use App\Jobs\SendEmail;
use App\Repositories\Contracts\MachineRepositoryContract;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CheckMachines extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:machines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checar máquinas para manutenção e notificar os responsáveis técnicos.';

    private $machineRepository;

    /**
     * Create a new command instance.
     *
     * @param MachineRepositoryContract $machineRepository
     */
    public function __construct(MachineRepositoryContract $machineRepository)
    {
        parent::__construct();
        $this->machineRepository = $machineRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $machines = $this->machineRepository->getMachinesPeriodMaintenance();

        foreach ($machines as $machine) {

            $users = $machine->users->map(function($item) {
                return array(
                    'nameTo' => $item->name,
                    'emailTo' => $item->email,
                );
            })->toArray();

            $this->dispatch(new SendEmail([
                'users' => $users,
                'machine' => $machine
            ]));
        }
    }
}
