<?php
declare(strict_types=1);
namespace Domain;


enum ReviewStatus: int {
    case None = 0;
    case Approved = 1;
    case Seen = 7;
    case Rejected = 9;
    case Deleted = 99;
}