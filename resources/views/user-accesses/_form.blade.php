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
      <option value="{{ $user->id }}" @selected(old('user_id')==$user->id)>{{ $user->name }} ({{ $user->email }})</option>
      @endforeach
    </select>
    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Modul <span class="text-danger">*</span></label>
    <select name="module" class="form-select @error('module') is-invalid @enderror" required>
      <option value="">-- Pilih Modul --</option>
      @foreach(['organizations', 'members', 'roles', 'users', 'user-accesses', 'activities', 'meetings', 'agendas', 'finances', 'letters', 'letter-categories'] as $module)
      <option value="{{ $module }}" @selected(old('module')===$module)>{{ $module }}</option>
      @endforeach
    </select>
    @error('module')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Hak Akses</label>
    <div class="form-check">
      <input type="hidden" name="allow_create" value="0">
      <input type="checkbox" name="allow_create" value="1" id="allow_create" class="form-check-input" @checked(old('allow_create', $access->allow_create ?? false))>
      <label class="form-check-label" for="allow_create">Buat (Create)</label>
    </div>
    <div class="form-check">
      <input type="hidden" name="allow_edit" value="0">
      <input type="checkbox" name="allow_edit" value="1" id="allow_edit" class="form-check-input" @checked(old('allow_edit', $access->allow_edit ?? false))>
      <label class="form-check-label" for="allow_edit">Ubah (Edit)</label>
    </div>
    <div class="form-check">
      <input type="hidden" name="allow_view" value="0">
      <input type="checkbox" name="allow_view" value="1" id="allow_view" class="form-check-input" @checked(old('allow_view', $access->allow_view ?? false))>
      <label class="form-check-label" for="allow_view">Lihat (View)</label>
    </div>
    <div class="form-check">
      <input type="hidden" name="allow_delete" value="0">
      <input type="checkbox" name="allow_delete" value="1" id="allow_delete" class="form-check-input" @checked(old('allow_delete', $access->allow_delete ?? false))>
      <label class="form-check-label" for="allow_delete">Hapus (Delete)</label>
    </div>
  </div>
  @endif
  
</div>