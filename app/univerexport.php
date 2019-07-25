<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class univerexport extends Model
{
    protected $fillable = ['id','name', 'price_old', 'price_new', 'price_measure', 'price_reference', 'reference_measure', 'manufacturer', 'image_url', 'category','url'];
}
