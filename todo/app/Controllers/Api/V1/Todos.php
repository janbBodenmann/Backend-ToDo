<?php

namespace App\Controllers\Api\V1;

use App\Models\CategoryModel;
use CodeIgniter\RESTful\ResourceController;
use PhpParser\Node\Expr\New_;

class Todos extends ResourceController
{
    protected $modelName = 'App\Models\Todos';
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }




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

        //adds created and updated time to the request
        if (!empty($data)) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            // Checks if it gives a category id
            if (!empty($data['category_id'])) {

                //Checks in todo if category id exists if yes it returns the id else null

                $categoryExists = $this->categoryModel->where('id', $data['category_id'])->first();
                if (!empty($categoryExists)) {
                    // Updating the value of count in Category (increasing it)
                    //Finds the category
                    $category = $this->categoryModel->find($data['category_id']);
                    //gets value of count and increments it by one
                    $category['count'] += 1;
                    //updates category with new count value
                    $this->categoryModel->update($data['category_id'], $category);

                    // Inserts data in Todo
                    $new_id = $this->model->insert($data);
                } else {
                    return $this->failNotFound('There is no category with this id');
                }

            } else {
                //inserts data
                $new_id = $this->model->insert($data);
            }

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
                // Updating the value of count in Category (increasing it)
                    //Finds the category
                $category = $this->categoryModel->find($data_exists['category_id']);
                    //gets value of count and increments it by one
                $category['count'] -= 1;
                    //updates category with new count value
                $this->categoryModel->update($data_exists['category_id'], $category);

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