<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $exchange
 * @property string $type
 * @property string $base
 * @property string $quote
 * @property string $base_symbol
 * @property string $quote_symbol
 */
class NomicsBinanceUsd extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nomics-binance-usd';

    /**
     * @var array
     */
    protected $fillable = ['exchange', 'type', 'base', 'quote', 'base_symbol', 'quote_symbol'];
}
