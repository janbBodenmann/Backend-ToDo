<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Todos;


class Mail extends BaseController
{
    protected $modelName = 'App\Models\Todos';

    public function sendEmail()
    {
        $email = \Config\Services::email();
        $emailTodo = new Todos();
        $emailOpen = $emailTodo->getOpenTodos();
        $email_config = config('Email');

        if (!empty($emailOpen)) {
            $email->setSubject('Email Open Todos');
            $textSend = "";
            $email->setFrom($email_config->fromEmail, 'Open Manager');
            $email->setTo($email_config->recipients);
            foreach ($emailOpen as $em) {
                $textSend .= "{$em['name']} \n";
            }
            $email->setMessage($textSend);
            if ($email->send()) {
                log_message('info', 'E-Mail erfolgreich versendet an: ' . config('Email.recipients'));
                return 'E-Mail erfolgreich versendet';
            } else {
                $errorMessage = $email->printDebugger(['headers']);
                log_message('error', 'E-Mail-Versand fehlgeschlagen an: ' . config('Email.recipients') . ' Fehler: ' . $errorMessage);
                return 'E-Mail-Versand fehlgeschlagen';
            }

        } else {
            return "failed";
        }



    }
}