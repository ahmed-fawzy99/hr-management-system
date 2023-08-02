<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/***
 * Using a Model for this table is not necessary, but it's a good practice to do so.
 * Also, I'm using it to create the timestamps automatically, because using DB::table('globals')->insert()
 * and DB::table('globals')->update() won't do that unless I pass them specifically.
 * Also, Shorter to write Globals::find(1) instead of DB::table('globals')->select('*')->get()
 ***/
class Globals extends Model
{
    use LogsActivity;
    protected $guarded = [];

    // Wasn't able to call this model 'Global' as it was reserved by PHP.
    protected $table = 'globals';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

}
