<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dis_drink extends Model
{
    protected $fillable = ['code', 'title', 'body', 'imageUrl', 'imageDefault', 'barcodes', 'formattedPrice', 'price', 'supplementaryPriceLabel1', 'supplementaryPriceLabel2', 'shop', 'category'];
}
