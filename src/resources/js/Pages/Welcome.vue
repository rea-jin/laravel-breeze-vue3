<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
});
</script>
<!-- $request->user() → 現在ログイン中のユーザーを返すメソッド（Auth::user() と同じ） -->
 /**
    * ルート定義の中で、'/' にアクセスしたときに Welcome コンポーネントを表示するように設定しています。
    * HandleInertiaRequests.php:34-36 の share() メソッドで定義されています。
    * 'auth' => [
    *   'user' => $request->user(),
    * ],
    * share() に書いたデータは全ページ共通で自動的に $page.props に渡される仕組みです。
    * ログイン中なら user にユーザー情報が入り、未ログインなら null になります。
 */
<template>
    <div>
    <Head title="商品登録マスター" />
    <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-10 max-w-md w-full text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">商品登録マスター</h1>
            <p class="text-gray-500 mb-8">商品の登録・管理を行うシステムです。</p>
            <!-- canLoginは常にtrue -->
            <div v-if="canLogin" class="flex flex-col gap-3">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('products.index')"
                    class="w-full px-6 py-3 bg-indigo-500 text-white rounded-md font-semibold hover:bg-indigo-600 transition"
                >
                    商品一覧へ
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="w-full px-6 py-3 bg-indigo-500 text-white rounded-md font-semibold hover:bg-indigo-600 transition"
                    >
                        ログイン
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="w-full px-6 py-3 bg-white text-indigo-500 border border-indigo-500 rounded-md font-semibold hover:bg-indigo-50 transition"
                    >
                        新規登録
                    </Link>
                </template>
            </div>
        </div>
    </div>
    </div>
</template>
