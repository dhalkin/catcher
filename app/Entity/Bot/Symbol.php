<?php

namespace App\Entity\Bot;

class Symbol
{
    
    /**
     * @var string
     */
    private string $time;
    
    /**
     * @var string
     */
    private string $name;
    
    /**
     * @var float
     */
    private float $pricePercent;
    
    /**
     * @var float
     */
    private float $volumePercent;
    
    /**
     * @var float
     */
    private float $circulationPercent;
    
    /**
     * @var float
     */
    private float $price;
    
    
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
    public function getPricePercent(): float
    {
        return $this->pricePercent;
    }
    
    /**
     * @param float $pricePercent
     */
    public function setPricePercent(float $pricePercent): void
    {
        $this->pricePercent = $pricePercent;
    }
    
    /**
     * @return float
     */
    public function getVolumePercent(): float
    {
        return $this->volumePercent;
    }
    
    /**
     * @param float $volumePercent
     */
    public function setVolumePercent(float $volumePercent): void
    {
        $this->volumePercent = $volumePercent;
    }
    
    /**
     * @return float
     */
    public function getCirculationPercent(): float
    {
        return $this->circulationPercent;
    }
    
    /**
     * @param float $circulationPercent
     */
    public function setCirculationPercent(float $circulationPercent): void
    {
        $this->circulationPercent = $circulationPercent;
    }
    
    /**
     * @return float
     */
    public function getPrice(): float
    {
        $parts = explode('.', (string) $this->price);
        return match (strlen($parts[0])) {
            5 => round($this->price, 2),
            4 => round($this->price, 3),
            3 => round($this->price, 4),
            2 => round($this->price, 5),
            default => round($this->price, 6),
        };
    }
    
    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = round($price, 6);
    }
}