<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class BelajarController extends Controller
{
    public function cache(Request $request){
        
        $data = Cache::remember('users', now()->addMinutes(5), function(){
            return User::get();
        });

        return view('belajar.cache', compact('data'));
    }

    public function import(Request $request){
        return view('import');
    }

    public function import_proses(Request $request){
        dd($request->all());
    }

    public function enkripsi(Request $request){

        $string = 'Saya suka main Stardew Valley';
        $enkripsi = Crypt::encryptString('Saya suka main Stardew Valley');
        $dekripsi = Crypt::decryptString($enkripsi);

        echo "String Asli    :" . $string . "<br><br>";
        echo "Hasil Enkripsi :" . $enkripsi . "<br><br>";
        echo "Hasil Dekripsi :" . $dekripsi . "<br><br>";

        $params = [
            'nama' => 'Lugia',
            'generasi' => 'Pokemon Gen 2',
            'tipe' => 'Psychic Flying',
        ];

        $params = Crypt::encrypt($params);

        echo "<a href=" . route('enkripsi-detail', ['params' => $params]) . "> Lihat detail</a>";
    }

    public function enkripsi_detail(Request $request, $params){
        $params = Crypt::decrypt($params);
        
        dd($params);
    }
}
