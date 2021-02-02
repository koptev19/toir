<?php

class Receiving extends ToirModel
{

    public $table = "receivings";

    /**
     * @return string
     */
    public function smena(): string
    {
        $smena = $this->NAME;
        if(strpos($smena, "[") > 0) {
            $smena = substr($smena, strpos($smena, "[") + 1);
            $smena = substr($smena, 0, -1);
        }
        return $smena;
    }

    /**
     * @return ServiceRequest|null
     */
    public function serviceRequest(): ?ServiceRequest
    {
        return ServiceRequest::find($this->SERVICE_REQUEST_ID);
    }

}