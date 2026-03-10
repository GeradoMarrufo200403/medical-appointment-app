<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que se cree bien
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        //Si pasa la validacion, crear el rol
        Role::create([
            'name' => $request->name
        ]);

        //Confirmacion de operacion exitosa
        session()->flash('success',[
            'icon' => 'success',
            'title' => 'Rol creado',
            'text' => 'El rol se ha creado correctamente'
        ]);

        //Redireccionar a la tabla principal
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
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
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact($role));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //1.Definir roles
        $protectedRoles = ['Admin', 'Doctor',
         'Paciente', 'Recepcionista', 'Super admin'];

        //2.Revisar si el rol actual esta en los roles protegidos
        if(in_array($role->name, $protectedRoles)){
            session()->flash('swal',[
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminar un rol'
            ]);
            return redirect(route('admin.roles.index'));
        }
        

        //Borrar el elemento
        $role->delete();

        //Confirmacion de operacion exitosa
        session()->flash('swal,success',[
            'icon' => 'success',
            'title' => 'Rol eliminado',
            'text' => 'El rol se ha eliminado correctamente'
        ]);
    }
}
