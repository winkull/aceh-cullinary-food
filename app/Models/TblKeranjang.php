<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblKeranjang extends Model
{
    use HasFactory;
    protected $table = 'tblkeranjang';
    protected $primaryKey = 'nokeranjang';
}
