<x-layouts.app>

<x-slot:title>
    Cipher
</x-slot>

<h1>Sizda qaysi algoritmlar bor?</h1>
<form action="{{ route('cipher') }}" method="POST">
    @csrf
    @foreach ($ciphers as $cipher)
        <div>
            <input type="checkbox" 
                   name="ciphers[]" 
                   value="{{ $cipher->id }}" 
                   {{ auth()->user()->ciphers->contains($cipher->id) ? 'checked' : '' }}>
            <label>{{ $cipher->name }}</label>
        </div>
    @endforeach
    <button type="submit" class="btn btn-primary" style="width: 100px">Tanlash</button>
    @error('ciphers')
        <p class="help-block text-danger">{{ $message }}</p>
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</form>

</x-layouts.app>