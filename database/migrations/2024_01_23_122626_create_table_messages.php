<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'livearchive';

    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp')->index();
            $table->string('subject')->index('msg_subject');
            $table->string('sender', 320);
            $table->string('recipient', 320);
            $table->integer('size');
            $table->string('file_path');
            $table->string('sender_envelope')->index();
            $table->integer('attachment_count');
            $table->string('preview', 160);

            $table->index(['recipient', 'sender'], 'msg_send_rec');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
