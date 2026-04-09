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
            'name' => $request->name,
            'guard_name' => 'web',
            'is_system' => false,
        ]);

        //Confirmacion de operacion exitosa
        session()->flash('swal',[
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

        //Validacion a nivel BD
        if($role->is_system){
            session()->flash('swal',[
                'icon' => 'error',
                'title' => 'Accion denegada',
                'text' => 'No puedes editar un rol reservado del sistema.'
            ]);
            return redirect(route('admin.roles.index'));
        }
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //Validacion a nivel BD
        if($role->is_system){
            return redirect(route('admin.roles.index'));
        }
        //Validar que se actualice bien y que excluya la fila que se edita
        $request->validate([ 
            'name' => 'required|unique:roles,name,'. $role->id,
        ]);
        //Si pasa la validacion, actualizar el rol
        $role->update([
            'name' => $request->name
        ]);
        //Confirmacion de operacion exitosa
        session()->flash('swal',[   
            'icon' => 'success',
            'title' => 'Rol actualizado correctamente',
            'text' => 'El rol se ha modificado correctamente'
        ]);
        //Redireccionar a la misma vista de editar
        return redirect(route('admin.roles.edit', $role));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //Validacion a nivel BD
        if($role->is_system){
            session()->flash('swal',[
                'icon' => 'error',
                'title' => 'Accion denegada',
                'text' => 'No puedes eliminar un rol reservado del sistema.'
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

        //Redireccion
        return redirect(route('admin.roles.index'));
    }
}
