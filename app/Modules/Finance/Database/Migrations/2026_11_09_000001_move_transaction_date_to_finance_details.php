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
     * Memindahkan tanggal transaksi dari header finances ke setiap detail
     * transaksi di finance_details.
     */
    public function up(): void
    {
        Schema::table('finance_details', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('amount');
        });

        DB::table('finances')->orderBy('id')->chunkById(200, function ($finances) {
            foreach ($finances as $finance) {
                if ($finance->transaction_date === null) {
                    continue;
                }

                DB::table('finance_details')
                    ->where('finance_id', $finance->id)
                    ->update(['transaction_date' => $finance->transaction_date]);
            }
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->dropIndex(['transaction_date']);
            $table->dropColumn('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('type');
            $table->index('transaction_date');
        });

        // Ambil tanggal paling awal dari detail sebagai tanggal header.
        DB::table('finances')->orderBy('id')->chunkById(200, function ($finances) {
            foreach ($finances as $finance) {
                $transactionDate = DB::table('finance_details')
                    ->where('finance_id', $finance->id)
                    ->whereNotNull('transaction_date')
                    ->min('transaction_date');

                if ($transactionDate !== null) {
                    DB::table('finances')
                        ->where('id', $finance->id)
                        ->update(['transaction_date' => $transactionDate]);
                }
            }
        });

        Schema::table('finance_details', function (Blueprint $table) {
            $table->dropColumn('transaction_date');
        });
    }
};
