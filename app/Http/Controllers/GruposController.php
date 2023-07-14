<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Grupos;
use Illuminate\Support\Facades\Auth;
use App\Models\Solicitud;
class GruposController extends Controller
{
    public function index()
    {  $user = User::all();
        $blogs = Blog::all();
        return view('grupos.index', compact('blogs','user'));
    }
    public function ingreso()
    { $id = Auth::id();
        $user = User::find($id);
        $blogs = Blog::all();
        if (! Auth::check()) { 
            return redirect('/login');
        }
    
        return view('grupos.ingreso',compact('user','blogs'));
    }
    public function guardar(Request $request)
    {
        $id_user = $request->input('id_user');
        $id_grupo = $request->input('id_grupo');
        $name_user = $request->input('name_user');
        $email = $request->input('email');
        $name_grupo = Blog::find($id_grupo)->titulo;
           
        $solicitud = new Solicitud();
        $solicitud->id_user = $id_user;
        $solicitud->id_grupo = $id_grupo;
        $solicitud->name_user = $name_user;
        $solicitud->name_grupo = $name_grupo;
        $solicitud->email = $email;
        $solicitud->save();
    
        // Mensaje de éxito
        $success_message = "La solicitud se envió correctamente.";
    
        return redirect()->route('home')->with('success_message', $success_message);
    }
    
  

    }

