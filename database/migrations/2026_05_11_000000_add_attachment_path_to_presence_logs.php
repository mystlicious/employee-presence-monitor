<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentPathToPresenceLogsTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('presence_logs') && ! Schema::hasColumn('presence_logs', 'attachment_path')) {
            Schema::table('presence_logs', function (Blueprint $table) {
                $table->string('attachment_path', 512)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('presence_logs') && Schema::hasColumn('presence_logs', 'attachment_path')) {
            Schema::table('presence_logs', function (Blueprint $table) {
                $table->dropColumn('attachment_path');
            });
        }
    }
}
