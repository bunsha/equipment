<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\EquipmentStatus::class, 100)->create();
        $futureDefault = DB::table('equipment_statuses')
            ->select(DB::raw('min(id) as id'))
            ->groupBy('account_id')
            ->get();
        $temp = [];
        foreach($futureDefault as $item){
            $temp[] = $item->id;
        }
        DB::table('equipment_statuses')
            ->whereIn('id', $temp)
            ->update(['is_default' => 1]);

        factory(App\EquipmentType::class, 1000)->create()->each(function ($type) {
            $type->equipment()->saveMany(factory(App\Equipment::class, rand(1,30))->create()->each(function($equipment){
                $equipment->history()->saveMany(factory(App\EquipmentHistory::class, rand(0,50))->make());
            }));
        });
    }
}
