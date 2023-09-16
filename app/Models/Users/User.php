<?php

namespace App\Models\Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Posts\Like;

use App\Models\Posts\Post;



use Auth;

use App\Models\Users\subject;


class User extends Authenticatable
{
    use Notifiable;
    use softDeletes;

    const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'over_name',
        'under_name',
        'over_name_kana',
        'under_name_kana',
        'mail_address',
        'sex',
        'birth_day',
        'role',
        'password'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password' , 'remember_token', 'user_id' , 'subject_users'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

/////////////////////////////////////////////////////////////////////////////////////
    public function posts(){//postsテーブルとのリレーション1対多の,1の方。
        return $this->hasMany(Post::class);
    }

    public function likes(){ //likesテーブルとのリレーション1対多の,1の方。
        return $this->hasMany(Like::class);
    }

/////////////////////////////////////////////////////////////////////////////////////

    public function calendars(){
        return $this->belongsToMany('App\Models\Calendars\Calendar', 'calendar_users', 'user_id', 'calendar_id')->withPivot('user_id', 'id');
    }

    public function reserveSettings(){
        return $this->belongsToMany('App\Models\Calendars\ReserveSettings', 'reserve_setting_users', 'user_id', 'reserve_setting_id')->withPivot('id');
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////

    //多対多のリレーションの定義(中間テーブル(subject_usersテーブル)の設定)

    public function subjects()
    {
        return $this->belongsToMany(User::class, 'subject_users', 'user_id', 'subject_id');
        //ログインユーザーのidがuser_idに入る。
        //attach($subjects)のidがsubject_idに入る。
    }



///////////////////////////////////////////////////////////////////////////////////////////////////////

    // ログインユーザーが投稿にイイネしているかどうか確認する
    public function is_Like($post_id){
        return Like::where('like_user_id', Auth::id())->where('like_post_id', $post_id)->first(['likes.id']);
        //like_user_idは,いいねした人のidのこと
        //like_post_idは,いいねした投稿のidのこと
        //渡されてきた投稿が、自分がいいねした投稿であれば、その投稿のidを取得する

        //likes.idはlikesテーブルのid
    }

    public function likePostId(){
        return Like::where('like_user_id', Auth::id());
    }
}
