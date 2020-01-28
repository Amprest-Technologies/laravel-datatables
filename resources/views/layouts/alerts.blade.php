{{-- Display success messages --}}
@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

{{-- Display error messages --}}
@if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@endif

{{-- Display info messages --}}
@if(session('info'))
    <div class="alert alert-info" role="alert">
        {{ session('info') }}
    </div>
@endif