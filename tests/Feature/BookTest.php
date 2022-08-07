<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;



class BookTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    /**
     * テスト名について
     * 接頭辞にtest_をつける
     * @testをつける。メソッド名にtestは不要
     * 日本語でも大丈夫
     * 
     * test_<url>_<証明する内容>
     */


    
     // ログインしてないユーザがbook.indexにアクセスできないこと(302)
    public function test_book_index_ng()
    {
        $response = $this->get("/book");
        $response->assertStatus(302);
    }

    // ログインユーザがbook.indexにアクセスできること(200)
    public function test_book_index_ok()
    {
        //ログインさせる場合はfactoryでuserを作りactingAsでリクエストする
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/book");
        $response->assertStatus(200);
    }

    
    // 存在するIDでbook.detailにアクセスできること(200)
    public function test_book_detail_id_exist()
    {
        //ログインさせる場合はfactoryでuserを作りactingAsでリクエストする
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $response = $this->actingAs($user)->get("/book/detail/$book->id");
        $response->assertStatus(200);
    }


    // 存在しないIDでbook.detailにアクセスできないこと
    public function test_book_detail_id_not_exist()
    {
        //ログインさせる場合はfactoryでuserを作りactingAsでリクエストする
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/book/detail/9999");
        $response->assertStatus(404);
    }

    // 存在するIDでbook.editにアクセスできること
    public function test_book_edit_id_exist()
    {
        //ログインさせる場合はfactoryでuserを作りactingAsでリクエストする
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $response = $this->actingAs($user)->get("/book/edit/$book->id");
        $response->assertStatus(200);
    }

    // 存在しないIDでbook.editにアクセスできないこと
    public function test_book_edit_id_not_exist()
    {
        //ログインさせる場合はfactoryでuserを作りactingAsでリクエストする
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/book/edit/9999");
        $response->assertStatus(404);
    }


    // book.editで更新処理が行えること
    public function test_book_update_ok()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $params = [
            'id' => $book->id,
            'name' => 'test',
            'status' => 1,
            'author' => 'test',
            'publication' => 'test',
            'read_at' => '2022-10-01',
            'note' => 'test'
        ];

        $response = $this->actingAs($user)->patch("/book/update",$params);
        $response->assertStatus(302); //httpステータスが302を返すこと
        $response->assertSessionHas('status','本を更新しました。'); //sessionにstatusが含まれており、値が一致していること
        $this->assertDatabaseHas('books',$params); //データベースの値が更新されたこと
    }


    // 不正な値でbook.editで更新処理がエラーになること
    public function test_book_update_ng()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $params = [
            'id' => $book->id,
            'name' => 'test',
            'status' => 9, //不正な値
            'author' => 'test',
            'publication' => 'test',
            'read_at' => '2022-10-01',
            'note' => 'test'
        ];

        $response = $this->actingAs($user)->patch("/book/update",$params);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['status'=>'選択されたステータスは、有効ではありません。']); //エラーセッションに値が含まれること
        $this->assertDatabaseMissing('books',$params); //データベースの値が更新されたこと
    }

        // 不正な値でbook.editで更新処理がエラーになること（複数）
        public function test_book_update_ng_all()
        {
            $user = User::factory()->create();
            $book = Book::factory()->create();
    
            $params = [
                'id' => $book->id,
                //下記全て不正な値
                'name' => $this->faker->realText(256),
                'status' => 9, 
                'author' => $this->faker->realText(256),
                'publication' => $this->faker->realText(256),
                'read_at' => '2022-10-01xxxx',
                'note' => $this->faker->realText(1001),
            ];
    
            $response = $this->actingAs($user)->patch("/book/update",$params);
            $response->assertStatus(302);
            $this->assertDatabaseMissing('books',$params); //データベースの値が更新されたこと

            /**
             * error message
             * name:名前は、255文字以下にしてください。
             * status:選択されたステータスは、有効ではありません。
             * author:著者は、255文字以下にしてください。
             * publication:出版は、255文字以下にしてください。
             * read_at:読破日は、正しい日付ではありません。
             * note:メモは、1000文字以下にしてください。
             */

            $response->assertInvalid(['name'=>'名前は、255文字以下にしてください。']);
            $response->assertInvalid(['status'=>'選択されたステータスは、有効ではありません。']);
            $response->assertInvalid(['author'=>'著者は、255文字以下にしてください。']);
            $response->assertInvalid(['publication'=>'出版は、255文字以下にしてください。']);
            $response->assertInvalid(['read_at'=>'読破日は、正しい日付ではありません。']);
            $response->assertInvalid(['note'=>'メモは、1000文字以下にしてください。']);

        }

    // book.newで更新処理が行えること
    public function test_book_create_ok()
    {
        $user = User::factory()->create();

        $params = [
            'name' => 'test',
            'status' => 1,
            'author' => 'test',
            'publication' => 'test',
            'read_at' => '2022-10-01',
            'note' => 'test'
        ];

        $response = $this->actingAs($user)->post("/book/create",$params);
        $response->assertStatus(302); //httpステータスが302を返すこと
        $response->assertSessionHas('status','本を作成しました。'); //sessionにstatusが含まれており、値が一致していること
        $this->assertDatabaseHas('books',$params); //データベースの値が更新されたこと
    }


    // 不正な値でbook.newで更新処理がエラーになること
    public function test_book_create_ng_all()
    {
        $user = User::factory()->create();

        $params = [
            //全て不正な値
            'name' => $this->faker->realText(256),
            'status' => 9, 
            'author' => $this->faker->realText(256),
            'publication' => $this->faker->realText(256),
            'read_at' => '2022-10-01xxxx',
            'note' => $this->faker->realText(1001),
        ];

        $response = $this->actingAs($user)->post("/book/create",$params);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('books',$params); //データベースの値が更新されたこと

        /**
         * error message
         * name:名前は、255文字以下にしてください。
         * status:選択されたステータスは、有効ではありません。
         * author:著者は、255文字以下にしてください。
         * publication:出版は、255文字以下にしてください。
         * read_at:読破日は、正しい日付ではありません。
         * note:メモは、1000文字以下にしてください。
         */

        $response->assertInvalid(['name'=>'名前は、255文字以下にしてください。']);
        $response->assertInvalid(['status'=>'選択されたステータスは、有効ではありません。']);
        $response->assertInvalid(['author'=>'著者は、255文字以下にしてください。']);
        $response->assertInvalid(['publication'=>'出版は、255文字以下にしてください。']);
        $response->assertInvalid(['read_at'=>'読破日は、正しい日付ではありません。']);
        $response->assertInvalid(['note'=>'メモは、1000文字以下にしてください。']);

    }

    // book.removeで更新処理が行えること
    public function test_book_remove_ok()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)->delete("/book/remove/$book->id");
        $response->assertStatus(302); //httpステータスが302を返すこと
        $response->assertSessionHas('status','本を削除しました。'); //sessionにstatusが含まれており、値が一致していること
        $book = Book::find($book->id);
        $this->assertEmpty($book); //NOTE: $thisはUnitTestの関数
    }

    // 不正な値でbook.removeで更新処理がエラーになること
    public function test_book_remove_ng()
    {
        //ログインさせる場合はfactoryでuserを作りactingAsでリクエストする
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete("/book/remove/9999");
        $response->assertStatus(404);
    }


    // 検索が正常に行えること
    
}
