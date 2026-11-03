<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Kode <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $category->code ?? '') }}" placeholder="Contoh: UND" required>
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-8">
    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name ?? '') }}" placeholder="Contoh: Undangan" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Keterangan singkat kategori surat">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" @checked(old('is_active', $category->is_active ?? true))>
      <label class="form-check-label" for="is_active">Aktif</label>
    </div>
  </div>
</div>
