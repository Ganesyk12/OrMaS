<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama Organisasi <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $organization->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Kode <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $organization->code ?? '') }}" required>
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tipe <span class="text-danger">*</span></label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
      @foreach(['rt' => 'RT', 'rw' => 'RW', 'kelurahan' => 'Kelurahan', 'kecamatan' => 'Kecamatan'] as $value => $label)
        <option value="{{ $value }}" @selected(old('type', $organization->type ?? 'rw') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Level</label>
    <input type="text" name="level" class="form-control @error('level') is-invalid @enderror" value="{{ old('level', $organization->level ?? '') }}">
    @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tanggal Berdiri</label>
    <input type="date" name="founded_at" class="form-control @error('founded_at') is-invalid @enderror" value="{{ old('founded_at', $organization?->founded_at?->format('Y-m-d')) }}">
    @error('founded_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6 d-flex align-items-end">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" @checked(old('is_active', $organization->is_active ?? true))>
      <label class="form-check-label" for="is_active">Aktif</label>
    </div>
  </div>
  <div class="col-md-12">
    <label class="form-label">Alamat</label>
    <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $organization->address ?? '') }}</textarea>
    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $organization->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
