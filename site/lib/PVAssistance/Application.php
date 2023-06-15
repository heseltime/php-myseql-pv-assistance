<?php
namespace PVAssistance;

use \PVAssistance\User;
use \DateTime;

class Application extends Entity {

  private User $user;
  private string $address;
  private DateTime $constructionDate;
  private float $outputInKWP;
  private string $pvType;
  private DateTime $requestDate;
  private string $ipAddress;
  private string $token;
  private string $uuid;
  private string $status;
  private string $notes;
 
  public function __construct(int $id,
                              User $user,
                              string $address,
                              float $outputInKWP,
                              DateTime $constructionDate,
                              string $pvType,
                              DateTime $requestDate,
                              string $ipAddress,
                              string $token,
                              string $uuid,
                              string $status,
                              string $notes) {
    
    $this->user = $user;
    $this->address = $address;
    $this->outputInKWP = $outputInKWP;
    $this->constructionDate = $constructionDate;
    $this->pvType = $pvType;
    $this->requestDate = $requestDate;
    $this->ipAddress = $ipAddress;
    $this->token = $token;
    $this->uuid = $uuid;
    $this->status = $status;
    $this->notes = $notes;

    parent::__construct($id);
  }


public function getUser(): User {
    return $this->user;
}

public function getAddress(): string {
    return $this->address;
}

public function getConstructionDate(): DateTime {
    return $this->constructionDate;
}

public function getOutputInKWP(): float {
    return $this->outputInKWP;
}

public function getPvType(): string {
    return $this->pvType;
}

public function getRequestDate(): DateTime {
    return $this->requestDate;
}

public function getIPAddress(): string {
    return $this->ipAddress;
}

public function getToken(): string {
    return $this->token;
}

public function getUUID(): string {
    return $this->uuid;
}

public function setStatus(AcceptedStatus $status) {
  $this->accepted = $status;
}

public function getStatus(): string {
  return $this->status;
}

public function getNotes(): string {
  return $this->notes;
}

}