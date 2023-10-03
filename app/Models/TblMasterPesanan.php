<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMasterPesanan extends Model
{
    use HasFactory;
    protected $table = 'tblmasterpesanan';
    protected $primaryKey = 'nomorpesanan';
}
