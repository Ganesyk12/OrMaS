<div class="row g-3">
  @if($access)
    <div class="col-md-6">
      <label class="form-label">Pengguna</label>
      <input type="text" class="form-control" value="{{ $access->user->name ?? '-' }}" disabled>
    </div>
    <div class="col-md-3">
      <label class="form-label">Modul</label>
      <input type="text" class="form-control" value="{{ $access->module }}" disabled>
    </div>
    <div class="col-md-3">
      <label class="form-label">Permission</label>
      <input type="text" class="form-control" value="{{ $access->permission }}" disabled>
    </div>
  @else
    <div class="col-md-12">
      <label class="form-label">Pengguna <span class="text-danger">*</span></label>
      <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
        <option value="">-- Pilih Pengguna --</option>
        @foreach($users as $user)
          <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
        @endforeach
      </select>
      @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Modul <span class="text-danger">*</span></label>
      <select name="module" class="form-select @error('module') is-invalid @enderror" required>
        <option value="">-- Pilih Modul --</option>
        @foreach(['organizations', 'members', 'roles', 'users', 'user-accesses', 'activities', 'meetings', 'agendas', 'finances', 'letters', 'letter-categories'] as $module)
          <option value="{{ $module }}" @selected(old('module') === $module)>{{ $module }}</option>
        @endforeach
      </select>
      @error('module')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Permission <span class="text-danger">*</span></label>
      <select name="permission" class="form-select @error('permission') is-invalid @enderror" required>
        <option value="">-- Pilih Permission --</option>
        @foreach(['view', 'create', 'edit', 'delete'] as $permission)
          <option value="{{ $permission }}" @selected(old('permission') === $permission)>{{ $permission }}</option>
        @endforeach
      </select>
      @error('permission')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  @endif
  <div class="col-md-12">
    <div class="form-check">
      <input type="hidden" name="is_allowed" value="0">
      <input type="checkbox" name="is_allowed" value="1" id="is_allowed" class="form-check-input" @checked(old('is_allowed', $access->is_allowed ?? true))>
      <label class="form-check-label" for="is_allowed">Diizinkan</label>
    </div>
  </div>
</div>
