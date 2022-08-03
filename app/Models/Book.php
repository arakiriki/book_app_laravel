<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * id:一意（autoincrement）,not null
     * name: length 255, not null,
     * status: tinyInteger(1-255), not null, 1:読書中 2:未読 3:読破 4:読みたい
     * author:著者 length 255, not ok.
     * pabulication:出版社 length 255, not ok.
     * read_at: 読み終わった日 date(yyyy-mm-dd), null ok
     * note:　メモ text, null ok
     * timestomps:updated_at,created_at
     *
     * cover_image:本の画像
     *
     */
}
