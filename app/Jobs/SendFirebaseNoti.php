<?php

namespace App\Jobs;

use App\Models\Firebase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNoti implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $body;

    /**
     * Create a new job instance.
     */
    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendPushNotification([
            'title' => $this->title,
            'body' => $this->body,
        ]);
    }

    private function sendPushNotification($message)
    {
        try {
            $firebase = Firebase::all();
            $firebase = $firebase->pluck('token')->toArray();

            $title = $message['title'];
            $body = $message['body'];

            $data = [
                "registration_ids" => $firebase,

                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "aps" => [
                    "title" => $title,
                    "body" => $body,
                    "badge" => "1",
                    "content_available" => true,
                    "priority" => "high",
                ],
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . env('FIREBASE_API_KEY'),
                'Content-Type: application/json',
            ];
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            dump("firebase response: ".$response);
            return $response;
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
    }
}
