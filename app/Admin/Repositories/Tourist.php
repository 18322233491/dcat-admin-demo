<?php

namespace App\Admin\Repositories;

use App\Models\Tourist as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Tourist extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
