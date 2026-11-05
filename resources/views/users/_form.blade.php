<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Email <span class="text-danger">*</span></label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Password {{ $user ? '' : '*' }}</label>
    <div class="input-group">
      <input type="password" name="password" id="user_password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" @required(! $user)>
      <button type="button" class="btn btn-outline-secondary" data-toggle-password="user_password" aria-label="Tampilkan password" title="Tampilkan password">
        <i class="ti ti-eye"></i>
      </button>
    </div>
    @if($user)
      <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
    @endif
    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
</div>
