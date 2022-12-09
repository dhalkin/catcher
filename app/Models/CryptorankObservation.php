<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $cryptorank_id
 * @property string $sessionTime
 * @property string $lastUpdated
 * @property string $name
 * @property string $symbol
 * @property string $type
 * @property integer $circulatingSupply
 * @property integer $totalSupply
 * @property float $price
 * @property integer $volume24h
 * @property float $percentChange24h
 * @property string $created_at
 */
class CryptorankObservation extends Model
{
    public const UPDATED_AT = null;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cryptorank_observations';
    
    protected $dateFormat = 'U';
    
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
        'percentChange24h',
        'created_at'
    ];
}
