<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Jika hanya admin atau superadmin yang boleh export
        if (in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return User::orderBy('id', 'desc')->get();
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Role',
            'Tanggal Registrasi',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->role,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
