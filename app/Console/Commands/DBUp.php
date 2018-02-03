<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DBUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable Database Maintenance mode';

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
        //
        $info = $this->get_maintenance_info();
        $this->free_maintenance_info();

        return;
        if ($info != false) {
            $this->info('Starting maintenance mode!');
            $config = $this->argument('maintenance_to_use');
            if ($config == '') {
                $config = 'maintenance';
            }
            $this->start_maintenance_mode($config);
        } else {
            $this->error("Maintenance mode already on the way. Currently on stage: {$info['stage_name']}");
        }
    }

    protected function get_maintenance_info()
    {
        if (Storage::disk()->exists('db.down')) {
            $contents = Storage::get('db.down');

            return json_decode($contents, true);
        }

        return false;
    }

    protected function start_maintenance_mode($config = 'tmdb_maintenance')
    {
        $this->replicate_tables($config);

        $this->finish_preparations();
    }

    protected function replicate_tables($config)
    {
        $info = [
            'stage'      => 'db.replication',
            'stage_name' => 'Database Replication',
            'progress'   => 0.0,
        ];

        $this->set_maintenance_info($info);
        //Replicating database
        $from_db = Config::get("database.$config.from_database");
        $to_db = Config::get("database.$config.to_database");
        $connection = Config::get("database.$config.db_connection");
        if ($from_db == '' || $to_db == '') {
            $this->error('Please check your configuration!');
            $this->free_maintenance_info();

            return;
        }

        $this->info("Replicating database from $from_db to $to_db");

        $conf_tables = Config::get("database.$config.tables");
        $tables = $conf_tables;
        if ($conf_tables == 'all' || $conf_tables == '') {
            $tables = DB::select("SHOW TABLES FROM $from_db");
            $real_tables = [];
            foreach ($tables as $dict) {
                foreach ($dict as $key => $value) {
                    $real_tables[] = $value;
                }
            }
            $tables = $real_tables;
        }

        $this->info('Replicating tables '.implode(', ', $tables));

        $first_tables = Config::get("database.$config.tables_before");

        if (is_array($first_tables) && count($first_tables) > 0) {
            $this->info('Starting with table(s) '.implode(',', $first_tables));

            foreach ($first_tables as $value) {
                if (($key = array_search($value, $tables)) !== false) {
                    unset($tables[$key]);
                }
            }

            foreach ($first_tables as $value) {
                array_unshift($tables, $value);
            }

            $this->info('Order of replication: '.implode(', ', $tables));
        }

        $current = 0;
        $total = count($tables);

        DB::connection($connection)->statement('SET foreign_key_checks = 0;');
        foreach ($tables as $value) {
            try {
                DB::connection($connection)->statement("DROP TABLE $to_db.$value");
            } catch (Exception $e) {
                //Ignore since maybe table does not exist yet.
            }

            $create_syntax = DB::select("SHOW CREATE TABLE $from_db.$value");
            $actual_syntax = '';
            foreach ($create_syntax as $syntax) {
                //dd($syntax);
                foreach ($syntax as $key => $val) {
                    if ($key == 'Create Table') {
                        $actual_syntax = $val;
                    }
                }
            }

            $actual_syntax = str_replace("`$value`", "`$to_db`.`$value`", $actual_syntax);
            DB::connection($connection)->statement($actual_syntax);
            //DB::connection($connection)->getPdo()->query($actual_syntax);
            DB::connection($connection)->statement("INSERT INTO $to_db.$value SELECT * FROM $from_db.$value");
            $current += 1;
            $this->info("Replicated table $value. ($current/$total)");
            $info = [
                'stage'      => 'db.replication',
                'stage_name' => 'Database Replication',
                'progress'   => $current / (float) $total,
            ];

            $this->set_maintenance_info($info);
        }
        DB::connection($connection)->statement('SET foreign_key_checks = 1;');

        $this->info('Finished Replicating Database.');
    }

    protected function finish_preparations()
    {
        $info = [
          'stage'      => 'finished',
          'stage_name' => 'Database Maintenance!',
          'progress'   => 1.0,
      ];

        $this->set_maintenance_info($info);
        $this->info('Finished preparing Database Maintenance. You can now safely manipulate production database!');
    }

    protected function set_maintenance_info($info)
    {
        Storage::put('db.down', json_encode($info));
    }

    protected function free_maintenance_info()
    {
        Storage::delete('db.down');
    }
}
