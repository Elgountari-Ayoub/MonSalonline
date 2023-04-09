<?php
class Customer {
  private $db;
  
  public function __construct(){
    $this->db = new Database;
  }

  // Get All Customers
  public function getCustomers(){
    $this->db->query("SELECT * FROM Customers ORDER BY created_at DESC;");

    $results = $this->db->resultset();

    return $results;
  }

  // Get Customer By ID
  public function getCustomerById($id){
    $this->db->query("SELECT * FROM Customers WHERE id = :id");

    $this->db->bind(':id', $id);
    
    $row = $this->db->single();

    return $row;
  }
  // Get Customer By Token
  public function getCustomerByToken($token){
    $this->db->query("SELECT * FROM Customers WHERE token = :token");

    $this->db->bind(':token', $token);
    
    $row = $this->db->single();

    return $row;
  }
  // Get Customer By phone number
  public function getCustomerByPhoneNumber($phone_number){
    $this->db->query("SELECT * FROM Customers WHERE id = :id");

    $this->db->bind(':id', $phone_number);
    
    $row = $this->db->single();

    return $row;
  }

  // Add Customer
  public function addCustomer($data){

    // echo "<pre>";
    // print_r($data);
    // die("empty");
    // echo "</pre>";
    // Prepare Query
    $this->db->query('INSERT INTO Customers (first_name, last_name, phone_number, token) 
    VALUES (:first_name, :last_name, :phone_number, :token)');
    // Bind Values
    $this->db->bind(':first_name', $data['first_name']);
    $this->db->bind(':last_name', $data['last_name']);
    $this->db->bind(':phone_number', $data['phone_number']);
    $this->db->bind(':token', $data['token']);
    //Execute
    if($this->db->execute() === true){
      return true;
    } else {
      return false;
    }
  }

  // Update Customer
  public function updateCustomer($data){
    // Prepare Query
    $this->db->query('UPDATE Customers SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number, token = :token WHERE id = :id');

    // Bind Values
    $this->db->bind(':id', $data['id']);
    $this->db->bind(':first_name', $data['first_name']);
    $this->db->bind(':last_name', $data['last_name']);
    $this->db->bind(':phone_number', $data['phone_number']);
    $this->db->bind(':token', $data['token']);
    
    //Execute
    if($this->db->execute() === true){
      return true;
    } else {
      return false;
    }
  }

  // Delete Customer
  public function deleteCustomer($token){
    // Prepare Query
    $this->db->query('DELETE FROM Customers WHERE token = :token');

    // Bind Values
    $this->db->bind(':id', $token);
    
    //Execute
    if($this->db->execute()){
      return true;
    } else {
      return false;
    }
  }
}
