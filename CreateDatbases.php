<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDatbases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migration:database {func} {limit} {prefix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create\Drop your limit databases. Use php artisan migration:database create\drop 100 prefix_';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $range = $this->argument('limit');
        $prefix = $this->argument('prefix');
        for($i = 0; $i < $range; $i++) {
            try {
                if($this->argument('func') === 'drop') {
                    DB::statement(DB::raw('DROP DATABASE '.$prefix.$i));
                    $this->info("Database {$prefix}{$i} drop!");
                } else if($this->argument('func') === 'create') {
                    DB::statement(DB::raw('CREATE DATABASE '.$prefix.$i));
                    Config::set("database.connections.mysql_{$i}", [
                        'driver' => 'mysql',
                        'host' => env('DB_HOST', '127.0.0.1'),
                        'port' => env('DB_PORT', '3306'),
                        'database' => "{$prefix}{$i}",
                        'username' => env('DB_USERNAME', 'forge'),
                        'password' => env('DB_PASSWORD', ''),
                        'unix_socket' => env('DB_SOCKET', ''),
                        'charset' => 'utf8',
                        'collation' => 'utf8_general_ci',
                        'prefix' => '',
                        'strict' => false,
                        'engine' => null
                    ]);
//                    Schema::connection("mysql_{$i}")
//                        ->create('aaaa_test', function(Blueprint $table) {
//                            $table->string('test');
//                    });
                    $this->info("Database {$prefix}{$i} created!");
                } else {
                    $this->info('Command not found! Use php artisan migration:database create\drop 100 prefix_');
                }
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
            }
        }
    }
}
