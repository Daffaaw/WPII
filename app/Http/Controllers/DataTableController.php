<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DataTableController extends Controller
{
    
    public function clientside(Request $request){
        
        $data = new User;

        if($request->get('search')){
            $data = $data->where('name', 'LIKE', '%'.$request->get('search').'%')
            ->orWhere('email','LIKE', '%'.$request->get('search').'%');
        }

        $data = $data->get();
    
        return view('datatable.clientside', compact('data', 'request'));
    }

    public function serverside(Request $request){
        
        

        if($request->ajax()){
        
            $data = new User;
            $data = $data->latest();
            return DataTables::of($data)
            ->addColumn('no', function($data){
                return 'ini nomor';
            })
            ->addColumn('photo', function($data){
                return 'ini photo';
            })
            ->addColumn('nama', function($data){
                return $data->name;
            })
            ->addColumn('email', function($data){
                return $data->emial;
            })
            ->addColumn('action', function($data){
                return 'action';
            })
            ->make(true);
        }
    
        return view('datatable.serverside', compact( 'request'));
    }
}