<?php

namespace App\Services;

use App\Entity\Bot\SessionData;
use DefStudio\Telegraph\Models\TelegraphChat;
use App\Entity\Bot\Symbol;

/**
 *
 */
class BotSender
{
    private const PRICE_UP = "\xE2\xAC\x86";
    private const PRICE_DOWN = "\xE2\xAC\x87";
    private const UP_DOWN_ARROW = "\xE2\x86\x95";
    
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
     * @param SessionData $sessionData
     * @return void
     */
    public function sendSessionData(SessionData $sessionData): void
    {
        $message[] = "<i><u>Price " . self::UP_DOWN_ARROW . " " . $sessionData->getFilter()->getChangePrice() .
            "% or Vol24h up > " . $sessionData->getFilter()->getChangeVolume() . "</u></i>";
        
        /** @var Symbol $symbol */
        foreach ($sessionData->getSymbols() as $symbol) {
            
            $message[] = "<b>" . $symbol->getName() . "</b> (" . $symbol->getTime() . ")  <code>" . $symbol->getPrice() . "</code>";
            
            $priceMessage = $symbol->getPricePercent() > 0 ? "Price " . self::PRICE_UP : "Price " . self::PRICE_DOWN;
            $message[] = $priceMessage . ": <b>" . $symbol->getPricePercent() . "</b>%";
            
            $volumeMessage = $symbol->getVolumePercent() > 0 ? "Volume " . self::PRICE_UP : "Volume " . self::PRICE_DOWN;
            $message[] = $volumeMessage . ": <b>" . $symbol->getVolumePercent() . "</b>%";
            
            if ($symbol->getCirculationPercent() != 0) {
                $cMessage = ($symbol->getCirculationPercent() > 0) ? self::PRICE_UP : self::PRICE_DOWN;
                $message[] = "Circulating supply: " . $cMessage . "<b>" . $symbol->getCirculationPercent() . "</b>%";
            }
            $message[] = str_repeat('-', 26) . " ";
        }
        
        $this->tChat->html(implode("\n", $message))->send();
    }
}