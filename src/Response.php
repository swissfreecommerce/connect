<?php

namespace SwissFreeCommerce\Connect;

class Response
{
    public function __construct(public string $body, public int $status)
    {
    }
}
