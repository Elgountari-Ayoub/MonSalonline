<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
class Customers extends Controller
{
  public $customerModel;
  // public $userModel;
  public function __construct()
  {
    // if(!isset($_SESSION['user_id'])){
    //   redirect('users/login');
    // }
    // Load Models
    $this->customerModel = $this->model('Customer');
    // die("!empty");
    // $this->userModel = $this->model('User');
  }

  // Load All customers
  public function read()
  {
    // Read all customers
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $customers  = $this->customerModel->getCustomers();
      $data = [
        'customers' => array(),
      ];

      $num = count($customers);
      if ($num > 0) {
        $customersArr = array();
        foreach ($customers as $customer) {
          $customer_item = array(
            'id' => $customer->id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'phone_number' => $customer->phone_number,
            'token' => $customer->token,
          );
          array_push($data['customers'], $customer_item);
        }
        echo json_encode($data);
      } else {
        echo json_encode(
          array("message => No customer found -!_")
        );
      }
    }
  }

  // Load a customer by ID
  public function single($id = -1)
  {
    // Read all customers
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if ($id != -1) {
        $customer  = $this->customerModel->getCustomerById($id);
        $data = [
          'customer' => $customer,
        ];
        if ($customer) {
          echo json_encode($data);
        } else {
          echo json_encode(
            array("message => No customer found -!_")
          );
        }
      } else {
        echo json_encode(
          array("message => No customer ID given -!_")
        );
      }
    }
  }
  // Load a customer by ID
  public function login_($id = -1)
  {
    // Read all customers
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if ($id != -1) {
        $customer  = $this->customerModel->getCustomerById($id);
        $data = [
          'customer' => $customer,
        ];
        if ($customer) {
          echo json_encode($data);
        } else {
          echo json_encode(
            array("message => No customer found -!_")
          );
        }
      } else {
        echo json_encode(
          array("message => No customer ID given -!_")
        );
      }
    }
  }
  // Create a new customer
  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      // echo json_encode($data);

      $customer = $this->customerModel->getCustomerByToken($data['token']);

      if ($customer) {
        $response = array('message' => 'Customer found', 'customer' => $customer);
        header('Content-Type: application/json');
        echo json_encode($response);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array('message' => 'Customer not found: ' . $customer);
        header('Content-Type: application/json');
        echo json_encode($response);
      }
    }
  }
  // Create a new customer
  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      // Generate a unique token
      $token = bin2hex(random_bytes(5));

      // Add the generated Token to the $data array
      $data['token'] = $token;

      $cusomerAdded = $this->customerModel->addCustomer($data);

      if ($cusomerAdded) {
        $response = array('message' => 'Customer created successfully', 'customer' => $data);
        header('Content-Type: application/json');
        echo json_encode($response);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array('message' => 'Error creating customer: ' . $cusomerAdded);
        header('Content-Type: application/json');
        echo json_encode($response);
      }
    }
  }
  // Update a customer
  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      // Generate a unique token
      $token = bin2hex(random_bytes(5));

      // Add the generated Token to the $data array
      $data['token'] = $token;

      $cusomerUpdated = $this->customerModel->updateCustomer($data);

      if ($cusomerUpdated) {
        $response = array('message' => 'Customer created successfully');
        header('Content-Type: application/json');
        echo json_encode($response);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array('message' => 'Error creating customer: ' . $cusomerUpdated);
        header('Content-Type: application/json');
        echo json_encode($response);
      }
    }
  }
  // Update a customer
  public function delete()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      // Generate a unique token
      $token = bin2hex(random_bytes(5));

      // Add the generated Token to the $data array
      $data['token'] = $token;

      $cusomerDeleted = $this->customerModel->deleteCustomer($data);

      if ($cusomerDeleted) {
        $response = array('message' => 'Customer created successfully');
        header('Content-Type: application/json');
        echo json_encode($response);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array('message' => 'Error creating customer: ' . $cusomerDeleted);
        header('Content-Type: application/json');
        echo json_encode($response);
      }
    }
  }
}
