<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Dekor;
use App\Models\KategoriDekor;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DekorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dekors = Dekor::where('user_id', $user->id)->latest()->paginate(5);

        return view('admin.dekor.index', compact('dekors'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pakets = Paket::all();
        $kategoriDekor = KategoriDekor::all();
        return view('admin.dekor.create', compact('pakets', 'kategoriDekor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori_dekor,id',
            'tema' => 'nullable|string',
            'gaya' => 'nullable|string',
            'warna' => 'nullable|string',
            'gambar' => 'required||mimes:jpeg,png,jpg,gif,svg|max:5048',
            'harga' => 'required',
            'deskripsi' => 'required',
            'ga'
        ]);

        $data = $request->all();
        $data['user_id'] = $user_id;
        $data['slug'] = Str::random(20);

        if ($request->hasFile('gambar')) { 
            $gambar = $request->file('gambar');
            $destinationPath = 'img/admin/gambarDekor/';
            $profileGambar = date('YmdHis') . "." . $gambar->getClientOriginalExtension();
            $gambar->move(public_path($destinationPath), $profileGambar); 
            $data['gambar'] =  $profileGambar;
        } else {
            return redirect()->back()->withInput()->withErrors(['gambar' => 'The gambar field is required.']);
        }

        Dekor::create($data);

        return redirect()->route('dekor.index')
            ->with('success', 'dekor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dekor $dekor)
    {
        return view('admin.dekor.show', compact('dekor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dekor $dekor)
    {
        $pakets = Paket::all();
        $kategoriDekor = KategoriDekor::all();
        return view('admin.dekor.edit', compact('dekor', 'pakets', 'kategoriDekor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dekor $dekor)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori_dekor,id',
            'tema' => 'nullable|string',
            'gaya' => 'nullable|string',
            'warna' => 'nullable|string',
            'gambar' => 'nullable||mimes:jpeg,png,jpg,gif,svg|max:5048',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);

        $input = $request->all();
        $input['user_id'] = $user_id;
        $input['slug'] = Str::random(20);

        if ($request->hasFile('gambar')) {

            if ($dekor->gambar) {
                $oldImagePath = public_path('img/admin/gambarDekor/' . $dekor->gambar);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $gambar = $request->file('gambar');
            $destinationPath = 'img/admin/gambarDekor/';
            $profileGambar = date('YmdHis') . "." . $gambar->getClientOriginalExtension();
            $gambar->move(public_path($destinationPath), $profileGambar);
            $input['gambar'] = "$profileGambar";
        } else {
            unset($input['gambar']);
        }

        $dekor->update($input);

        return redirect()->route('dekor.index')
            ->with('success', 'dekor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dekor $dekor)
    {
        if ($dekor->gambar) {
            $imagePath = public_path('img/admin/gambarDekor/' . $dekor->gambar);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $dekor->delete();

        return redirect()->route('dekor.index')
            ->with('success', 'dekor deleted successfully');
    }
}
