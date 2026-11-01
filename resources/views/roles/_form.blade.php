<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama Peran <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Kode <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $role->code ?? '') }}" required>
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Level</label>
    <input type="number" name="level" min="0" class="form-control @error('level') is-invalid @enderror" value="{{ old('level', $role->level ?? 0) }}">
    @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6 d-flex align-items-end">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" @checked(old('is_active', $role->is_active ?? true))>
      <label class="form-check-label" for="is_active">Aktif</label>
    </div>
  </div>
  <div class="col-md-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
