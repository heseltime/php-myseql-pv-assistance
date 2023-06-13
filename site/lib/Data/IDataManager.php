<?php

namespace Data;
use PVAssistance\User;
interface IDataManager {
    public static function createUser(string $firstName, string $lastName, string $sex, string $dateOfBirth, string $emailAddress, string $phoneNo) : ?User;
    public static function getUserByUserId(int $userId) : ?User;
    public static function getUserByEmail(string $emailAddress) : ?User;
}