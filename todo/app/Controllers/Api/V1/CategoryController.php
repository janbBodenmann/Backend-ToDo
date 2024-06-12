<?php

namespace App\Controllers\Api\V1;

use App\Controllers\Api\V1\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CategoryController extends ResourceController
{
    protected $modelName = 'App\Models\CategoryModel';
    protected $format = 'json';
    

    public function index()
    {
        $data = $this->model->findAll();
        if (!empty($data)) {
            return $this->respond($data);
        }
        log_message('debug','Show per ID.');
        return $this->fail("No data found");
    }

    public function show($id = null)
    {
        if (!empty($id)) {
            $data = $this->model->find($id);
            if (!empty($data)) {
                return $this->respond($data);
               
            }
        }
        
        
        return $this->failNotFound();
        
    }
  
    
    

    public function create()
    {
        // If user adds count to 10 even though he never used it. Will be set to 0.
        $data = $this->request->getJSON(true);
        if ($data['count'] != 0) {
            $data['count'] = 0;
        }

        if(!empty($data['id'])){
            $dublicateId = $this->model->find($data['id']);
            if(!empty($dublicateId)){
                return $this->fail("Error dublicaded ID", 400);
            }
        }


        if (!empty($data)) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $new_id = $this->model->insert($data);
            if ($new_id == false) {
                return $this->failValidationError($this->model->errors());
            } else {
                // Füge die neu erstellte ID dem Antwortdatenarray hinzu
                $data['id'] = $new_id;
                return $this->respondCreated($data);
            }
        } else {  log_message('info','Show per ID');
            return $this->failValidationError($this->model->errors());
            
        }
       
    }


    public function update($id = null)
    {
        if ($id === null) {
            return $this->failValidationError('ID is required');
        }
        $data = $this->request->getJSON(true);
        if (empty($data)) {
            return $this->failValidationError('No data provided');
        }
        $category = $this->model->find($id);
        if (!$category) {
            return $this->failNotFound('Category not found', 400);
        }
        if (!empty($data['count'])){
            $data['count'] = $category['count'];
        }
        $updated = $this->model->update($id, $data);
        if ($updated) {
            return $this->respondUpdated($data);
        } else { log_message('info','Show per ID');
            return $this->failServerError('Failed to update category');
        }
        
    }

    public function delete($id = null)
    {

        if (!empty($id)) {
            $data_exists = $this->model->find($id);


            if (!empty($data_exists)) {
                //Checks for if model category count = 0 does not work

                // $result = $this->model->find($id);

                if (($data_exists['count'] == 0)) {

                    $delete_status = $this->model->delete($id);

                    if ($delete_status === true) {
                        return $this->respondDeleted(['id' => $id]);

                    }
                } else {
                    return $this->fail("error category is being used", 400);
                }

            } else { log_message('info','Show per ID');
                return $this->failNotFound();
            } 

        }
       
    }
}
?>