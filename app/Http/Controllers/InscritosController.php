<?php

namespace App\Http\Controllers;

use App\Models\LiderGrupo;
use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\User;
use App\Models\Grupos;
use App\Models\Solicitud;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscritosController extends Controller
{
    public function index()
    {
        $id = Auth::id();
        $idGrupos = Grupos::where('id_user', $id)->pluck('id_grupo');
        
        /*$blogs = Blog::join('lider_grupo', 'blogs.id', '=', 'lider_grupo.id_grupo')
                     ->whereIn('lider_grupo.id_grupo', $idGrupos)
                     ->get(); */
                     
       $blogs = Blog::whereIn('id', $idGrupos)->get();            
        return view('inscritos.index', compact('blogs'));
    }
    
    
    
    
    //Pagina de admin todos los datos 
    public function solicitud()
    {
        $id = Auth::id();
        $idGrupos = Grupos::where('id_user', $id)->pluck('id_grupo');                 
       $blogs = Blog::whereIn('id', $idGrupos)->get(); 
       $solicitudes = Solicitud::whereIn('id_grupo', $idGrupos)->get();

       $grupos = DB::table('grupo_has_user')
       ->join('users', 'grupo_has_user.id_user', '=', 'users.id')
       ->join('blogs', 'grupo_has_user.id_grupo', '=', 'blogs.id')
       ->select('*')
       ->whereIn('grupo_has_user.id_grupo', $idGrupos)
       ->get();

        $id_user = 0;
        $id_grupo = 0;
        return view('inscritos.solicitud', compact('solicitudes', 'grupos', 'id_user', 'id_grupo'));

    }

    //para borrar y aceptar miembros 

    public function accept($id_user, $id_grupo, $id)
    {

        $grupoHasUser = new Grupos();
        $grupoHasUser->id_user = $id_user;
        $grupoHasUser->id_grupo = $id_grupo;
        $grupoHasUser->save();
        $this->destroy($id);
        return $this->solicitud();
    }

    public function ingresar(Request $request)
    {
        $id_user = $request->input('id_user');
        $id_grupo = $request->input('id_grupo');
        $grupoHasUser = new Grupos();
        $grupoHasUser->id_user = $id_user;
        $grupoHasUser->id_grupo = $id_grupo;
        $grupoHasUser->save();


        $solicitudes = Solicitud::all();
        $grupos = DB::table('grupo_has_user')
            ->join('users', 'grupo_has_user.id_user', '=', 'users.id')
            ->join('blogs', 'grupo_has_user.id_grupo', '=', 'blogs.id')
            ->select('*')
            ->get();

        $id_user = 0;
        $id_grupo = 0;

        return view('inscritos.solicitud', compact('solicitudes', 'grupos', 'id_user', 'id_grupo'));


    }

    public function destroy($id)
    {

        Solicitud::where('id', $id)
            ->delete();


        $solicitudes = Solicitud::all();

        $grupos = DB::table('grupo_has_user')
            ->join('users', 'grupo_has_user.id_user', '=', 'users.id')
            ->join('blogs', 'grupo_has_user.id_grupo', '=', 'blogs.id')
            ->select('*')
            ->get();

        $id_user = 0;
        $id_grupo = 0;
        return view('inscritos.solicitud', compact('solicitudes', 'grupos', 'id_user', 'id_grupo'));
    }
    public function sacar($id, $id_user)
    {
        Grupos::where('id_grupo', $id)
            ->where('id_user', $id_user)
            ->delete();

        $solicitudes = Solicitud::all();

        $grupos = DB::table('grupo_has_user')
            ->join('users', 'grupo_has_user.id_user', '=', 'users.id')
            ->join('blogs', 'grupo_has_user.id_grupo', '=', 'blogs.id')
            ->select('*')
            ->get();

        $id_user = 0;
        $id_grupo = 0;
        return view('inscritos.solicitud', compact('solicitudes', 'grupos', 'id_user', 'id_grupo'));
    }



}