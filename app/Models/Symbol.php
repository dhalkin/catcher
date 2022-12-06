<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $symbol
 */
class Symbol extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['symbol'];
}
