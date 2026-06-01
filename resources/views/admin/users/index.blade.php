@extends('layouts.app')

@section('title', 'إدارة المستخدمين — CineReview')

@section('content')
    <h2 class="mb-4"><i class="bi bi-people text-warning"></i> إدارة المستخدمين</h2>
    @include('admin.partials.nav', ['active' => 'users'])

    @error('role')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="bg-dark-2 rounded p-3">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد</th>
                        <th>المراجعات</th>
                        <th>الدور</th>
                        <th class="text-end">تغيير الدور</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                {{ $user->name }}
                                @if ($user->id === auth()->id())
                                    <span class="badge bg-warning text-dark">أنت</span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $user->email }}</td>
                            <td>{{ $user->reviews_count }}</td>
                            <td>
                                @php
                                    $colors = ['admin' => 'danger', 'editor' => 'info', 'user' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $colors[$user->role] ?? 'secondary' }}">{{ $user->roleLabel() }}</span>
                            </td>
                            <td class="text-end" style="max-width: 220px;">
                                @if ($user->id === auth()->id())
                                    <span class="text-secondary small">—</span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="d-flex gap-2 justify-content-end">
                                        @csrf @method('PUT')
                                        <select name="role" class="form-select form-select-sm bg-dark text-light border-secondary" style="width:auto;">
                                            @foreach (\App\Models\User::ROLES as $value => $label)
                                                <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-warning"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">{{ $users->links() }}</div>
@endsection
