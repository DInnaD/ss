<?php
/**
 * DentaProject.
 * User: Serg
 * Date: 23.06.2018
 * Time: 1:02
 */

namespace App\Http\Traits;


use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\OptionsPriorities;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

trait PushNotificationTrait
{
    public function sendNotification($token, $title = 'Title is empty', $body = 'Text is empty', $data = ['key' => 'value'], $sound = 'default')
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $optionBuilder->setPriority(OptionsPriorities::high);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound($sound);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        FCM::sendTo($token, $option, $notification, $data);
    }
}