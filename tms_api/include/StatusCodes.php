<?php

/**
 * Description of StatusCodes
 *
 * @author Francis Chulu
 */
class StatusCodes {

    /**
     * Any system failure status code
     */
    const GENERIC_ERROR = 99;
    const GENERIC_ERROR_MSG = "Wrong payload. System expects a JSON for POST methods request! ";

    /**
     *  Success status
     */
    const SUCCESS_CODE = 100;
    const SUCCESS_MSG="Request was successful";

    /**
     * failed status
     */
    const FAILURE_CODE = 101;

    /**
     * pending status
     */
    const PENDING_CODE = 102;
    const PENDING_CODE_DSC="Transactions is pending processing";

    /**
     * The trasaction id from client is a duplicate
     */
    const DUPLICATE_CLIENT_TRANSACTION_ID = 103;

    /**
     * Transaction already updated
     */
    const TRANSACTION_ALREADY_UPDATED = 104;

    /**
     * Transaction id does not exist
     */
    const TRANSACTION_ID_DOES_NOT_EXIST = 105;

    /**
     * Client authentication succeeded
     */
    const CLIENT_AUTHENTICATED_SUCCESSFULLY = 106;

    /**
     * Client Authentication failed
     */
    const CLIENT_AUTHENTICATION_FAILED = 107;

    /**
     * Missing mandatory field
     */
    const MISSING_MANDATORY_FIELD = 108;

    /**
     * Invalid amount
     */
    const INVALID_AMOUNT = 109;

    /**
     * Client not authorized to access service
     */
    const CLIENT_NOT_AUTHORIZED_TO_ACCESS_SERVICE = 110;

    /**
     * Duplicate requestID
     */
    const DUPLICATE_REQUEST_ID = 111;

    /**
     * Ambiguos transaction
     */
    const AMBIGUOUS_TRX_STATUS_CODE = 112;
    /**
     * Trader already in the system
     */
    const TRADER_ALREADY_EXIST = 113;
    /**
     * Incorrect credentials
     */
    const INCORRECT_CREDENTIALS=114;

    /**
     * Client is still active
     */
    const CLIENT_ACTIVE = 1;

    /**
     * Client is banned
     */
    const CLIENT_BANNED = 1;

}
