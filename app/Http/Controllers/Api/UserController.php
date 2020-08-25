<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        \Log::info('这是测试吧开始');
        sleep(1);
        \Log::info('这是测试吧结束');
        return 'hello world';
    }
}