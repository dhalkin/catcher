<?php

namespace App\Entity\Bot\Filter;

abstract class Filter
{
    protected int $changePrice;
    protected int $changeVolume;
    protected int $changeCirculation;
    
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
     * @return int
     */
    public function getChangeCirculation(): int
    {
        return $this->changeCirculation;
    }
}
