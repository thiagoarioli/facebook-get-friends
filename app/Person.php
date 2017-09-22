<?php

namespace App;

use DB;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Person extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'person';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'picture', 'id',
    ];


    public function insertMany($arrayDeUsers){
        DB::collection('person')->insert($arrayDeUsers);
    }

}
