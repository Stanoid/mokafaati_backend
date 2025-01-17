<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use App\Models\User;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use YieldStudio\LaravelExpoNotifier\ExpoNotificationsChannel;
use YieldStudio\LaravelExpoNotifier\Dto\ExpoMessage;


class WithdrawSuccessful extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $amount;
    protected $to;
    public function __construct($amount,$to)
    {
        $this->amount = round($amount/100, 2);
        $this->to = $to;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
       // return ['database'];
        //return [FcmChannel::class];
        return [ExpoNotificationsChannel::class,'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {

        return [
            'data' => $this->amount .' points was successfully sent to'.$this->to

        ];

    }

    public function toExpoNotification($notifiable): ExpoMessage
    {
        return (new ExpoMessage())
            ->to([$notifiable->fcm_token])
            ->title('Points sent ')
            ->body($this->amount .' points was successfully sent to'.$this->to)
            ->channelId('default');
    }


    // public function toFcm($notifiable): FcmMessage
    // {
    //     return (new FcmMessage(notification: new FcmNotification(
    //             title: 'Points recived',
    //             body: 'You recieved'.$this->amount.'from a friend.',
    //            // image: 'http://example.com/url-to-image-here.png'
    //         )))
    //         ->data(['data1' => 'value', 'data2' => 'value2'])
    //         ->custom([
    //             'android' => [
    //                 'notification' => [
    //                     'color' => '#0A0A0A',
    //                 ],
    //                 'fcm_options' => [
    //                     'analytics_label' => 'analytics',
    //                 ],
    //             ],
    //             'apns' => [
    //                 'fcm_options' => [
    //                     'analytics_label' => 'analytics',
    //                 ],
    //             ],
    //         ]);
    // }
}
