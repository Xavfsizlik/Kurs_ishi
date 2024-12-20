<x-layouts.app>

    {{-- <x-slot:title>
        Key
    </x-slot>
    @section('content')
    
    <form action="{{ route('keycreate') }}" method="POST">
        @csrf
        <select name="id" class="form-control">
            <option disabled selected>Foydalanuvchini tanlang</option>
            @foreach($users as $user)
            <option value={{ $user->id }}>{{ $user->name }}</option>
            @endforeach
        </select>
        <div style="padding:10px">
            <button type="submit" class="btn btn-primary" style="width: 300px; height: 30px;">Kalit yaratish</button>
        </div>
    </form> --}}
        <x-slot:title>Key</x-slot:title>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </head>
        <form action="{{ route('keycreate') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="search-user">Foydalanuvchini yozib qidiring:</label>
                <input type="text" id="search-user" class="form-control" placeholder="Foydalanuvchi ismini kiriting">
            </div>
            <select name="id" id="user-list" class="form-control">
                <option disabled selected>Foydalanuvchini tanlang</option>
                @foreach($users as $user)
                    <option value={{ $user->id }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <div style="padding:10px">
                <button type="submit" class="btn btn-primary" style="width: 300px; height: 30px;">Kalit yaratish</button>
            </div>
        </form>
        <script>
            document.getElementById('search-user').addEventListener('input', function () {
                const query = this.value;
                fetch(`/search-users?query=${query}`)
                    .then(response => response.json())
                    .then(users => {
                        const userList = document.getElementById('user-list');
                        userList.innerHTML = '<option disabled selected>Foydalanuvchini tanlang</option>';
                        users.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.name;
                            userList.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
    
</x-layouts.app>