<?php

class Appointment {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get All Appointments
    public function getAppointments() {
        // $this->db->query("SELECT *, 
        //                 appointments.id as appointmentId, 
        //                 customers.id as customerId
        //                 FROM appointments 
        //                 INNER JOIN customers 
        //                 ON appointments.customer_id = customers.id
        //                 ORDER BY appointments.date ASC;");
        $this->db->query("SELECT * FROM appointments ORDER BY appointments.date ASC;");

        $results = $this->db->resultset();

        return $results;
    }

    // Get Appointment By ID
    public function getAppointmentById($id) {
        $this->db->query("SELECT * FROM appointments WHERE id = :id");

        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }
    // check if the customer has reserved an hour in the giving date[day]
    // the goal of the this function is to return true if the cusomter try to reserve twice a day
    public function isDayReservedByCustomer($data) {
        $this->db->query("SELECT * FROM appointments WHERE date(date) = :date and customer_id = :customer_id");

        $this->db->bind(':date', $data['date']);
        $this->db->bind(':customer_id', $data['customer_id']);

        $row = $this->db->single();
        if ($row) {
            return true;
        }else{
            return false;
        }
    }

    // check if the date are reserved by any customer
    public function isDateReserved($data) {
        $this->db->query("SELECT * FROM appointments WHERE date = :date");

        $this->db->bind(':date', $data['dateTime']);

        $row = $this->db->single();
        if ($row) {

            return true;
        }else{
            return false;
        }
    }


    // Add Appointment
    public function addAppointment($data) {
        // Prepare Query
        $this->db->query('INSERT INTO appointments (customer_id, date) 
        VALUES (:customer_id, :date)');

        // Bind Values
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':date', $data['dateTime']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update Appointment
    public function updateAppointment($data) {
        // Prepare Query
        $this->db->query('UPDATE appointments SET date = :date WHERE id = :id');

        // Bind Values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':date', $data['dateTime']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete Appointment
    public function deleteAppointment($data) {
        // Prepare Query
        $this->db->query('DELETE FROM appointments WHERE date = :date');

        // Bind Values
        $this->db->bind(':date', $data['date']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
