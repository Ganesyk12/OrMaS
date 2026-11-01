<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">Judul <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $agenda->title ?? '') }}" required>
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Urutan</label>
    <input type="number" name="order" min="0" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $agenda->order ?? 0) }}">
    @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Penyaji</label>
    <input type="text" name="presenter" class="form-control @error('presenter') is-invalid @enderror" value="{{ old('presenter', $agenda->presenter ?? '') }}">
    @error('presenter')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label">Durasi (menit)</label>
    <input type="number" name="duration_minutes" min="1" class="form-control @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $agenda->duration_minutes ?? '') }}">
    @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
      @foreach(['pending' => 'Pending', 'discussed' => 'Dibahas', 'completed' => 'Selesai', 'skipped' => 'Dilewati'] as $value => $label)
        <option value="{{ $value }}" @selected(old('status', $agenda->status ?? 'pending') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $agenda->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
