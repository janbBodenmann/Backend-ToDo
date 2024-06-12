<?php

namespace App\Models;

use CodeIgniter\Model;

class Todos extends Model
{
    protected $table = 'todo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'open', 'category_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|alpha_numeric_space',
        'open' => 'required|in_list[0,1]',
        'category_id' => 'permit_empty|is_natural_no_zero'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];


    function getOpenTodos($bool)
    {
        return $this->where('open', $bool)->findAll();
    }


    function getFiltered($filter = [])
    {
        $builder = $this->builder();
        if (!empty($filter)) {

            if (isset($filter['status'])) {
                $builder = $this->where('open', $filter['status']);
            }
            if (isset($filter['category_id'])) {
                $builder->where('category_id', $filter['category_id']);
            }

            if (isset($filter['name'])) {
                $builder->like('name', $filter['name']);
            }
            if (isset($filter['order_by'])) {
                $builder->orderBy($filter['order_by'][0], $filter['order_by'][1]);
            }
            if (isset($filter['limit'])) {
                $builder->limit($filter['limit']);
            }
        }

    }
}