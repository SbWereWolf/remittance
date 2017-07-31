UPDATE transfer
SET
  income_amount       = CAST(:INCOME_AMOUNT AS DOUBLE PRECISION),
  outcome_amount      = CAST(:OUTCOME_AMOUNT AS DOUBLE PRECISION),
  report_email        = :REPORT_EMAIL,
  transfer_name       = :TRANSFER_NAME, transfer_account = :TRANSFER_ACCOUNT,
  receive_name        = :RECEIVE_NAME,
  receive_account     = :RECEIVE_ACCOUNT, document_number = :DOCUMENT_NUMBER,
  document_date       = CAST(:DOCUMENT_DATE AS TIMESTAMP WITH TIME ZONE),
  income_currency_id  = :INCOME_CURRENCY_ID,
  outcome_currency_id = :OUTCOME_CURRENCY_ID, transfer_status_id = :STATUS_ID,
  status_comment      = :STATUS_COMMENT,
  status_time         = CAST(:STATUS_TIME AS TIMESTAMP WITH TIME ZONE),
  is_hidden           = :IS_HIDDEN,
  await_name          = :AWAIT_NAME, await_account = :AWAIT_ACCOUNT,
  fee                 = CAST(:FEE AS DOUBLE PRECISION),
  proceed_account     = :PROCEED_ACCOUNT, proceed_name = :PROCEED_NAME,
  body                = CAST(:BODY AS DOUBLE PRECISION),
  placement_date      = CAST(:PLACEMENT_DATE AS TIMESTAMP WITH TIME ZONE),
  cost                = CAST(:COST AS DOUBLE PRECISION)
WHERE
  id = :ID
RETURNING
  id,
  is_hidden,
  income_amount,
  outcome_amount,
  report_email,
  transfer_name,
  transfer_account,
  receive_name,
  receive_account,
  document_number,
  document_date,
  income_currency_id,
  outcome_currency_id,
  transfer_status_id,
  status_comment,
  status_time,
  await_name,
  await_account,
  fee,
  proceed_account,
  proceed_name,
  body,
  placement_date,
  cost;

UPDATE transfer
SET income_amount    = CAST(:INCOME_AMOUNT AS DOUBLE PRECISION),
  outcome_amount     = CAST(:OUTCOME_AMOUNT AS DOUBLE PRECISION), report_email = :REPORT_EMAIL,
  transfer_name      = :TRANSFER_NAME, transfer_account = :TRANSFER_ACCOUNT, receive_name = :RECEIVE_NAME,
  receive_account    = :RECEIVE_ACCOUNT, document_number = :DOCUMENT_NUMBER, document_date = :DOCUMENT_DATE,
  income_currency_id = :INCOME_CURRENCY_ID, outcome_currency_id = :OUTCOME_CURRENCY_ID, transfer_status_id = :STATUS_ID,
  status_comment     = :STATUS_COMMENT, status_time = :STATUS_TIME, await_name = :AWAIT_NAME,
  await_account      = :AWAIT_ACCOUNT, fee = CAST(:FEE AS DOUBLE PRECISION), proceed_account = :PROCEED_ACCOUNT,
  proceed_name       = :PROCEED_NAME, body = CAST(:BODY AS DOUBLE PRECISION), placement_date = :PLACEMENT_DATE,
  cost               = CAST(:COST AS DOUBLE PRECISION)
WHERE is_hidden = 1 AND document_number = '1501500916' AND document_date = '2017-07-28T06:22:16-07:00'
RETURNING id, is_hidden, income_amount, outcome_amount, report_email, transfer_name, transfer_account, receive_name, receive_account, document_number, document_date, income_currency_id, outcome_currency_id, transfer_status_id, status_comment, status_time, await_name, await_account, fee, proceed_account, proceed_name, body, placement_date, cost;

SELECT CAST(NULL AS DOUBLE PRECISION);


SELECT to_char(current_timestamp, 'YYYY-MM-DD HH24:MI:SS.MSTZ');

SELECT to_timestamp((to_char(current_timestamp, 'YYYY-MM-DD HH24:MI:SS.MSTZ')), 'YYYY-MM-DD T HH24:MI:SS.MS');

SELECT to_char(current_timestamp :: TIMESTAMP AT TIME ZONE 'UTC', 'YYYY-MM-DD"T"HH24:MI:SS"Z"');

SELECT
  current_timestamp                                                                           AS source,
  to_char(current_timestamp :: TIMESTAMP AT TIME ZONE 'UTC', 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"') AS string,
  to_timestamp((to_char(current_timestamp :: TIMESTAMP AT TIME ZONE 'UTC', 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')),
               'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')                                               AS value;

UPDATE transfer
SET document_date = to_timestamp(
    (to_char(current_timestamp :: TIMESTAMP AT TIME ZONE 'UTC', 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')),
    'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')
WHERE id = 61
RETURNING to_timestamp((to_char(current_timestamp :: TIMESTAMP AT TIME ZONE 'UTC', 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')),
                       'YYYY-MM-DD"T"HH24:MI:SS.US"Z"');

SELECT
  current_timestamp,
  to_char(current_timestamp, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"'),
  to_timestamp(to_char(current_timestamp, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"'), 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"');

SELECT
  id,
  income_amount,
  outcome_amount,
  report_email,
  transfer_name,
  transfer_account,
  receive_name,
  receive_account,
  document_number,
  to_char(document_date, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')  AS document_date,
  income_currency_id,
  outcome_currency_id,
  transfer_status_id,
  status_comment,
  to_char(status_time, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')    AS status_time,
  await_name,
  await_account,
  fee,
  proceed_account,
  proceed_name,
  body,
  to_char(placement_date, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"') AS placement_date,
  cost
FROM transfer
WHERE id = 66;

SHOW datestyle;

SET datestyle = "ISO, ISO";

SELECT
  id,
  income_amount,
  outcome_amount,
  report_email,
  transfer_name,
  transfer_account,
  receive_name,
  receive_account,
  document_number,
  to_char(document_date, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')  AS document_date,
  income_currency_id,
  outcome_currency_id,
  transfer_status_id,
  status_comment,
  to_char(status_time, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"')    AS status_time,
  await_name,
  await_account,
  fee,
  proceed_account,
  proceed_name,
  body,
  to_char(placement_date, 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"') AS placement_date,
  cost
FROM transfer
WHERE id = 67;