<x-layouts.app> 
    <x-slot:title>
        Welcome
    </x-slot>
    <div>
        <h1>Bosh sahifa</h1>
        <p>{{ session("xato") ?? 'Bizning loyiha' }}</p>
    </div>

</x-layouts.app>