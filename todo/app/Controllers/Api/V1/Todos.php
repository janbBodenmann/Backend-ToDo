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

        $request = \Config\Services::request();
        $filter = [];

        // Checks for status
        $status = $request->getGet("status");
        if (isset($status)) {
            if ($status == '0' || $status == '1') {
                $filter['status'] = $status;
            } else {
                return $this->fail("Wrong syntax with 'status' you can only use 0 or 1", 403);
            }
        }

        // Checks for category
        $category_id = $request->getGet("category_id");
        if (isset($category_id)) {
            $filter['category_id'] = $category_id;
        }

        // Checks for limit
        $limit = $request->getGet("limit");
        if (isset($limit)) {
            $filter['limit'] = $limit;
        }

        // Checks for name like
        $name = $request->getGet("name");
        if (isset($name)) {
            $filter['name'] = $name;
        }

        $order_by = $request->getGet("order_by");
        if (isset($order_by)) {
            $order_by = explode(',', $order_by);
            // print_r($order_by);
            // exit;
            if (count($order_by) == 2) {
                if ($order_by[1] == 'asc' || $order_by[1] == 'desc') {

                } else {
                    return $this->fail("Wrong syntax order_by should look like 'blabla,desc' or 'blabla,asc'!", 403);
                }
            } else {
                // array_push($order_by, $order_by);
                array_push($order_by, 'desc');
            }
            $filter['order_by'] = $order_by;
        }


        $filteredRequest = $this->model->getFiltered($filter);

        //wenn kein filter gebraucht wird
        if (empty($filteredRequest)) {
            $all_data = $this->model->findAll();
            if (!empty($all_data)) {

                return $this->respond($all_data);
            }
        } else {
            return $this->respond($filteredRequest);
        }

        return $this->fail("There is no todo with such a Category id");

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
            if (isset($data['category_id'])) {
                
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
                return $this->fail("Fail");
            } else {
                // Füge die neu erstellte ID dem Antwortdatenarray hinzu
                $data['id'] = $new_id;
                return $this->respondCreated($data);
            }
        } else {
            return $this->fail("Might have syntax errors or you give wrong parameters. Only: name, category, open. Name and open are required");
        }
    }


    public function update($id = null)
    {
        // Checks if id is given
        if ($id === null) {
            return $this->failValidationError('ID is required');
        }
        // Checks if data is provided
        $data = $this->request->getJSON(true);
        if (empty($data)) {
            return $this->failValidationError('No data provided');
        }
        // Checks if a todo with that id even exists
        $todo = $this->model->find($id);
        if (!$todo) {
            return $this->failNotFound('Todo not found');
        }
        // Updates todo
        $updated = $this->model->update($id, $data);
        if ($updated) {
            // If category id wants to be changed it goes in this if statement
            if (!empty($todo['category_id'])) {
                //Getting old category
                $category = $this->categoryModel->find($todo['category_id']);
                //Decrementing and updating old category
                $category['count'] -= 1;
                $this->categoryModel->update($todo['category_id'], $category);
                //Finding, incrementing and updating new category
                $category = $this->categoryModel->find($data['category_id']);
                $category['count'] += 1;
                $this->categoryModel->update($data['category_id'], $category);
                //
            } else {
                return $this->fail("Success", status:200);
            }
            if (!empty($category)) {
                return $this->respondUpdated($data);
            }
        } else {
            return $this->failServerError('Failed to update Todo');
        }
    }

    public function delete($id = null)
    {
        
        if (isset($id)) {
            $data_exists = $this->model->find($id);
            // print_r($id);
            // return;

            if (!empty($data_exists)) {
                
                // Updating the value of count in Category (increasing it)
                //Finds the category

                $category = $this->categoryModel->find($data_exists['category_id']);

                if(empty($category)){
                    return $this->fail("Error in settings category was already deleted or never existed");
                }
                
                //gets value of count and increments it by one
                $category['count'] -= 1;
                //updates category with new count value
                $this->categoryModel->update($data_exists['category_id'], $category);

                $delete_status = $this->model->delete($id);
                
                if ($delete_status === true) {
                    return $this->respondDeleted(['id' => $id]);

                } else {
                    return $this->fail("No such todo");
                }

            } else {
                return $this->fail("No todo with that id or id not set");
            }

        } 
    }


    public function status($bool = 1)
    {
        $test = $this->model->getOpenTodos($bool);
        return $this->respond($test);
    }

}