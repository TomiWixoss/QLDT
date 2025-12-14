<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Only create trigger for MySQL (SQLite doesn't support this syntax)
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::unprepared('
                CREATE TRIGGER update_stock AFTER INSERT ON stock_movements
                FOR EACH ROW BEGIN
                    IF NEW.type = "in" THEN
                        UPDATE products SET quantity = quantity + NEW.quantity WHERE id = NEW.product_id;
                    ELSEIF NEW.type = "out" THEN
                        UPDATE products SET quantity = quantity - NEW.quantity WHERE id = NEW.product_id;
                    END IF;
                END
            ');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_stock');
        }
    }
};
