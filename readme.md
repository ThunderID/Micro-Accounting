FORMAT: 1A

# MicroAccounting

# Accounts [/accounts]
Account resource representation.

## Show all Accounts [GET /accounts]
Get a JSON representation of all the stored Accounts.

+ Request (application/json)
    + Body

            {
                "search": {
                    "name": "string",
                    "companyid": "integer",
                    "code": "string",
                    "type": "asset|liability|equity|income|expense"
                },
                "sort": {
                    "newest": "asc",
                    "company": "desc",
                    "type": "desc",
                    "code": "asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "id": null,
                        "company_id": "integer",
                        "name": "string",
                        "code": "string",
                        "type": "string"
                    },
                    "count": "integer"
                }
            }

## Store Account [POST /accounts]
Store a new Account in a company.

+ Request (application/json)
    + Body

            {
                "id": null,
                "company_id": "integer",
                "name": "string",
                "code": "string",
                "type": "string"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": null,
                    "company_id": "integer",
                    "name": "string",
                    "code": "string",
                    "type": "string"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

## Delete Account [DELETE /accounts]
Delete an account of company

+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": null,
                    "company_id": "integer",
                    "name": "string",
                    "code": "string",
                    "type": "string"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

# Transactions [/transactions]
Transaction resource representation.

## Show all Transactions [GET /transactions/{type}]
Get a JSON representation of all the stored Transactions.

+ Request (application/json)
    + Body

            {
                "type": "all|cash_note|cheque|credit_memo|debit_memo|giro|invoice|memorial|receipt",
                "search": {
                    "id": "integer",
                    "name": "string",
                    "companyid": "integer",
                    "code": "string",
                    "type": "cash_note|cheque|credit_memo|debit_memo|giro|invoice|memorial|receipt"
                },
                "sort": {
                    "newest": "asc",
                    "company": "desc",
                    "type": "desc",
                    "code": "asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "id": "null",
                        "amount": "integer",
                        "issued_by": "integer",
                        "company_id": "integer",
                        "assigned_to": "integer",
                        "type": "receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro",
                        "doc_number": "string",
                        "ref_number": "string",
                        "issued_at": "datetime",
                        "transact_at": "datetime",
                        "due_at": "datetime",
                        "details": {
                            "id": "integer",
                            "transaction_id": "integer",
                            "description": "string",
                            "quantity": "integer",
                            "unit": "string",
                            "price": "integer",
                            "discount": "integer"
                        }
                    },
                    "count": "integer"
                }
            }

## Store Transaction [POST /transactions/{type}]
Store a new Transaction with transaction details.

+ Request (application/json)
    + Body

            {
                "id": "null",
                "amount": "integer",
                "issued_by": "integer",
                "company_id": "integer",
                "assigned_to": "integer",
                "type": "receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro",
                "doc_number": "string",
                "ref_number": "string",
                "issued_at": "datetime",
                "transact_at": "datetime",
                "due_at": "datetime",
                "details": {
                    "id": "integer",
                    "transaction_id": "integer",
                    "description": "string",
                    "quantity": "integer",
                    "unit": "string",
                    "price": "integer",
                    "discount": "integer"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": "null",
                    "amount": "integer",
                    "issued_by": "integer",
                    "company_id": "integer",
                    "assigned_to": "integer",
                    "type": "receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro",
                    "doc_number": "string",
                    "ref_number": "string",
                    "issued_at": "datetime",
                    "transact_at": "datetime",
                    "due_at": "datetime",
                    "details": {
                        "id": "integer",
                        "transaction_id": "integer",
                        "description": "string",
                        "quantity": "integer",
                        "unit": "string",
                        "price": "integer",
                        "discount": "integer"
                    }
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

## Delete Transaction [DELETE /transactions/{type}]
Delete a Transaction with transaction details.

+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": "null",
                    "amount": "integer",
                    "issued_by": "integer",
                    "company_id": "integer",
                    "assigned_to": "integer",
                    "type": "receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro",
                    "doc_number": "string",
                    "ref_number": "string",
                    "issued_at": "datetime",
                    "transact_at": "datetime",
                    "due_at": "datetime",
                    "details": {
                        "id": "integer",
                        "transaction_id": "integer",
                        "description": "string",
                        "quantity": "integer",
                        "unit": "string",
                        "price": "integer",
                        "discount": "integer"
                    }
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "cannot delete."
                    ]
                }
            }

# Journals [/journals]
Journal resource representation.

## Show all Journals [GET /journals/{type}]
Get a JSON representation of all the stored Journals.

+ Request (application/json)
    + Body

            {
                "type": "cash|accrual",
                "search": {
                    "id": "integer",
                    "transactionid": "integer",
                    "parentaccountid": "integer",
                    "accountid": "integer"
                },
                "sort": {
                    "newest": "asc",
                    "transaction": "desc",
                    "debit": "desc",
                    "credit": "asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "id": null,
                        "company_id": "integer",
                        "transaction_id": "integer",
                        "transact_at": "datetime",
                        "type": "cash|accrual",
                        "currency": "string",
                        "notes": "text",
                        "details": {
                            "id": "integer",
                            "journal_id": "integer",
                            "account_id": "integer",
                            "debit": "integer",
                            "credit": "integer",
                            "account": {
                                "company_id": "integer",
                                "name": "string",
                                "type": "string",
                                "code": "string"
                            }
                        },
                        "transaction": {
                            "id": "integer",
                            "amount": "integer",
                            "issued_by": "integer",
                            "company_id": "integer",
                            "assigned_to": "integer",
                            "type": "receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro",
                            "doc_number": "string",
                            "ref_number": "string",
                            "issued_at": "datetime",
                            "transact_at": "datetime",
                            "due_at": "datetime"
                        }
                    },
                    "count": "integer"
                }
            }

## Store Journal [POST /journals/{type}]
Store a new Journal

+ Request (application/json)
    + Body

            {
                "id": null,
                "company_id": "integer",
                "transaction_id": "integer",
                "transact_at": "datetime",
                "type": "cash|accrual",
                "currency": "string",
                "notes": "text",
                "details": {
                    "id": "integer",
                    "journal_id": "integer",
                    "account_id": "integer",
                    "debit": "integer",
                    "credit": "integer"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": null,
                    "company_id": "integer",
                    "transaction_id": "integer",
                    "transact_at": "datetime",
                    "type": "cash|accrual",
                    "currency": "string",
                    "notes": "text",
                    "details": {
                        "id": "integer",
                        "journal_id": "integer",
                        "account_id": "integer",
                        "debit": "integer",
                        "credit": "integer",
                        "account": {
                            "company_id": "integer",
                            "name": "string",
                            "type": "string",
                            "code": "string"
                        }
                    },
                    "transaction": {
                        "id": "integer",
                        "amount": "integer",
                        "issued_by": "integer",
                        "company_id": "integer",
                        "assigned_to": "integer",
                        "type": "receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro",
                        "doc_number": "string",
                        "ref_number": "string",
                        "issued_at": "datetime",
                        "transact_at": "datetime",
                        "due_at": "datetime"
                    }
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "account invalid."
                    ]
                }
            }

## Delete Journal [DELETE /journals/{type}]
Delete a Journal

+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": null,
                    "company_id": "integer",
                    "transaction_id": "integer",
                    "transact_at": "datetime",
                    "type": "cash|accrual",
                    "currency": "string",
                    "notes": "text",
                    "details": {
                        "id": "integer",
                        "journal_id": "integer",
                        "account_id": "integer",
                        "debit": "integer",
                        "credit": "integer",
                        "account": {
                            "company_id": "integer",
                            "name": "string",
                            "type": "string",
                            "code": "string"
                        }
                    },
                    "transaction": {
                        "id": "integer",
                        "amount": "integer",
                        "issued_by": "integer",
                        "company_id": "integer",
                        "assigned_to": "integer",
                        "type": "receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro",
                        "doc_number": "string",
                        "ref_number": "string",
                        "issued_at": "datetime",
                        "transact_at": "datetime",
                        "due_at": "datetime"
                    }
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "cannot delete."
                    ]
                }
            }

# Accounts [/reports]
Report resource representation.

## Report general ledger [GET /reports/general/ledger/{mode}]
Get a JSON representation of all the stored journal in various type of account.

+ Request (application/json)
    + Body

            {
                "mode": "cash|accrual",
                "ondate": "datetime Y-m-d H:i:s",
                "company_id": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "assets": {
                        "id": null,
                        "company_id": "integer",
                        "name": "string",
                        "code": "string",
                        "type": "string",
                        "amount": "integer"
                    },
                    "liabilities": {
                        "id": null,
                        "company_id": "integer",
                        "name": "string",
                        "code": "string",
                        "type": "string",
                        "amount": "integer"
                    },
                    "equities": {
                        "id": null,
                        "company_id": "integer",
                        "name": "string",
                        "code": "string",
                        "type": "string",
                        "amount": "integer"
                    },
                    "incomes": {
                        "id": null,
                        "company_id": "integer",
                        "name": "string",
                        "code": "string",
                        "type": "string",
                        "amount": "integer"
                    },
                    "expenses": {
                        "id": null,
                        "company_id": "integer",
                        "name": "string",
                        "code": "string",
                        "type": "string",
                        "amount": "integer"
                    }
                }
            }