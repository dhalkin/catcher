<?php

namespace App\Entity\Bot\Filter;

abstract class Filter
{
    protected int $changePrice;
    protected int $changeVolume;
    protected bool $unsigned = false;
    
    /**
     * @return int
     */
    public function getChangePrice(): int
    {
        return $this->changePrice;
    }
    
    /**
     * @return int
     */
    public function getChangeVolume(): int
    {
        return $this->changeVolume;
    }
    
    /**
     * @return bool
     */
    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }
}