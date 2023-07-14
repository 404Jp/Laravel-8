<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\User;
use App\Models\Grupos;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LiderGrupo;

class BlogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-blog|crear-blog|editar-blog|borrar-blog')->only('index');
        $this->middleware('permission:crear-blog', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-blog', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-blog', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $idGrupo = DB::table('lider_grupo')->where('id_lider', $id)->pluck('id_grupo');
        $blogs = Blog::all();
        $solicitudes = Solicitud::all();

        $id_user = 0;
        $id_grupo = 0;
        return view('blogs.index', compact('blogs', 'solicitudes', 'id_user', 'id_grupo'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lideres = DB::table('lider_grupo')->get();
        return view('blogs.crear', compact('lideres'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
         $request->validate([
             'titulo' => 'required',
             'contenido' => 'required',
             'lugar' => 'required',
             'imagen' => 'required|image',
             'lider' => 'required',
             'horario' => 'required',
         ]);
     
         $imagen = $request->file('imagen');
         $imagenNombre = $imagen->getClientOriginalName();
     
         $blog = new Blog;
         $blog->titulo = $request->input('titulo');
         $blog->contenido = $request->input('contenido');
         $blog->lugar = $request->input('lugar');
         $blog->imagen_nombre = $imagenNombre;
         $blog->lider = $request->input('lider');
         $blog->horario = $request->input('horario');
         $blog->save();
         
         // Mueve la imagen a la ubicación deseada
         $imagen->move(public_path('imagenes'), $imagenNombre);
         
        // Obtener el id_lider correspondiente al líder seleccionado
$lider = LiderGrupo::where('nombre_lider', $request->input('lider'))->first();

// Crear una nueva entrada en la tabla lider_grupo con el id_grupo, el id_lider y el nombre_lider
if ($lider) {
    $liderGrupo = new LiderGrupo;
    $liderGrupo->id_grupo = $blog->id;
    $liderGrupo->id_lider = $lider->id_lider;
    $liderGrupo->nombre_lider = $lider->nombre_lider; // Agregar el nombre del líder
    $liderGrupo->save();
}
     
         return redirect()->route('blogs.index');
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
    public function edit(Blog $blog)
    {
        $lideres = DB::table('lider_grupo')->get();
        
        return view('blogs.editar', compact('blog', 'lideres'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'titulo' => 'required',
            'contenido' => 'required',
            'lugar' => 'required',
            'lider' => 'required',
            'horario' => 'required',
        ]);
    
        // Actualizar los datos del grupo
        $blog->titulo = $request->input('titulo');
        $blog->contenido = $request->input('contenido');
        $blog->lugar = $request->input('lugar');
        $blog->lider = $request->input('lider');
        $blog->horario = $request->input('horario');
        $blog->save();
      $nombreLider =  $request->input('lider');
      $lideres = LiderGrupo::all();
        $idLider = null; 
        foreach ($lideres as $lider) {
            if ($lider->nombre_lider === $nombreLider) {
                $idLider = $lider->id_lider;
                break;
            }
        }
    
        // Verificar si se encontró el líder y asignar los valores a las variables correspondientes
        if ($idLider !== null) {
            $nombreLiderV = $nombreLider; // Asignar el nombre del líder a una variable
            $idLiderV = $idLider; // Asignar el ID del líder a una variable
        } else {
            // Manejar el caso en el que no se encuentre el líder seleccionado
           
            $nombreLiderV = "Líder no encontrado";
            $idLiderV = -1;
        }
        $idGrupoED = $blog->id;
        LiderGrupo::where('id_grupo', $idGrupoED)->delete();
        $liderNuevo = new LiderGrupo;
        $liderNuevo->nombre_lider = $request->input('lider');
        $liderNuevo->id_grupo = $idGrupoED;
        $liderNuevo->id_lider = $idLiderV;
        $liderNuevo->save();


        
        return redirect()->route('blogs.index');
    }
        


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('blogs.index');
    }
}