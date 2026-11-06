<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Memindahkan data transaksi lama ke tabel finance_details, lalu
     * mengubah finances menjadi header transaksi.
     */
    public function up(): void
    {
        $now = now();

        DB::table('finances')->orderBy('id')->chunkById(200, function ($finances) use ($now) {
            foreach ($finances as $finance) {
                DB::table('finance_details')->insert([
                    'finance_id' => $finance->id,
                    'category' => $finance->category ?? 'Lainnya',
                    'amount' => $finance->amount ?? 0,
                    'receipt' => $finance->receipt ?? null,
                    'created_by' => $finance->created_by !== null ? (string) $finance->created_by : null,
                    'updated_by' => $finance->updated_by !== null ? (string) $finance->updated_by : null,
                    'created_at' => $finance->created_at ?? $now,
                    'updated_at' => $finance->updated_at ?? $now,
                ]);
            }
        });

        // Audit trail sekarang menyimpan email, bukan id user.
        Schema::table('finances', function (Blueprint $table) {
            $table->string('created_by')->nullable()->change();
            $table->string('updated_by')->nullable()->change();
        });

        $this->convertUserIdsToEmails('finances');
        $this->convertUserIdsToEmails('finance_details');

        Schema::table('finances', function (Blueprint $table) {
            $table->dropColumn(['category', 'amount', 'description', 'receipt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('receipt')->nullable();
        });

        // Ringkas kembali detail ke header (detail pertama + total nominal).
        DB::table('finances')->orderBy('id')->chunkById(200, function ($finances) {
            foreach ($finances as $finance) {
                $first = DB::table('finance_details')
                    ->where('finance_id', $finance->id)
                    ->orderBy('id')
                    ->first();

                DB::table('finances')->where('id', $finance->id)->update([
                    'category' => $first->category ?? 'Lainnya',
                    'amount' => DB::table('finance_details')->where('finance_id', $finance->id)->sum('amount') ?? 0,
                    'receipt' => $first->receipt ?? null,
                ]);
            }
        });

        // Email tidak dapat dikembalikan menjadi id user secara pasti.
        DB::table('finances')->update(['created_by' => null, 'updated_by' => null]);

        Schema::table('finances', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
        });
    }

    /**
     * Konversi kolom audit berisi id user menjadi email.
     */
    private function convertUserIdsToEmails(string $table): void
    {
        DB::table($table)->select('id', 'created_by', 'updated_by')->orderBy('id')->chunkById(200, function ($rows) use ($table) {
            foreach ($rows as $row) {
                $ids = array_filter([$row->created_by, $row->updated_by], fn ($value) => filled($value));
                $emails = $ids
                    ? DB::table('users')->whereIn('id', $ids)->pluck('email', 'id')
                    : collect();

                $update = [];

                if ($row->created_by !== null) {
                    $update['created_by'] = $emails[$row->created_by] ?? null;
                }

                if ($row->updated_by !== null) {
                    $update['updated_by'] = $emails[$row->updated_by] ?? null;
                }

                if ($update !== []) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            }
        });
    }
};
