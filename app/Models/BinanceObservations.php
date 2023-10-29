<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $symbol
 * @property float $price
 * @property string $session_time
 */
class BinanceObservations extends Model
{
    public const UPDATED_AT = null;
    public const CREATED_AT = null;
    
    /**
     * @var array
     */
    protected $fillable = ['symbol', 'price', 'session_time'];
    
    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }
    
    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }
    
    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
    
    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
    
    /**
     * @return string
     */
    public function getSessionTime(): string
    {
        return $this->session_time;
    }
    
    /**
     * @param string $session_time
     */
    public function setSessionTime(string $session_time): void
    {
        $this->session_time = $session_time;
    }
}
