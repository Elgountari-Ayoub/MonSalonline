<?php
class Users extends Controller
{
  public $userModel;
  public function __construct()
  {
    $this->userModel = $this->model('User');
  }
}
