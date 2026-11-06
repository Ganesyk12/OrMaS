@php
  $formatAmount = function ($value) {
      if ($value === '' || $value === null) {
          return '';
      }

      return rtrim(rtrim(number_format((float) $value, 2, ',', '.'), '0'), ',');
  };

  $existingDetails = $finance?->details ?? collect();
  $existingDetailsById = $existingDetails->keyBy('id');

  $detailRows = old('details');

  if ($detailRows === null) {
      $detailRows = $existingDetails->map(fn ($detail) => [
          'id' => $detail->id,
          'category' => $detail->category,
          'amount' => $detail->amount,
          'receipt' => $detail->receipt,
      ])->all();
  }

  if (empty($detailRows)) {
      $detailRows = [['id' => null, 'category' => '', 'amount' => '', 'receipt' => null]];
  }

  $nextDetailIndex = max(array_keys($detailRows)) + 1;
@endphp

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

  <div class="col-md-12">
    <hr class="my-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h6 class="mb-0">Detail Transaksi</h6>
        <div class="text-muted small">Total transaksi dihitung dari akumulasi seluruh detail di bawah.</div>
      </div>
      <button type="button" class="btn btn-outline-primary btn-sm" id="add-detail-row">
        <i class="ti ti-plus me-1"></i> Tambah Detail
      </button>
    </div>

    <div id="detail-rows">
      @foreach($detailRows as $i => $row)
        @php
          $rowReceipt = $row['receipt'] ?? null;
          if (! $rowReceipt && ! empty($row['id']) && $existingDetailsById->has($row['id'])) {
              $rowReceipt = $existingDetailsById->get($row['id'])->receipt;
          }
        @endphp
        <div class="detail-row border rounded p-3 mb-3" data-detail-row>
          <input type="hidden" name="details[{{ $i }}][id]" value="{{ $row['id'] ?? '' }}">

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Kategori <span class="text-danger">*</span></label>
              <input type="text" name="details[{{ $i }}][category]"
                     class="form-control @error('details.'.$i.'.category') is-invalid @enderror"
                     value="{{ $row['category'] ?? '' }}" required>
              @error('details.'.$i.'.category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
              <label class="form-label">Jumlah <span class="text-danger">*</span></label>
              <div class="input-group has-validation">
                <span class="input-group-text">Rp</span>
                <input type="text" name="details[{{ $i }}][amount]"
                       class="form-control js-amount @error('details.'.$i.'.amount') is-invalid @enderror"
                       inputmode="numeric" autocomplete="off"
                       value="{{ $formatAmount($row['amount'] ?? '') }}" placeholder="0" required>
                @error('details.'.$i.'.amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label">Bukti / Kwitansi</label>
              <input type="file" name="details[{{ $i }}][receipt]"
                     class="form-control @error('details.'.$i.'.receipt') is-invalid @enderror">
              @error('details.'.$i.'.receipt')<div class="invalid-feedback">{{ $message }}</div>@enderror
              @if($rowReceipt)
                <div class="form-text">
                  File saat ini:
                  <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($rowReceipt) }}" target="_blank" rel="noopener">
                    {{ basename($rowReceipt) }}
                  </a>
                </div>
              @endif
            </div>

            <div class="col-md-2 d-flex align-items-end">
              <button type="button" class="btn btn-outline-danger w-100 js-remove-detail">
                <i class="ti ti-trash me-1"></i> Hapus
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="d-flex justify-content-end mt-2">
      <div class="fw-semibold">Total: <span id="detail-total" class="text-dark">Rp 0</span></div>
    </div>
  </div>
</div>

<template id="detail-row-template">
  <div class="detail-row border rounded p-3 mb-3" data-detail-row>
    <input type="hidden" name="details[__INDEX__][id]" value="">

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <input type="text" name="details[__INDEX__][category]" class="form-control" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
        <div class="input-group has-validation">
          <span class="input-group-text">Rp</span>
          <input type="text" name="details[__INDEX__][amount]" class="form-control js-amount"
                 inputmode="numeric" autocomplete="off" placeholder="0" required>
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label">Bukti / Kwitansi</label>
        <input type="file" name="details[__INDEX__][receipt]" class="form-control">
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-outline-danger w-100 js-remove-detail">
          <i class="ti ti-trash me-1"></i> Hapus
        </button>
      </div>
    </div>
  </div>
</template>

@push('scripts')
<script>
  (function () {
    var rowsContainer = document.getElementById('detail-rows');
    var addButton = document.getElementById('add-detail-row');
    var template = document.getElementById('detail-row-template');
    var totalEl = document.getElementById('detail-total');

    if (!rowsContainer || !addButton || !template) return;

    var nextIndex = {{ $nextDetailIndex }};

    // Format angka dengan pemisah ribuan titik, desimal koma.
    function formatRupiah(value) {
      var cleaned = value.replace(/[^\d,]/g, '');
      var parts = cleaned.split(',');
      var whole = parts.shift().replace(/^0+(?=\d)/, '');
      var decimal = parts.join('').slice(0, 2);
      var formatted = whole.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

      if (value.indexOf(',') !== -1) {
        formatted += ',' + decimal;
      }

      return formatted;
    }

    function parseAmount(value) {
      var normalized = value.replace(/\./g, '').replace(',', '.');
      var number = parseFloat(normalized);

      return isNaN(number) ? 0 : number;
    }

    // Kembalikan posisi kursor berdasarkan jumlah digit di depannya.
    function setCaretByDigits(input, text, digitCount) {
      if (digitCount <= 0) {
        input.setSelectionRange(0, 0);
        return;
      }

      var seen = 0;
      for (var i = 0; i < text.length; i++) {
        if (/\d/.test(text[i])) {
          seen++;
          if (seen === digitCount) {
            input.setSelectionRange(i + 1, i + 1);
            return;
          }
        }
      }

      input.setSelectionRange(text.length, text.length);
    }

    function updateTotal() {
      if (!totalEl) return;

      var total = 0;
      rowsContainer.querySelectorAll('.js-amount').forEach(function (input) {
        total += parseAmount(input.value);
      });

      totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID', { maximumFractionDigits: 2 });
    }

    rowsContainer.addEventListener('input', function (event) {
      var input = event.target;
      if (!input.classList || !input.classList.contains('js-amount')) return;

      var caret = input.selectionStart || 0;
      var digitsBeforeCaret = input.value.slice(0, caret).replace(/\D/g, '').length;

      input.value = formatRupiah(input.value);
      setCaretByDigits(input, input.value, digitsBeforeCaret);
      updateTotal();
    });

    addButton.addEventListener('click', function () {
      var wrapper = document.createElement('div');
      wrapper.innerHTML = template.innerHTML.replace(/__INDEX__/g, nextIndex++).trim();
      rowsContainer.appendChild(wrapper.firstElementChild);
      updateTotal();
    });

    rowsContainer.addEventListener('click', function (event) {
      var button = event.target.closest('.js-remove-detail');
      if (!button) return;

      if (rowsContainer.querySelectorAll('[data-detail-row]').length <= 1) return;

      var row = button.closest('[data-detail-row]');
      if (row) {
        row.remove();
        updateTotal();
      }
    });

    // Kirim angka murni ke server saat form disubmit.
    var form = rowsContainer.closest('form');
    if (form) {
      form.addEventListener('submit', function () {
        rowsContainer.querySelectorAll('.js-amount').forEach(function (input) {
          input.value = input.value.replace(/\./g, '').replace(',', '.');
        });
      });
    }

    updateTotal();
  })();
</script>
@endpush
