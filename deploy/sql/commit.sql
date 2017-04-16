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

CREATE TABLE rate
(
  id                 SERIAL NOT NULL PRIMARY KEY,
  insert_date        TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden          INTEGER                  DEFAULT 0,
  source_currency_id INTEGER,
  target_currency_id INTEGER,
  exchange_rate      DOUBLE PRECISION,
  fee                DOUBLE PRECISION,
  effective_rate     DOUBLE PRECISION,
  is_default         INTEGER                  DEFAULT 0
);

CREATE UNIQUE INDEX ux_rate_source_currency_id_target_currency_id
  ON rate (source_currency_id, target_currency_id);
CREATE INDEX ix_rate_is_hidden_id
  ON rate (is_hidden, id);
CREATE INDEX ix_rate_is_hidden_source_currency_id
  ON rate (is_hidden, source_currency_id);
