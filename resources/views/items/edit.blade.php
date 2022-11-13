@extends('layouts.app')
@section('title', 'Edit item')

@section('content')
<div class="container">
    <h1>Edit item</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{route('items.index')}}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    {{-- TODO: action, method, enctype --}}
    <form method="POST"  action="{{route('items.update',$item)}}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
   
      
        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name',$item->name)}}">
                @error('name')
                 <div class="invalid-feedback">
                     {{$message}}
                 </div>
                @enderror
            </div>
        </div>

  

        <div class="form-group row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Description*</label>
            <div class="col-sm-10">
                <textarea rows="5" class="form-control @error('description') is-invalid @enderror" id="description" name="description"> {{old('description',$item->description)}}
                </textarea>
  
                @error('description')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
               @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="categories" class="col-sm-2 col-form-label py-0">Labels</label>
            <div class="col-sm-10">
        
                @forelse ($labels as $label)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $label->id}}"
                            id="label{{$label->id}}"
                            name="labels[]"
                            @checked(
                                in_array($label->id,old('labels',
                                $item->labels->pluck('id')->toArray()))
                            )
                        >
                  
                        <label for="label{{$label->id}}" class="form-check-label">
                            <span class="badge" style="background-color:{{ $label->color }}">{{ $label->name }}</span>
                        </label>
                    </div>
                @empty
                    <p>No categories found</p>
                @endforelse
                @error('labels.*')
                <ul class="text-danger">
                    @foreach ($errors->get('labels.*') as $message)
                        <li>{{$message[0]}}</li>
                    @endforeach
                </ul>
                @enderror

            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label">Settings</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="remove_cover_image" name="remove_cover_image" @checked(old('remove_cover_image'))>
                        <label for="remove_cover_image" class="form-check-label">Remove cover image</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row mb-3" id="cover_image_section">
            <label for="image_path" class="col-sm-2 col-form-label">Cover image</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <input type="file" class="form-control-file" id="image_path" name="image_path">
                        </div>
                        <div id="cover_preview" class="col-12">
                            <p>Cover preview:</p>
                            {{-- TODO: Use attached image --}}
                            <img id="cover_preview_image" width="350px" src="{{ asset(
                                $item->image
                                ? 'storage/' . $item->image
                                : 'images/default_post_cover.jpg'
                            )
                            }}" alt="Cover preview">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @error('image')
        <small class="text-danger">
           {{$message}}
        </small>
       @enderror

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const removeCoverInput = document.querySelector('input#remove_cover_image');
    const coverImageSection = document.querySelector('#cover_image_section');
    const coverImageInput = document.querySelector('input#cover_image');
    const coverPreviewContainer = document.querySelector('#cover_preview');
    const coverPreviewImage = document.querySelector('img#cover_preview_image');
    // Render Blade to JS code:
    // TODO: Use attached image
    const defaultCover = `{{ asset('images/default_post_cover.jpg') }}`;

    removeCoverInput.onchange = event => {
        if (removeCoverInput.checked) {
            coverImageSection.classList.add('d-none');
        } else {
            coverImageSection.classList.remove('d-none');
        }
    }

    coverImageInput.onchange = event => {
        const [file] = coverImageInput.files;
        if (file) {
            coverPreviewImage.src = URL.createObjectURL(file);
        } else {
            coverPreviewImage.src = defaultCover;
        }
    }
</script>
@endsection
