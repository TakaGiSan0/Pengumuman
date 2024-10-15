<?php

namespace App\Http\Controllers;

use App\Models\news;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = DB::table('news')->get();
        return view('user.user', compact('news'));
    }

    public function admin()
    {
        $news = DB::table('news')->get();
        return view('user.admin', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx,jpeg,mp4,avi,mkv,mov,wmv,flv,webm,ogg,3gp,3g2,|max:50048',
        ], [
            'file.max' => 'Ukuran file tidak boleh lebih dari 50MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('uploads', 'public');

        news::create([
            'file' => $file->getClientOriginalName(),
            'file_path' => $filePath,
        ]);

        // Update timestamp
        Cache::put('last_update', now());

        // Mengembalikan respons, misalnya redirect atau JSON response
        return redirect()->route('user.admin')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view("user.admin");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function lastUpdated()
    {
        $latestUpdate = \App\Models\news::latest('updated_at')->first()->updated_at ?? now();
        return response()->json(['last_updated' => $latestUpdate]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $file = news::findOrFail($id);

        // Hapus file
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        // Update timestamp
        Cache::put('last_update', now());

        return redirect()->route('user.admin')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
