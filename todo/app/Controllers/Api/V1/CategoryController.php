<?php

namespace App\Controllers\Api\V1;

use App\Controllers\Api\V1\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{


    protected $modelName = 'App\Models\CategoryModel';
    protected $format = 'json';
    protected $config = null;
    public function index()
    {

        $data = $this->model->findAll();

        if(!empty($data)) {
            return $this->respond($data);

        }
        return $this->failNotFound();

    }
}
