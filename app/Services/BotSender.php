<?php

namespace App\Services;

use App\Entity\Bot\BinanceSessionData;
use App\Entity\Bot\BinanceSymbol;
use App\Entity\Bot\SessionData;
use App\Models\TelegraphChat;
use App\Entity\Bot\Symbol;
use Illuminate\Support\Collection;

/**
 *
 */
class BotSender
{
    private const PRICE_UP = "\xE2\xAC\x86";
    private const PRICE_DOWN = "\xE2\xAC\x87";
    private const UP_DOWN_ARROW = "\xE2\x86\x95";
    
    private const CHUNK_OUTPUT_MESSAGE = 10;
    
    /**
     * @var TelegraphChat
     */
    private TelegraphChat $tChat;
    
    /**
     *
     */
    public function __construct()
    {
        $this->tChat = TelegraphChat::find(1);
    }
    
    /**
     * @param  SessionData $sessionData
     * @return void
     * @codingStandardsIgnoreStart
     */
    public function sendSessionData(SessionData $sessionData): void
    {
        $message[] = "<i><u>Price " . self::UP_DOWN_ARROW . " " . $sessionData->getFilter()->getChangePrice() .
            "% or Vol24h up " . $sessionData->getFilter()->getChangeVolume() . "%</u></i>";
        
        $chunked = $sessionData->getSymbols()->chunk(self::CHUNK_OUTPUT_MESSAGE);
        foreach ($chunked as $chunk) {
            /**
             * @var Symbol $symbol
            */
            foreach ($chunk as $symbol) {
                $message[] = "<b>" . $symbol->getSymbol() . "</b> (" . $symbol->getTime() . ")  <code>" . $symbol->getPrice() . "</code>";
                $message[] = $symbol->getName();
            
                $priceMessage = $symbol->getPricePercent() > 0 ? "Price " . self::PRICE_UP : "Price " . self::PRICE_DOWN;
                $message[] = $priceMessage . ": <b>" . $symbol->getPricePercent() . "</b>%";
            
                $volumeMessage = $symbol->getVolumePercent() > 0 ? "Volume24h " . self::PRICE_UP : "Volume24h " . self::PRICE_DOWN;
                $message[] = $volumeMessage . ": <b>" . $symbol->getVolumePercent() . "</b>%";
            
                // show only it's the reason why sybmol is here
                if ($symbol->getCirculationPercent() > 0) {
                    $cMessage = ($symbol->getCirculationPercent() > 0) ? self::PRICE_UP : self::PRICE_DOWN;
                    $message[] = "Circulating supply: " . $cMessage . "<b>" . $symbol->getCirculationPercent() . "</b>%";
                }
                $message[] = str_repeat('-', 30) . " ";
            }
            
            // send chunked
            $this->tChat->html(implode("\n", $message))->send();
            $message = [];
        }
    }
    
    /**
     * @param  string     $start
     * @param  string     $stop
     * @param  Collection $data
     * @return void
     */
    public function sendWatchlistData(string $start, string $stop, Collection $data): void
    {
        $chunked = $data->chunk(self::CHUNK_OUTPUT_MESSAGE);
        foreach ($chunked as $chunk) {
            $message[] = "<i>Watchlist</i> 👽";
            $message[] = "<b>Start</b> " . $start;
            $message[] = "<b>Stop</b> " . $stop;
            $message[] = str_repeat('-', 30) . " ";
            
            foreach ($chunk as $row) {
                $message[] = "<b>" . $row['symbol'] . "</b> - " . $row['result'] . "(<b>" . $row['priceChanged'] . "</b>%)";
                $message[] = $row['name'];
                $message[] = str_repeat('-', 30) . " ";
            }
    
            $this->tChat->html(implode("\n", $message))->send();
            $message = [];
        }
    }
    
    /**
     * @param  BinanceSessionData $binanceSessionData
     * @param  int                $percent
     * @return void
     */
    public function sendBinanceSessionData(BinanceSessionData $binanceSessionData, int $percent): void
    {
        /* @var BinanceSymbol $a */
        /* @var BinanceSymbol $b */
        $sorted = $binanceSessionData->getSymbols()->sort(
            function ($a, $b) {
                if ($a->getChangePrice() == $b->getChangePrice()) {
                    return 0;
                }
                return ($a->getChangePrice() > $b->getChangePrice()) ? -1 : 1;
            }
        );
        
        $chunked = $sorted->chunk(self::CHUNK_OUTPUT_MESSAGE);
        foreach ($chunked as $chunk) {
            $message[] = "<i>Binance session </i> (" . $percent ."%) 👾";
            
            /* @var BinanceSymbol $item */
            foreach ($chunk as $item) {
                $arrow = ($item->getChangePrice() > 0) ? self::PRICE_UP : self::PRICE_DOWN ;
                $message[] = "<b>" . $item->getName() . "</b>  (" . $item->getTime() . ")" . $arrow . " " . $item->getChangePrice() . "%";
            }
            $this->tChat->html(implode("\n", $message))->send();
            $message = [];
        }
    }
}
