<?php

namespace App\Validator;

trait Commission{

    protected $rules = [
        
        "commission" => "required|numeric",
        
    ];

}
