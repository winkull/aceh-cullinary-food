<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblToko extends Model
{
    use HasFactory;
    protected $table = 'tbltoko';
    protected $primaryKey = 'kodetoko';
}
