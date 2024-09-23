<?php


namespace App\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use Illuminate\Support\Facades\Log;


class NewMessage implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;


    public $message;
    public $senderId;
    public $recipientId;


    /**
     * Создаём событие.
     *
     * @param Message $message
     * @param int $recipientId
     */
    public function __construct(Message $message)
    {
        $this->message = $message->content;
        $this->senderId = $message->sender_id;
        $this->recipientId = $message->receiver_id;
        $this->created_at = $message->created_at;
    }


    /**
     * Определение канала, на который транслируется событие.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        // Логирование для проверки работы события
        Log::info('New message event broadcasted:', [
            'message' => $this->message,
            'senderId' => $this->senderId,
            'recipientId' => $this->recipientId
        ]);


        // Возвращаем приватный канал
        return new PrivateChannel('chat.' . 3);
    }


    /**
     * Определение данных, которые будут переданы клиенту.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message, // Текст сообщения
            'sender' => $this->senderId, // ID отправителя
            'receiver' => $this->recipientId,
            'created_at' => $this->created_at,// ID получателя
        ];
    }


    /**
     * Определение имени события.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-message'; // Определяем имя события, которое будет слушать клиент
    }
}





