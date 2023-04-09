<?php
header('Content-Type: application/json');
class Appointments extends Controller
{
  public $appointmentModel;
  // public $userModel;
  public function __construct()
  {

    // if(!isset($_SESSION['user_id'])){
    //   redirect('users/login');
    // }
    // Load Models
    $this->appointmentModel = $this->model('Appointment');
    // die("!empty");
    // $this->userModel = $this->model('User');
  }

  // Load All Appointments
  public function read()
  {

    // Read all Appointments
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $Appointments  = $this->appointmentModel->getAppointments();
      $data = [
        'Appointments' => array(),
      ];

      $num = count($Appointments);
      if ($num > 0) {
        $AppointmentsArr = array();
        foreach ($Appointments as $Appointment) {
          $Appointment_item = array(
            'id' => $Appointment->id,
            'first_name' => $Appointment->first_name,
            'last_name' => $Appointment->last_name,
            'phone_number' => $Appointment->phone_number,
            'token' => $Appointment->token,
          );
          array_push($data['Appointments'], $Appointment_item);
        }
        echo json_encode($data);
      } else {
        echo json_encode(
          array("message => No Appointment found -!_")
        );
      }
    }
  }

  public function create()
  {
    // Create a new Appointment
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      $first_name = $data['first_name'];
      $last_name = $data['last_name'];
      $phone_number = $data['phone_number'];

      // Generate a unique token
      $token = bin2hex(random_bytes(25));
      // $token = $data['token'];
      $cusomerAdded = $this->appointmentModel->addAppointment();


      if ($cusomerAdded) {
        $response = array('message' => 'Appointment created successfully');
        header('Content-Type: application/json');
        echo json_encode($response);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array('message' => 'Error creating Appointment: ' . $cusomerAdded);
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
        if ($this->appointmentModel->addPost($data)) {
          // Redirect to login
          flash('post_added', 'Post Added');
          redirect('Appointments');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('Appointments/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => '',
      ];

      $this->view('Appointments/add', $data);
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
        if ($this->appointmentModel->updatePost($data)) {
          // Redirect to login
          flash('post_message', 'Post Updated');
          redirect('Appointments');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('Appointments/edit', $data);
      }
    } else {
      // Get post from model
      $post = $this->appointmentModel->getPostById($id);

      // Check for owner
      if ($post->user_id != $_SESSION['user_id']) {
        redirect('Appointments');
      }

      $data = [
        'id' => $id,
        'title' => $post->title,
        'body' => $post->body,
      ];

      $this->view('Appointments/edit', $data);
    }
  }

  // Delete Post
  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      //Execute
      if ($this->appointmentModel->deletePost($id)) {
        // Redirect to login
        flash('post_message', 'Post Removed');
        redirect('Appointments');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('Appointments');
    }
  }
}
