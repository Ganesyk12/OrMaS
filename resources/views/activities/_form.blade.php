<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Organisasi <span class="text-danger">*</span></label>
    <select name="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
      <option value="">-- Pilih Organisasi --</option>
      @foreach($organizations as $org)
        <option value="{{ $org->id }}" @selected(old('organization_id', $activity->organization_id ?? '') == $org->id)>{{ $org->name }}</option>
      @endforeach
    </select>
    @error('organization_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Judul <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $activity->title ?? '') }}" required>
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tipe <span class="text-danger">*</span></label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
      @foreach(['program' => 'Program', 'kegiatan' => 'Kegiatan', 'pelatihan' => 'Pelatihan', 'sosial' => 'Sosial'] as $value => $label)
        <option value="{{ $value }}" @selected(old('type', $activity->type ?? 'kegiatan') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
      @foreach(['planning' => 'Perencanaan', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
        <option value="{{ $value }}" @selected(old('status', $activity->status ?? 'planning') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $activity?->start_date?->format('Y-m-d')) }}" required>
    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tanggal Selesai</label>
    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $activity?->end_date?->format('Y-m-d')) }}">
    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Lokasi</label>
    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $activity->location ?? '') }}">
    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Anggaran</label>
    <input type="number" name="budget" min="0" step="0.01" class="form-control @error('budget') is-invalid @enderror" value="{{ old('budget', $activity->budget ?? 0) }}">
    @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $activity->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
