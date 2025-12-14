<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER add_points AFTER UPDATE ON orders
            FOR EACH ROW BEGIN
                IF NEW.order_status = "completed" AND OLD.order_status != "completed" AND NEW.customer_id IS NOT NULL THEN
                    UPDATE customers SET points = points + FLOOR(NEW.total_money / 100000) WHERE id = NEW.customer_id;
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS add_points');
    }
};
