<?php

namespace App\Constants;

class Status {

    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const VERIFIED = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_PENDING = 2;
    const PAYMENT_REJECT = 3;
    
    const ACCOUNT_PENDING = 0;
    const ACCOUNT_ACTIVE = 1;
    const ACCOUNT_BANNED = 2;

    const TICKET_OPEN = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY = 2;
    const TICKET_CLOSE = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;

    const STAFF_ACTIVE = 1;
    const STAFF_BAN = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING = 2;
    const KYC_VERIFIED = 1;

    const FDR_RUNNING = 1;
    const FDR_CLOSED = 2;

    const DPS_RUNNING = 1;
    const DPS_MATURED = 2;
    const DPS_CLOSED = 0;

    const LOAN_PENDING = 0;
    const LOAN_RUNNING = 1;
    const LOAN_PAID = 2;
    const LOAN_REJECTED = 3;

    const TRANSFER_PENDING = 0;
    const TRANSFER_COMPLETED = 1;
    const TRANSFER_REJECTED = 2;

    const ROLE_TELLER = 0;
    const ROLE_MANAGER = 1;
    const ROLE_CUSTOMER_SERVICE = 2;
    const ROLE_ACCOUNTING = 3;
    
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    
    const MARITAL_SINGLE = 1;
    const MARITAL_MARRIED = 2;
    const MARITAL_DIVORCEE = 3;
    const MARITAL_WIDOW = 4;
}
