<?php

namespace App\Lib;

/**
 * Description of StatusCodes
 *
 * @author Francis Chulu
 */
class StatusCodes {

    /**
     * Any system failure status code
     */
    const GENERIC_ERROR = 199;
    const GENERIC_ERROR_MSG = "An internal system failure occured. Please try again later or contact ADC support";

    /**
     *  Successful transaction
     */
    const SUCCESS_CODE = 200;

    /**
     * The transaction failed
     */
    const FAILURE_CODE = 201;

    /**
     * Transaction is pending processing
     */
    const PENDING_CODE = 202;
    const PENDING_CODE_DSC="Transactions is pending processing";

    /**
     * The trasaction id from client is a duplicate
     */
    const DUPLICATE_CLIENT_TRANSACTION_ID = 203;

    /**
     * Transaction already updated
     */
    const TRANSACTION_ALREADY_UPDATED = 204;

    /**
     * Transaction id does not exist
     */
    const TRANSACTION_ID_DOES_NOT_EXIST = 205;

    /**
     * Client authentication succeeded
     */
    const CLIENT_AUTHENTICATED_SUCCESSFULLY = 206;

    /**
     * Client Authentication failed
     */
    const CLIENT_AUTHENTICATION_FAILED = 207;

    /**
     * Missing mandatory field
     */
    const MISSING_MANDATORY_FIELD = 208;

    /**
     * Invalid amount
     */
    const INVALID_AMOUNT = 209;

    /**
     * Client not authorized to access service
     */
    const CLIENT_NOT_AUTHORIZED_TO_ACCESS_SERVICE = 210;

    /**
     * Duplicate requestID
     */
    const DUPLICATE_REQUEST_ID = 211;

    /**
     * Ambiguos transaction
     */
    const AMBIGUOUS_TRX_STATUS_CODE = 212;
    /**
     * Low customer float
     */
    const LOW_FLOAT_STATUS_CODE = 213;
    /**
     * Float Limit reached
     */
    const FLOAT_THRESHOLD_REACHED_STATUS_CODE=214;

    /**
     * Client is still active
     */
    const CLIENT_ACTIVE = 1;

    /**
     * Client is banned
     */
    const CLIENT_BANNED = 1;

}
