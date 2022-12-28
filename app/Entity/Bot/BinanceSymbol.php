<?php

namespace App\Entity\Bot;

class BinanceSymbol
{
    /**
     * @var string
     */
    private string $name;
    
    /**
     * @var float
     */
    private float $changePrice;
    
    /**
     * @var float
     */
    private float $currentPriceUSDT;
    
    /**
     * @var string
     */
    private string $time;
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return float
     */
    public function getChangePrice(): float
    {
        return $this->changePrice;
    }
    
    /**
     * @param float $changePrice
     */
    public function setChangePrice(float $changePrice): void
    {
        $this->changePrice = $changePrice;
    }
    
    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }
    
    /**
     * @param string $time
     */
    public function setTime(string $time): void
    {
        $this->time = $time;
    }
    
    /**
     * @return float
     */
    public function getCurrentPriceUSDT(): float
    {
        return $this->currentPriceUSDT;
    }
    
    /**
     * @param float $currentPriceUSDT
     */
    public function setCurrentPriceUSDT(float $currentPriceUSDT): void
    {
        $this->currentPriceUSDT = $currentPriceUSDT;
    }
}
