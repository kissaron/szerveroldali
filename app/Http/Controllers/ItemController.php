<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Label;
use App\Models\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
 
class ItemController extends Controller
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
            'items' => Item::orderBy('obtained','DESC')->paginate(6),
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
        $this->authorize('create',Item::class);
        return view('items.create',[
            'items' => Item::all(),
            'labels' => Label::all()
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
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'description' => 'required|max:255',
                'labels' => 'required|array',
                'labels.*' => 'numeric|integer|exists:labels,id',
                'image_path' => 'nullable|file|image|max:5000',
            ]
  
            
        );
        $image = null;
        if ($request->hasFile('image_path')){
            $file = $request->file('image_path');
            $image='image_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            Storage::disk('public')->put($image,$file->get());

        }
        
        $item =Item::factory()->create([
            "name" => $validated['name'],
            "description" => $validated['description'],
            "obtained" => now()->toDateString(),
            "image" => $image,
        ]);

        $item->labels()->sync($validated['labels']);
        Session::flash('item_created',$validated['name']);
        return Redirect::route('items.show',$item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('items.show', [
            'item' => $item,
           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $this->authorize('update',$item);

        return view('items.edit',[
            'item' => $item,
            'labels' => Label::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update',$item);

        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'description' => 'required|max:255',
                'labels' => 'required|array',
                'labels.*' => 'numeric|integer|exists:labels,id',
                'remove_cover_image' => 'nullable|boolean',
                'image_path' => 'nullable|file|mimes:jpg,bmp,png|max:5000',
            ]
  
            
        );
        $image = $item->image;
        $remove_cover_image = isset($validated['remove_cover_image']);
        if ($request->hasFile('image_path') && !$remove_cover_image){
            $file = $request->file('image_path');
            $image='image_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            Storage::disk('public')->put($image,$file->get());

        }
        
        if ( $remove_cover_image){
            $image= null;
        }

        /*if ($image != $item->$image && $item->image != null){
            Storage::disk('public')->delete($item->image);
        }*/

   

        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->obtained = now()->toDateString();
        $item->image = $image;
        $item->save();

        $item->labels()->sync($validated['labels']);
        Session::flash('item_updated',$validated['name']);
        return Redirect::route('items.show',$item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete',$item);
        $item->delete();
        
        Session::flash('item_deleted',$item->name);
        return Redirect::route('items.index');
    }
}
