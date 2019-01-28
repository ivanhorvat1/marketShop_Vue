<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Article extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         //return parent::toArray($request);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'barcodes' => $this->barcodes,
            'formattedPrice' => $this->formattedPrice,
            'supplementaryPriceLabel1' => $this->supplementaryPriceLabel1,
            'supplementaryPriceLabel2' => $this->supplementaryPriceLabel2,
            'shop' => $this->shop,
            'imageUrl' => $this->imageUrl,
            'imageDefault' => $this->imageDefault
        ];
    }

    public function with($request) {
        return [
            'version' => '1.0.0',
            'author_url' => url('http://traversymedia.com')
        ];
    }
}
