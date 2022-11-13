<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('items.index',[
            'users_count' => User::count(),
            'items' => Item::all(),
            'labels' => Label::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('labels.create',
        [
            'labels' => Label::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'color' => 'required',
            #'display'=> 'nullable'
            ]);

        #$validated['display'] = false;
        #$validated['color'] =  fake()->safeHexColor();
        #Label::factory()->create($validated);

     /*   if ($validated['display'] == null){
            $validated['display'] = false;
         }*/

        $label = new Label();
        $label->name= $validated['name'];
        $label->display= $request->has('display');
        $label->color=$validated['color'];
        $label->save();


        Session::flash('label_created',$validated['name']);

        return Redirect::route('items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return view('labels.show', [
            'label' => $label,
            'labels' => Label::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        $this->authorize('update',$label);

        return view('labels.edit',[
            
            'label' =>$label,
            'labels' => Label::all()
        ]);
    }

    /**
     * Update the specified resource in íorage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        $this->authorize('update',$label);

        $validated = $request->validate([
            'name' => 'required|min:3',
           
            'color' => 'required'
            ]);

        
        #$validated['color'] =  fake()->safeHexColor();
        

        $label->name= $validated['name'];
        $label->display= $request->has('display');
        $label->color=$validated['color'];
        $label->save();

        Session::flash('label_edited',$validated['name']);

        return Redirect::route('items.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete',$label);
        $label->delete();
        
        Session::flash('label_deleted',$label->name);
        return Redirect::route('items.index');
    }
}
