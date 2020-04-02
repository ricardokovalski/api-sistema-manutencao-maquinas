<?php

namespace App\Jobs;

use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formData;

    /**
     * SendEmail constructor.
     * @param $formData
     */
    public function __construct($formData)
    {
        $this->formData = (object) $formData;
    }

    /**
     * @param EmailService $emailService
     */
    public function handle(EmailService $emailService)
    {
        $emailService->setTitle('Notificação de manutenção')
            ->setNameTo($this->formData->nameTo)
            ->setEmailTo($this->formData->emailTo)
            ->setNameFrom(config('mail.from.name'))
            ->setEmailFrom(config('mail.from.address'))
            ->setTemplate('admin.email.machine')
            ->setBody([
                'content' => 'Teste 123'
            ])
            ->sendEmail();
    }
}
