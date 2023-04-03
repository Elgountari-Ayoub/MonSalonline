<?php

class Appointment {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get All Appointments
    public function getAppointments() {
        $this->db->query("SELECT *, 
                        appointments.id as appointmentId, 
                        customers.id as customerId
                        FROM appointments 
                        INNER JOIN customers 
                        ON appointments.customer_id = customers.id
                        ORDER BY appointments.start_time ASC;");

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

    // Add Appointment
    public function addAppointment($data) {
        // Prepare Query
        $this->db->query('INSERT INTO appointments (customer_id, start_time, end_time) 
        VALUES (:customer_id, :start_time, :end_time)');

        // Bind Values
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);

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
        $this->db->query('UPDATE appointments SET customer_id = :customer_id, start_time = :start_time, end_time = :end_time WHERE id = :id');

        // Bind Values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete Appointment
    public function deleteAppointment($id) {
        // Prepare Query
        $this->db->query('DELETE FROM appointments WHERE id = :id');

        // Bind Values
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
