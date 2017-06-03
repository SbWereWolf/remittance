/* --==88 DDL 88==-- */

CREATE TABLE public.transfer_status
(
  id          SERIAL PRIMARY KEY,
  insert_date TIMESTAMPTZ DEFAULT now(),
  is_hidden   INTEGER     DEFAULT 0,
  code        TEXT,
  title       TEXT,
  description TEXT
);
CREATE UNIQUE INDEX ux_transfer_status_code
  ON public.transfer_status (code);
CREATE INDEX ix_transfer_status_is_hidden_code
  ON public.transfer_status (is_hidden, code);

CREATE TABLE transfer
(
  id                 SERIAL PRIMARY KEY,
  insert_date        TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden          INTEGER                  DEFAULT 0,
  document_number    TEXT,
  document_date      TEXT,
  report_email       TEXT,
  transfer_status_id INTEGER
    CONSTRAINT fk_transfer_transfer_status_id
    REFERENCES transfer_status (id),
  status_comment     TEXT,
  status_time        TEXT,
  await_name         TEXT,
  await_account      TEXT,
  income_account     TEXT,
  income_amount      TEXT,
  outcome_account    TEXT,
  outcome_amount     TEXT,
  transfer_name      TEXT,
  transfer_account   TEXT,
  receive_name       TEXT,
  receive_account    TEXT
);

CREATE INDEX ix_transfer_transfer_status_id
  ON transfer (transfer_status_id);
CREATE INDEX ix_transfer_is_hidden_id
  ON transfer (is_hidden, id);


CREATE TABLE public.currency
(
  id          SERIAL PRIMARY KEY,
  insert_date TIMESTAMPTZ DEFAULT now(),
  is_hidden   INTEGER     DEFAULT 0,
  code        TEXT,
  title       TEXT,
  description TEXT
);
CREATE UNIQUE INDEX ux_currency_code
  ON public.currency (code);
CREATE INDEX ix_currency_is_hidden_code
  ON public.currency (is_hidden, code);

CREATE TABLE rate
(
  id                 SERIAL NOT NULL PRIMARY KEY,
  insert_date        TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden          INTEGER                  DEFAULT 0,
  source_currency_id INTEGER
    CONSTRAINT fk_rate_source_currency_id
    REFERENCES currency (id),
  target_currency_id INTEGER
    CONSTRAINT fk_rate_target_currency_id
    REFERENCES currency (id),
  exchange_rate      DOUBLE PRECISION,
  fee                DOUBLE PRECISION,
  effective_rate     DOUBLE PRECISION,
  is_default         INTEGER                  DEFAULT 0
);

CREATE UNIQUE INDEX ux_rate_source_currency_id_target_currency_id
  ON public.rate (source_currency_id, target_currency_id);
CREATE INDEX ix_rate_target_currency_id
  ON public.rate (target_currency_id);
CREATE INDEX ix_rate_is_hidden_id
  ON public.rate (is_hidden, id);
CREATE INDEX ix_rate_is_hidden_source_currency_id
  ON public.rate (is_hidden, source_currency_id);

CREATE TABLE volume
(
  id             SERIAL PRIMARY KEY,
  insert_date    TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden      INTEGER                  DEFAULT 0,
  currency_id    INTEGER
    CONSTRAINT fk_volume_currency_id
    REFERENCES currency (id),
  volume         DOUBLE PRECISION,
  reserve        DOUBLE PRECISION,
  account_name   TEXT,
  account_number TEXT,
  limitation     DOUBLE PRECISION,
  total          DOUBLE PRECISION
);

CREATE UNIQUE INDEX ux_volume_currency_id
  ON public.volume (currency_id);
CREATE INDEX ix_volume_is_hidden_id
  ON public.volume (is_hidden, id);


/* --==88 DML 88==-- */

INSERT INTO public.transfer_status (is_hidden, code, title, description)
VALUES (0, 'RECEIVED', 'Принята', 'Принятая заявка на перевод');
INSERT INTO public.transfer_status (is_hidden, code, title, description)
VALUES (0, 'ACCOMPLISH', 'Выполненна', 'Выполненная успешно заявка');
INSERT INTO public.transfer_status (is_hidden, code, title, description)
VALUES (0, 'ANNUL', 'Отменена', 'Отменённая ( Аннулированная ) заявка');
