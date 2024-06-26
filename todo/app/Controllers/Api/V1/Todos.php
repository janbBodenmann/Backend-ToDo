<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

class Todos extends ResourceController
{
    protected $modelName = 'App\Models\Todos';
    public function index()
    {
        $all_data = $this->model->findAll();
        if (!empty($all_data)) {

            return $this->respond($all_data);
        }
        return $this->failNotFound();
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
        $data = $this->request->getJSON(true);
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
        } else {
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
            return $this->failNotFound('Category not found');
        }
        $updated = $this->model->update($id, $data);
        if ($updated) {
            return $this->respondUpdated($data);
        } else {
            return $this->failServerError('Failed to update category');
        }
    }

    public function delete($id = null)
    {

        if (!empty($id)) {
            $data_exists = $this->model->find($id);


            if (!empty($data_exists)) {

                $delete_status = $this->model->delete($id);

                if ($delete_status === true) {
                    return $this->respondDeleted(['id' => $id]);

                }

            } else {
                return $this->failNotFound();
            }

        }
    }

}