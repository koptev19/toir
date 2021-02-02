<?php

trait PublicDataTrait
{
    /**
     * @var array
     */
    private $workshops = [];

    /**
     * @var array
     */
    private $services = [];

    /**
     * @return array
     */
    public function workshops(): array
    {
        if(!$this->workshops || count($this->workshops) == 0) {
            $this->workshops = Workshop::all();
        }

        return $this->workshops;
    }

    /**
     * @return array
     */
    public function services(): array
    {
        if(count($this->services) == 0) {
            $this->services = Service::all();
        }

        return $this->services;
    }

}