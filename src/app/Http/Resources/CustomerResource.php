<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
         return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}

// Laravelのコントローラーには以下の仕組みがあります。

// コントローラーが 「Arrayable や JsonResource オブジェクト」 を return する。

// Laravel本体が「お、これはオブジェクトだな。ブラウザに返す前に JSON に変換してあげよう」と気を利かせる。

// その際、Resourceオブジェクトの toResponse() というメソッドが自動で呼ばれ、そこで初めて JSON に変換される。
