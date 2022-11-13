@extends('layouts.app')
@section('title', 'Create label')

@section('content')
<div class="container">
    <h1>Create label</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{route('items.index')}}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    {{-- TODO: Session flashes --}}
    @if (Session::has('label_created'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_created')}}) successfully created!
        </div>
  
    @endif
    {{-- TODO: action, method --}}
    <form method="POST" action="{{route('labels.store')}}">
        @csrf
        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name')  is-invalid @enderror" id="name" name="name" value="{{old('name')}}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message}}
                    </div>
                @enderror
            </div>
            <div class="invalid-feedback">Error message</div>
        </div>

        
        <div class="form-group row mb-3">
            <label for="color" class="col-sm-2 col-form-label">Color*</label>
            <div class="col-sm-10">
                <input type="color"  class="form-color-input @error('color') is-invalid @enderror" id="color" name="color" value="{{old('color')}}"> 
               
  
                @error('color')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
               @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="display" class="col-sm-2 col-form-label">Visible</label>
            <div class="col-sm-10">
                <input type="checkbox"  class="form-checkbox-input @error('display') is-invalid @enderror" id="display" name="display" value="{{old('display')}}"> 
               
  
                @error('display')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
               @enderror
            </div>
        </div>


        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>

    </form>
</div>
@endsection
