<?php

namespace App\Entity\Bot;

use Illuminate\Support\Collection;
use App\Entity\Bot\Filter\Filter;

class SessionData
{
    /**
     * @var string
     */
    private string $time;
    
    /**
     * @var Collection
     */
    private Collection $symbols;
    
    private Filter $filter;
    
    /**
     *
     */
    public function __construct()
    {
        $this->symbols = new Collection();
    }
    
    /**
     * @param Symbol $symbol
     * @return void
     */
    public function addSymbol(Symbol $symbol): void
    {
        $this->symbols->add($symbol);
    }
    
    /**
     * @return array
     */
    public function getSymbols(): Collection
    {
        return $this->symbols;
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
     * @param Collection $symbols
     * @return SessionData
     */
    public function setSymbols(Collection $symbols): self
    {
        $this->symbols = $symbols;
        
        return $this;
    }
    
    /**
     * @param Filter $filter
     * @return SessionData
     */
    public function setFilter(Filter $filter): self
    {
        $this->filter = $filter;
    
        return $this;
    }
    
    /**
     * @return Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }
}
