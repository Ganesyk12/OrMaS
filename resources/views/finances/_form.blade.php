<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Organisasi <span class="text-danger">*</span></label>
    <select name="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
      <option value="">-- Pilih Organisasi --</option>
      @foreach($organizations as $org)
        <option value="{{ $org->id }}" @selected(old('organization_id', $finance->organization_id ?? '') == $org->id)>{{ $org->name }}</option>
      @endforeach
    </select>
    @error('organization_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Kegiatan</label>
    <select name="activity_id" class="form-select @error('activity_id') is-invalid @enderror">
      <option value="">-- Tanpa Kegiatan --</option>
      @foreach($activities as $activity)
        <option value="{{ $activity->id }}" @selected(old('activity_id', $finance->activity_id ?? '') == $activity->id)>{{ $activity->title }}</option>
      @endforeach
    </select>
    @error('activity_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tipe <span class="text-danger">*</span></label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
      @foreach(['income' => 'Pemasukan', 'expense' => 'Pengeluaran'] as $value => $label)
        <option value="{{ $value }}" @selected(old('type', $finance->type ?? 'income') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Kategori <span class="text-danger">*</span></label>
    <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $finance->category ?? '') }}" required>
    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
    <input type="number" name="amount" min="0" step="0.01" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $finance->amount ?? '') }}" required>
    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
    <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', $finance?->transaction_date?->format('Y-m-d')) }}" required>
    @error('transaction_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
      @foreach(['draft' => 'Draft', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label)
        <option value="{{ $value }}" @selected(old('status', $finance->status ?? 'draft') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Bukti / Kwitansi</label>
    <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror">
    @if($finance?->receipt)
      <div class="form-text">File saat ini: {{ $finance->receipt }}</div>
    @endif
    @error('receipt')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $finance->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
