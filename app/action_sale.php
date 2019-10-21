<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class action_sale extends Model
{
    protected $fillable = ['code', 'title', 'body', 'imageUrl', 'imageDefault', 'barcodes', 'toDate', 'formattedPrice', 'price', 'oldPrice', 'supplementaryPriceLabel1', 'supplementaryPriceLabel2', 'shop', 'category','deleted'];
}
