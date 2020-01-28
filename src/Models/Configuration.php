<?php

namespace Amprest\LaravelDatatables\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuration extends Model
{
    //  Use soft deletes in this model
    use SoftDeletes;

    //  Define the table name
    protected $table = 'datatables_configurations';

    //  Define fields that are mass assignable
    protected $fillable = [
        'identifier', 'columns', 'payload'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'array',
        'columns' => 'array',
    ];

    /**
     * Use the identifier as a unique ID during route
     * model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'identifier';
    }

    /**
     * Scope a query to return a result by slug name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $identifier
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIdentifier($query, $identifier)
    {
        return $query->where('identifier', $identifier);
    }
}
