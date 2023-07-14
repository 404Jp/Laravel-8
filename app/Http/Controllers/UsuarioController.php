<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//agregamos lo siguiente
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Blog;
use App\Models\LiderGrupo;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    
        $usuarios = User::paginate(5);
        $lideres = LiderGrupo::all();
        return view('usuarios.index', compact('usuarios','lideres'));

    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('usuarios.crear', compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('usuarios.index');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('usuarios.editar', compact('user', 'roles', 'userRole'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        $user->activo = $request->input('activo');
        $user->save();

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('usuarios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.index');
    }

    public function asignarlider()
    {   $id_user = 0;
        $id_grupo = 0;
        $lideres = LiderGrupo::all();
        $grupos = Blog::all();
        return view('usuarios.asignarlider', compact('lideres', 'grupos','id_user','id_grupo'));
    }
    public function agregarlider(Request $request)
{
    $id_user = $request->input('id');
    $nombre_lider = $request->input('nombre_lider');

    // Verificar si el usuario ya está en la tabla lider_grupo
    $existente = LiderGrupo::where('id_lider', $id_user)->exists();

    if (!$existente) {
        // Agregar al usuario a la tabla lider_grupo
        $liderGrupo = new LiderGrupo();
        $liderGrupo->id_lider = $id_user;
        $liderGrupo->nombre_lider = $nombre_lider;
        $liderGrupo->save();
    }

    // Redirigir al listado de usuarios
    return redirect()->route('usuarios.index');
}

    public function asignar(Request $request)
    {
        $grupoHasUser = new LiderGrupo();
        $grupoHasUser->id_lider =  $request->input('id_user');
        $grupoHasUser->id_grupo =  $request->input('id_grupo');
        $grupoHasUser->nombre_lider = $request->input('nombre_lider');
        $grupoHasUser->save();
    
        return $this->asignarlider(); 
    }
    public function borrar(Request $request)
    {
        $id = $request->input('id');
    
        $grupoHasUser = LiderGrupo::find($id);
    
        if ($grupoHasUser) {
            $grupoHasUser->delete();
        }
    
       
      
        return redirect()->route('usuarios.index');

    }
    

    

}