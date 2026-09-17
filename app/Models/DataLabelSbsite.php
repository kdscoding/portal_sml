<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataLabelSbsite extends Model
{
    protected $table = 'data_label_sbsite';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'integer';

    protected $guarded = [];

    protected $casts = [
        'no_urut' => 'integer',
        'qty' => 'integer',
        'status_received' => 'boolean',
        'sdd' => 'date',
    ];

    public $timestamps = true;
}
