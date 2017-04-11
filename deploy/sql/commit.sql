CREATE TABLE public.transfer
(
  id               SERIAL PRIMARY KEY,
  insert_date      TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden        INTEGER                  DEFAULT 0,
  document_number  TEXT,
  document_date    TEXT,
  report_email     TEXT,
  status           TEXT,
  status_comment   TEXT,
  status_time      TEXT,
  income_account   TEXT,
  income_amount    TEXT,
  outcome_account  TEXT,
  outcome_amount   TEXT,
  transfer_name    TEXT,
  transfer_account TEXT,
  receive_name     TEXT,
  receive_account  TEXT
);
CREATE INDEX ix_transfer_is_hidden_id
  ON transfer (is_hidden, id);

CREATE TABLE public.currency
(
  id          SERIAL PRIMARY KEY,
  insert_date TIMESTAMPTZ DEFAULT now(),
  is_hidden   INTEGER     DEFAULT 0,
  code        VARCHAR(4000),
  title       VARCHAR(4000),
  description VARCHAR(4000)
);
CREATE UNIQUE INDEX ux_currency_code
  ON public.currency (code);
CREATE INDEX ix_currency_is_hidden_code
  ON public.currency (is_hidden,code);
