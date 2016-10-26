<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyChannel extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'my_channels';

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */


}
