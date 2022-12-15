<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $cryptorank_id
 * @property string $sessionTime
 * @property string $lastUpdated
 * @property string $name
 * @property string $symbol
 * @property integer $circulatingSupply
 * @property integer $totalSupply
 * @property float $price
 * @property integer $volume24h
 * @property float $percentChange24h
 */
class CryptorankObservation extends Model
{
    public const UPDATED_AT = null;
    public const CREATED_AT = null;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cryptorank_observations';
    
    /**
     * @var array
     */
    protected $fillable = [
        'cryptorank_id',
        'sessionTime',
        'lastUpdated',
        'name',
        'symbol',
        'type',
        'circulatingSupply',
        'totalSupply',
        'price',
        'volume24h',
        'percentChange24h'
    ];
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @return int
     */
    public function getCryptorankId(): int
    {
        return $this->cryptorank_id;
    }
    
    /**
     * @param int $cryptorank_id
     */
    public function setCryptorankId(int $cryptorank_id): void
    {
        $this->cryptorank_id = $cryptorank_id;
    }
    
    /**
     * @return string
     */
    public function getSessionTime(): string
    {
        return $this->sessionTime;
    }
    
    /**
     * @param string $sessionTime
     */
    public function setSessionTime(string $sessionTime): void
    {
        $this->sessionTime = $sessionTime;
    }
    
    /**
     * @return string
     */
    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }
    
    /**
     * @param string $lastUpdated
     */
    public function setLastUpdated(string $lastUpdated): void
    {
        $this->lastUpdated = $lastUpdated;
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
     * @return int
     */
    public function getCirculatingSupply(): int
    {
        return $this->circulatingSupply;
    }
    
    /**
     * @param int $circulatingSupply
     */
    public function setCirculatingSupply(int $circulatingSupply): void
    {
        $this->circulatingSupply = $circulatingSupply;
    }
    
    /**
     * @return int
     */
    public function getTotalSupply(): int
    {
        return $this->totalSupply;
    }
    
    /**
     * @param int $totalSupply
     */
    public function setTotalSupply(int $totalSupply): void
    {
        $this->totalSupply = $totalSupply;
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
     * @return int
     */
    public function getVolume24h(): int
    {
        return $this->volume24h;
    }
    
    /**
     * @param int $volume24h
     */
    public function setVolume24h(int $volume24h): void
    {
        $this->volume24h = $volume24h;
    }
    
    /**
     * @return float
     */
    public function getPercentChange24h(): float
    {
        return $this->percentChange24h;
    }
    
    /**
     * @param float $percentChange24h
     */
    public function setPercentChange24h(float $percentChange24h): void
    {
        $this->percentChange24h = $percentChange24h;
    }
}
