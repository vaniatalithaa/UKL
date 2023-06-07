<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contact = Contact::query(); // mendefinisikan variabel $contact

        if ($request->filled('search')) { // jika inputan search ada isinya maka akan dieksekusi
            $contact->where('name', 'like', '%' . $request->search . '%'); // query pencarian data sesuai nama
        }
    
        return view('contacts.index', [
            'contacts' => $contact->latest()->simplePaginate(5),
        ]);
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'min:5'], // validasi kolom `name` harus diisi dan minimal 5 karakter
            'phone_number' => ['required'], // validasi kolom `phone_number` harus di isi dan berupa angka
        ]);
    
        $avatar = null; // mendefinisikan variabel avatar dengan nilai `null`
    
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store(); // override / mengganti nilai variabel `$avatar` dengan path hasil upload
        }
    
        // Menyimpan data ke dalam database

        Contact::create([
            'user_id' => auth()->id(), // menyimpan id dari user yang sedang login
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'avatar' => $avatar,
            'gender' => $request->gender,
        ]);

        return to_route('contacts.index');
    
        
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
    public function edit(string $id)
    {
        $contact = Contact::find($id);

        return view('contacts.edit', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => ['required', 'min:5'], // validasi kolom `name` harus diisi dan minimal 5 karakter
            'phone_number' => ['required'], // validasi kolom `phone_number` harus di isi dan berupa angka
        ]);

        $contact = Contact::find($id); // Mencari data kontak berdasarkan id

        $avatar = $contact->avatar; // mendefinisikan variabel avatar dengan nilai dari data kolom avatar pada `contacts`
    
        if ($request->hasFile('avatar')) { // mengecek jika pada request terdapat inputan berupa file dengan nama `avatar`
            $avatar = $request->file('avatar')->store(); // override / mengganti nilai variabel `$avatar` dengan path hasil upload
    }

    $contact->update([

        'name' => $request->name,
        'judul buku' => $request->phone_number,
        'gambar buku' => $avatar,
        'gender' => $request->gender,
    ]);

    $contact->save();

    return to_route('contacts.index'); // redirect ke index
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id); // mencari data kontak berdasarkan id
        $contact->delete(); // menghapus data kontak sesuai data yang ditemukan tadi

        
    }

}
