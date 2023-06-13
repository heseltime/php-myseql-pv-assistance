<?php
namespace PVAssistance;

use \PVAssistance\User;
use \DateTime;

class Application extends Entity {

  private string $id;
  private User $user;
  private string $address;
  private DateTime $constructionDate;
  private float $outputInKWP;
  private string $pvType;
  private DateTime $requestDate;
  private string $ipAddress;
  private string $token;
  private string $url;
  private string $status;
  private string $notes;
 
  public function __construct(string $id,
                              User $user,
                              string $address,
                              float $outputInKWP,
                              DateTime $constructionDate,
                              string $pvType,
                              DateTime $requestDate,
                              string $ipAddress,
                              string $token,
                              string $url,
                              string $status,
                              string $notes) {
    $this->id = $id;
    $this->user = $user;
    $this->address = $address;
    $this->outputInKWP = $outputInKWP;
    $this->constructionDate = $constructionDate;
    $this->pvType = $pvType;
    $this->requestDate = $requestDate;
    $this->ipAddress = $ipAddress;
    $this->token = $token;
    $this->url = $url;
    $this->status = $status;
    $this->reason = $notes;
  }

  public function getCountingNumber(): string {
    return $this->countingNumber;
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

public function getIP(): string {
    return $this->ip;
}

public function getToken(): string {
    return $this->token;
}

public function getURL(): string {
    return $this->url;
}

public function setAcceptedStatus(AcceptedStatus $status) {
  $this->accepted = $status;
}

public function getAcceptedStatus(): Status {
  return $this->accepted;
}

public function getReason(): string {
  return $this->reason;
}

}