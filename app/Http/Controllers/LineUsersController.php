<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\LineUser;

class LineUsersController extends Controller
{
    public function index()
    {
        return view('admin.lineusers');
    }

    public function getLineUsers(Request $request)
    {
        $filter = $request->query('filter', 'month');

        $query = LineUser::select('user_id', 'display_name', 'picture_url', 'created_at');

        if ($filter === 'month') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($filter === '3months') {
            $query->where('created_at', '>=', now()->subMonths(3));
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return response()->json($users);
    }
}
