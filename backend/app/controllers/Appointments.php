<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
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

  // Load All appointments
  public function read()
  {

    // Read all appointments
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $appointments  = $this->appointmentModel->getAppointments();

      $data = [
        'appointments' => array(),
      ];

      $num = count($appointments);
      if ($num > 0) {
        $appointmentsArr = array();
        foreach ($appointments as $appointment) {
          $appointment_item = array(
            'id' => $appointment->id,
            'customer_id' => $appointment->customer_id,
            'date' => $appointment->date,
          );
          array_push($data['appointments'], $appointment_item);
        }
        echo json_encode($data);
        // print_r($data);
      } else {
        echo json_encode(
          array("message => No appointment found -!_")
        );
      }
    }
  }


  // Load a appointment by ID
  public function single($id = -1)
  {
    // Read all customers
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if ($id <= 0) {
        $appointment  = $this->appointmentModel->getAppointmentById($id);
        $data = [
          'appointment' => $appointment,
        ];
        if ($appointment) {
          echo json_encode($data);
        } else {
          echo json_encode(
            array("message => No appointment found -!_")
          );
        }
      } else {
        echo json_encode(
          array("message => No appointment ID given -!_")
        );
      }
    }
  }
  // Create a new appointment
  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      $DayReservedByCustomer = $this->appointmentModel->isDayReservedByCustomer($data);
      $dateReserved = $this->appointmentModel->isDateReserved($data);

      if ($dateReserved) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array(
          'message' => 'Session Are Reserved',
        );
        header('Content-Type: application/json');
        echo json_encode($response);
      } elseif ($DayReservedByCustomer) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array(
          'message' => 'Can\'t Book twice a day',
        );
        header('Content-Type: application/json');
        echo json_encode($response);
      } else {
        $AppointmentAdded = $this->appointmentModel->addAppointment($data);
        if ($AppointmentAdded) {
          $response = array('message' => 'Appointment created successfully');
          header('Content-Type: application/json');
          echo json_encode($response);
        } else {
          header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
          $response = array('message' => 'Error creating appointment: ' . $AppointmentAdded);
          header('Content-Type: application/json');
          echo json_encode($response);
        }
      }
      // print_r($response);

    }
  }


  // for test
  public function appDate()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      $appointment = $this->appointmentModel->isDayReservedByCustomer($data['date']);
      $response = array('message' => 'Appointment created successfully', 'appintment' => $appointment);
      header('Content-Type: application/json');
      echo json_encode($response);
    }
  }
  // Update an appointment
  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
      $data = json_decode(file_get_contents('php://input'), true);
      // $DayReservedByCustomer = $this->appointmentModel->isDayReservedByCustomer($data);
      $dateReserved = $this->appointmentModel->isDateReserved($data);

      if ($dateReserved) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array(
          'message' => 'Session Are Reserved',
        );
        header('Content-Type: application/json');
        echo json_encode($response);
      } 
      // elseif ($DayReservedByCustomer) {
      //   header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
      //   $response = array(
      //     'message' => 'Can\'t Book twice a day',
      //   );
      //   header('Content-Type: application/json');
      //   echo json_encode($response);
      // }
       else {
        $AppointmentUpdated = $this->appointmentModel->updateAppointment($data);
        if ($AppointmentUpdated) {
          $response = array('message' => 'Appointment updated successfully');
          header('Content-Type: application/json');
          echo json_encode($response);
        } else {
          header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
          $response = array('message' => 'Error updated appointment: ' . $AppointmentUpdated);
          header('Content-Type: application/json');
          echo json_encode($response);
        }
      }
    }
  }
  // Update a appointment
  public function delete()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
      $data = json_decode(file_get_contents('php://input'), true);
      $AppointmentDeleted = $this->appointmentModel->deleteAppointment($data);

      if ($AppointmentDeleted) {
        $response = array('message' => 'Appointment deleted successfully');
        header('Content-Type: application/json');
        echo json_encode($response);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $response = array('message' => 'Error deleting appointment: ' . $AppointmentDeleted);
        header('Content-Type: application/json');
        echo json_encode($response);
      }
    }
  }
}
