<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer' => CustomerResource::make($this->customer),
            'product1' => ProductResource::make($this->product1),
            'num1' => $this->num1,
            'product2' => ProductResource::make($this->product2),
            'num2' => $this->num2,
            'product3' => ProductResource::make($this->product3),
            'num3' => $this->num3,
            'orderday' => $this->orderday,
        ];
    }
}
/**
 * toArray() は、最終的に「どういう連想配列にするか」という**定義（レシピ）**を書く場所であって、外部から呼び出すためのメインの窓口ではないからです。

* new UserResource($user): コンストラクタにモデルを渡して、インスタンスを作ります。

* UserResource::collection($users): 内部でループを回して、複数のモデルを一つずつ UserResource に詰め込んでくれます。

* もし UserResource::toArray($users) というメソッドを自分で作って呼び出そうとすると、自前で foreach を回して配列に変換する処理を書かなければならず、Resourceクラスを使うメリット（簡潔さ）が薄れてしまいます。

* 2. ::collection() はどこから来ている？
* これは、継承元の Illuminate\Http\Resources\Json\JsonResource クラスの中で**静的メソッド（static method）**として定義されています。
 */
