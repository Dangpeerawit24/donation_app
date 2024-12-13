<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $Results = DB::table('categories as c')
            ->selectRaw('c.id, c.name, COUNT(camp.id) AS total_campaigns, COALESCE(SUM(COALESCE(trans.total_value, 0) * COALESCE(camp.price, 0)), 0) AS total_value_price')
            ->leftJoin('campaigns as camp', 'c.id', '=', 'camp.categoriesID')
            ->leftJoinSub(
                DB::table('campaign_transactions')
                    ->selectRaw('campaignsid, SUM(value) AS total_value')
                    ->groupBy('campaignsid'),
                'trans',
                'camp.id',
                '=',
                'trans.campaignsid'
            )
            ->groupBy('c.id', 'c.name')
            ->get();


        if (Auth::user()->type === 'admin') {
            return view('admin.categories', compact('Results'));
        } elseif (Auth::user()->type === 'manager') {
            return view('manager.categories', compact('Results'));
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $data = $request->all();

        Category::create($data);

        return redirect()->back()->with('success', 'เพิ่มข้อมูล หัวข้อกองบุญ เรียบร้อยแล้ว.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        
        $category->update([
            'name' => $validated['name'],
        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อมูล หัวข้อกองบุญ เรียบร้อยแล้ว.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว.');
    }
}
