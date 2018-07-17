<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2018/7/16
 * Time: 22:38
 */
namespace Wiltechsteam\FoundationServiceSingle\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class WorkTimeCheckedInOutEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}