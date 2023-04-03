<?php
header('Content-Type: application/json');
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

  public function create()
  {
    // Create a new customer
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      $first_name = $data['first_name'];
      $last_name = $data['last_name'];
      $phone_number = $data['phone_number'];

      // Generate a unique token
      $token = bin2hex(random_bytes(25));
      // $token = $data['token'];
      $cusomerAdded = $this->customerModel->addCustomer();


      if ($cusomerAdded) {
        $response = array('message' => 'Customer created successfully');
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


  // Add Post
  public function add()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => ''
      ];

      // Validate email
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter name';
        // Validate name
        if (empty($data['body'])) {
          $data['body_err'] = 'Please enter the post body';
        }
      }

      // Make sure there are no errors
      if (empty($data['title_err']) && empty($data['body_err'])) {
        // Validation passed
        //Execute
        if ($this->customerModel->addPost($data)) {
          // Redirect to login
          flash('post_added', 'Post Added');
          redirect('customers');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('customers/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => '',
      ];

      $this->view('customers/add', $data);
    }
  }

  // Edit Post
  public function edit($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'id' => $id,
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => ''
      ];

      // Validate email
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter name';
        // Validate name
        if (empty($data['body'])) {
          $data['body_err'] = 'Please enter the post body';
        }
      }

      // Make sure there are no errors
      if (empty($data['title_err']) && empty($data['body_err'])) {
        // Validation passed
        //Execute
        if ($this->customerModel->updatePost($data)) {
          // Redirect to login
          flash('post_message', 'Post Updated');
          redirect('customers');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('customers/edit', $data);
      }
    } else {
      // Get post from model
      $post = $this->customerModel->getPostById($id);

      // Check for owner
      if ($post->user_id != $_SESSION['user_id']) {
        redirect('customers');
      }

      $data = [
        'id' => $id,
        'title' => $post->title,
        'body' => $post->body,
      ];

      $this->view('customers/edit', $data);
    }
  }

  // Delete Post
  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      //Execute
      if ($this->customerModel->deletePost($id)) {
        // Redirect to login
        flash('post_message', 'Post Removed');
        redirect('customers');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('customers');
    }
  }
}
