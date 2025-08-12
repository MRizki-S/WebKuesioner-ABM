<?php

namespace App\Http\Controllers\Admin;

use App\Models\Survey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CategorySurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::withCount('questions') // hitung total pertanyaan
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($surveys);

        return view('survey-admin.kategori-survei.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('survey-admin.kategori-survei.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'intro_schema' => 'required|array',
            'intro_schema.*.name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_]+$/'],
            'intro_schema.*.label' => 'required|string|max:255',
            'intro_schema.*.type' => 'required|in:text,select',
            'intro_schema.*.options' => 'nullable|string', // options dikirim sebagai string koma jika select
        ], [
            'intro_schema.*.name.regex' => 'Nama field intro_schema hanya boleh mengandung huruf, angka, dan garis bawah.',
        ]);
        // dd($request->all());
        $processedIntroSchema = collect($request->input('intro_schema'))->map(function ($item) {
            if ($item['type'] === 'select' && !empty($item['options'])) {
                // Pecah string options jadi array dan trim tiap elemen
                $options = array_map('trim', explode(',', $item['options']));
                $item['options'] = $options;
            } else {
                // Jika type bukan select, hapus key options supaya JSON bersih
                unset($item['options']);
            }
            return $item;
        })->toArray();

        $survey = Survey::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'intro_schema' => json_encode($processedIntroSchema),
        ]);

        return redirect()->route('survei.index')->with('success', 'Survey berhasil dibuat!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $survey = Survey::where('slug', $slug)->firstOrFail();
        return view('survey-admin.kategori-survei.edit-survei', compact('survey'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => 'required|string',
            'intro_schema' => 'required|array',
            'intro_schema.*.name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_]+$/'],
            'intro_schema.*.label' => 'required|string|max:255',
            'intro_schema.*.type' => 'required|in:text,select',
            'intro_schema.*.options' => 'nullable|string',
        ], [
            'intro_schema.*.name.regex' => 'Nama field intro_schema hanya boleh mengandung huruf, angka, dan garis bawah.',
        ]);


        $survey = Survey::findOrFail($id);

        $processedIntroSchema = collect($request->input('intro_schema'))->map(function ($item) {
            if ($item['type'] === 'select' && !empty($item['options'])) {
                $options = array_map('trim', explode(',', $item['options']));
                $item['options'] = $options;
            } else {
                unset($item['options']);
            }
            return $item;
        })->toArray();

        $survey->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'intro_schema' => json_encode($processedIntroSchema),
        ]);

        return redirect()->route('survei.index')->with('success', 'Survei berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();

        Session::flash('success', 'Survei berhasil dihapus!');

        return redirect()->route('survei.index');
    }
}
