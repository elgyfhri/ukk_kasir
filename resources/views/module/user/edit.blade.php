@extends('main')
@section('title', '| Edit User')

@section('content')

<div class="row">
    <form action="{{ route('user.update', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
      
        <!-- Nama User -->
        <div class="mb-3">
          <label for="name" class="form-label">Nama User</label>
          <input type="text" class="form-control border-secondary"  id="name" name="name" value="{{$item->name}}" required>
        </div>
      
        <!-- email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email User</label>
          <input type="email" class="form-control border-secondary"  id="email" name="email" value="{{$item->email}}" required>
        </div>
      
        <!-- password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password Baru User</label>
          <input type="password" class="form-control border-secondary" id="password" name="password">
        </div>

        <!-- role -->
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select">
                <option value="admin" {{ $item->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="employee" {{ $item->role == 'employee' ? 'selected' : '' }}>Employee</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>      
</div>

@endsection
