<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
    'client_name', 'client_email', 'items', 'subtotal', 'tax', 'total'
];
//
}
