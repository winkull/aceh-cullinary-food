<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPengguna extends Model
{
    use HasFactory;
    protected $table = 'tblpengguna';
    protected $primaryKey = 'idpengguna';
}
