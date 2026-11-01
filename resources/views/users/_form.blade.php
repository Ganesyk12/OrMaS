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
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" @required(! $user)>
    @if($user)
      <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
    @endif
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
