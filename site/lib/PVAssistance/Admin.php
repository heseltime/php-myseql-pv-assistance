<?php

namespace PVAssistance;

class Admin extends Entity {

  private string $name;
  private string $password;

  public function __construct(int $id, string $name, string $password) {
    parent::__construct($id);
    $this->name = $name;
    $this->password = $password;
  }

  public function getName() : string {
    return $this->name;
  }

  public function getPassword() : string {
    return $this->password;
  }

}