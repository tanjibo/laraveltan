<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 28/9/2017
 * Time: 11:12 AM
 */

namespace Repositories;


interface RepositoryInterface
{

    public function all();

    public function find(string  $id);

}