<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('stats', function () {
            return [
                'total_users' => User::count(),
                'total_posts' => Post::count(),
                'users_without_posts' => User::doesntHave('posts')->count()
            ];
        });
    }
}
