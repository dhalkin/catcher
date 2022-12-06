<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $date_time
 * @property string $symbol
 * @property string $result
 * @property integer $created_at
 */
class CryptorankResults extends Model
{
    public const UPDATED_AT = null;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cryptorank_results';
    /**
     * @var array
     */
    protected $fillable = ['date_time', 'symbol', 'result', 'created_at'];
    
    /**
     * @return string
     */
    public function getDateTime(): string
    {
        return $this->date_time;
    }
    
    /**
     * @param string $date_time
     */
    public function setDateTime(string $date_time): void
    {
        $this->date_time = $date_time;
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
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }
    
    /**
     * @param string $result
     */
    public function setResult(string $result): void
    {
        $this->result = $result;
    }
    
}

