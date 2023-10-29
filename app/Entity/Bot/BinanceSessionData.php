<?php

namespace App\Entity\Bot;

use Illuminate\Support\Collection;

class BinanceSessionData
{
    private Collection $symbols;
    
    private string $time;
    
    /**
     *
     */
    public function __construct()
    {
        $this->symbols = new Collection();
    }
    
    /**
     * @param  BinanceSymbol $binanceSymbol
     * @return void
     */
    public function addSymbol(BinanceSymbol $binanceSymbol): void
    {
        $this->symbols->add($binanceSymbol);
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
     * @return Collection
     */
    public function getSymbols(): Collection
    {
        return $this->symbols;
    }
    
    /**
     * @param Collection $symbols
     */
    public function setSymbols(Collection $symbols): self
    {
        $this->symbols = $symbols;
        
        return $this;
    }
}
