<?php

namespace App\Service;

class Helper
{
    public function getZones(){
        return [
            'Madrid'=>'Madrid',
            'Mexico' => 'Mexico City',
            'UK' => 'United Kingdom'
        ];
    }
    public function getRoles(){
        return [
            'Auditor' => 'ROLE_AUDITOR',
        ];
    }
}