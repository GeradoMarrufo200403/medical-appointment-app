<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.insurances.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.insurances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:insurances,name|max:255',
            'policy_details' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        Insurance::create($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Seguro Creado',
            'text' => 'El seguro ha sido registrado correctamente.'
        ]);

        return redirect()->route('admin.insurances.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        // Generally not used if we only have index, create, edit
        return view('admin.insurances.show', compact('insurance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        return view('admin.insurances.edit', compact('insurance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insurance $insurance)
    {
        $request->validate([
            'name' => 'required|max:255|unique:insurances,name,' . $insurance->id,
            'policy_details' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $insurance->update($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Seguro Actualizado',
            'text' => 'El seguro ha sido actualizado correctamente.'
        ]);

        return redirect()->route('admin.insurances.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Seguro Eliminado',
            'text' => 'El seguro ha sido eliminado correctamente.'
        ]);

        return redirect()->route('admin.insurances.index');
    }
}
