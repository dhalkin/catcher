<?php

namespace App\Services;

use DefStudio\Telegraph\Models\TelegraphChat;

/**
 *
 */
class BotSender
{
    /**
     * @var array
     */
    private array $message = [];
    
    /**
     * @var TelegraphChat
     */
    private TelegraphChat $tChat;
    
    /**
     * @param TelegraphChat $tChat
     */
    public function __construct(TelegraphChat $tChat)
    {
        $this->tChat = $tChat;
    }
    
    /**
     * @param string $message
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->message[] = $message . "\n";
    }
    
    /**
     * @param string $entity
     * @return void
     */
    public function sendAndFlush(string $entity): void
    {
        if (count($this->message) > 0) {
            $this->tChat->html("<strong>" . $entity . "</strong>\n" . implode("", $this->message))->send();
        }
        $this->message = [];
    }
}