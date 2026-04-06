app側でphp-fpmがあるのでphp artisanつかえる。
portでviteのポートをマウントしてるので、こちらでnpm run devをする。
server側はnginx（Webサーバー）なので、基本的に何もする必要はない。


Resource（API Resource）
php artisan make:resource UserResource
何をするか
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
👉 モデルをAPI用の形に整形する

DTOとの違い
DTO（Data Transfer Object）
👉 データの受け渡し用（型安全）

class UserDTO {
    public function __construct(
        public int $id,
        public string $name
    ) {}
}
役割
Controller → Service
Service → Repository
👉 内部レイヤー間のデータ運搬


Resource
👉 外に出すための整形
APIレスポンス
JSON形式
👉 外部向け

Repositoryとの違い
Repository
👉 データ取得ロジックを隠蔽

class UserRepository {
    public function find($id) {
        return User::find($id);
    }
}

レイヤー構造で見るとこう
[ Controller ]
     ↓
[ Service ]
     ↓
[ Repository ] → DB
     ↓
[ Model ]
     ↓
[ Resource ] ← ★ここ（外に出す直前）
     ↓
[ JSON Response ]
イメージで整理
概念	役割
DTO	内部データ運搬
Repository	データ取得
Resource	出力整形
