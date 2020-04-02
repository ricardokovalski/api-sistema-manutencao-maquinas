<?php

namespace App\Services;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

/**
 * Class EmailService
 * @package App\Services
 */
class EmailService
{
    /**
     * @var $template
     */
    private $template;

    /**
     * @var $title
     */
    private $title;

    /**
     * @var $emailFrom
     */
    private $emailFrom;

    /**
     * @var $nameFrom
     */
    private $nameFrom;

    /**
     * @var $emailTo
     */
    private $emailTo;

    /**
     * @var $nameTo
     */
    private $nameTo;

    /**
     * @var $body
     */
    private $body;

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailFrom()
    {
        return $this->emailFrom;
    }

    /**
     * @param $emailFrom
     * @return $this
     */
    public function setEmailFrom($emailFrom)
    {
        $this->emailFrom = $emailFrom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameFrom()
    {
        return $this->nameFrom;
    }

    /**
     * @param $nameFrom
     * @return $this
     */
    public function setNameFrom($nameFrom)
    {
        $this->nameFrom = $nameFrom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailTo()
    {
        return $this->emailTo;
    }

    /**
     * @param $emailTo
     * @return $this
     */
    public function setEmailTo($emailTo)
    {
        $this->emailTo = $emailTo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameTo()
    {
        return $this->nameTo;
    }

    /**
     * @param $nameTo
     * @return $this
     */
    public function setNameTo($nameTo)
    {
        $this->nameTo = $nameTo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function sendEmail()
    {
        if (config("mail.host") and
            config("mail.username") and
            config("mail.password")
        ) {

            $message = function (Message $message) {
                $message->from($this->getEmailFrom(), $this->getNameFrom())
                    ->to($this->getEmailTo(), $this->getNameTo())
                    ->subject($this->getTitle());
            };

            Mail::send($this->getTemplate(), $this->getBody(), $message);

            return true;
        }

        return null;
    }

}