<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(){ return response()->json(Book::all()); }
    public function store(Request $request){
        $book = Book::create($request->only(['judul','penulis','tahun_terbit','penerbit','stok']));
        return response()->json($book, 201);
    }
    public function show($id){ return response()->json(Book::findOrFail($id)); }
    public function update(Request $request, $id){
        $book = Book::findOrFail($id);
        $book->update($request->only(['judul','penulis','tahun_terbit','penerbit','stok']));
        return response()->json($book);
    }
    public function destroy($id){
        Book::findOrFail($id)->delete();
        return response()->json(['message'=>'hapus berhasil']);
    }
}