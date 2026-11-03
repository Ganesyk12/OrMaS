<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Organisasi <span class="text-danger">*</span></label>
    <select name="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
      <option value="">-- Pilih Organisasi --</option>
      @foreach($organizations as $org)
      <option value="{{ $org->id }}" @selected(old('organization_id', $member->organization_id ?? '') == $org->id)>{{ $org->name }}</option>
      @endforeach
    </select>
    @error('organization_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Pengguna <span class="text-danger">*</span></label>
    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
      <option value="">-- Pilih Pengguna --</option>
      @foreach($users as $user)
      <option value="{{ $user->id }}" @selected(old('user_id', $member->user_id ?? '') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
      @endforeach
    </select>
    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Peran / Jabatan <span class="text-danger">*</span></label>
    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
      <option value="">-- Pilih Peran --</option>
      @foreach($roles as $role)
      <option value="{{ $role->id }}" @selected(old('role_id', $member->role_id ?? '') == $role->id)>{{ $role->name }}</option>
      @endforeach
    </select>
    @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tanggal Bergabung <span class="text-danger">*</span></label>
    <input type="date" name="join_date" class="form-control @error('join_date') is-invalid @enderror" value="{{ old('join_date', $member?->join_date?->format('Y-m-d')) }}" required>
    @error('join_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6 d-flex align-items-end">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" @checked(old('is_active', $member->is_active ?? true))>
      <label class="form-check-label" for="is_active">Aktif</label>
    </div>
  </div>
</div>