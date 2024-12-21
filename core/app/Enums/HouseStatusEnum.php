<?php
namespace App\Enums;
enum HouseStatusEnum: int {
    
    case Owned = 1;

    case Rented = 2;

    case Mortgaged = 3;

}