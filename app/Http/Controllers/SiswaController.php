<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Validator;
class SiswaController extends Controller
{
    public function index()
    {
        try {
            return Siswa::all();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data siswa.'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $rules = [
            'nama' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['required', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
            'umur' => ['required', 'integer', 'between:6,18'],
        ];

        $messages = [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
            'kelas.regex' => 'Format kelas harus seperti "XII IPA 1".',
            'umur.between' => 'Umur harus antara 6 hingga 18 tahun.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 401);
        }

        $validated = $validator->validated();

        try {
            $siswa = Siswa::create($validated);
            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan data siswa.',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            return Siswa::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(
                ['error' => 'Siswa tidak ditemukan.'],
                404
            );
        }
    }

    public function update(Request $request, string $id)
    {


        $rules = [
            'nama' => ['sometimes', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['sometimes', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
            'umur' => ['sometimes', 'integer', 'between:6,18'],
        ];

        $messages = [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
            'kelas.regex' => 'Format kelas harus seperti "XII IPA 1".',
            'umur.between' => 'Umur harus antara 6 hingga 18 tahun.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 401);
        }

        $validated = $validator->validated();

        try {
            $siswa = Siswa::find($id);
            $siswa->update($validated);
            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal Memperbarui data siswa.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

            return response()->json('data berhasil dihapus', 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus data siswa.'
            ], 500);
        }
    }
}
