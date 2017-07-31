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
  is_default         INTEGER                  DEFAULT 0,
  description        TEXT
);

CREATE UNIQUE INDEX ux_rate_source_currency_id_target_currency_id
  ON public.rate (source_currency_id, target_currency_id);
CREATE INDEX ix_rate_target_currency_id
  ON public.rate (target_currency_id);
CREATE INDEX ix_rate_is_hidden_id
  ON public.rate (is_hidden, id);
CREATE INDEX ix_rate_is_hidden_source_currency_id
  ON public.rate (is_hidden, source_currency_id);

COMMENT ON TABLE rate IS 'Ставки обмена';
COMMENT ON COLUMN rate.exchange_rate IS 'Ставка обмена, ставка * сумма исходной валюты = сумма целевой валюты';
COMMENT ON COLUMN rate.fee IS 'Сумма комиссии за перевод  = сумма исходной валюты * Комиссия';

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

COMMENT ON COLUMN volume.volume IS 'Объём валюты';
COMMENT ON COLUMN volume.reserve IS 'Сумма валюты зарезервированная для обеспечения переводов';
COMMENT ON COLUMN volume.account_name IS 'Имя владельца счёта';
COMMENT ON COLUMN volume.account_number IS 'Номер счёта ( кошелька )';
COMMENT ON COLUMN volume.limitation IS 'ограничения по объёму списания';
COMMENT ON COLUMN volume.total IS 'Полная сумма выполненных списаний';

CREATE TABLE transfer
(
  id                  SERIAL PRIMARY KEY,
  insert_date         TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden           INTEGER                  DEFAULT 0,
  placement_date      TIMESTAMP WITH TIME ZONE DEFAULT now(),
  document_number     TEXT,
  document_date       TIMESTAMP WITH TIME ZONE,
  report_email        TEXT,
  transfer_status_id  INTEGER
    CONSTRAINT fk_transfer_transfer_status_id
    REFERENCES transfer_status (id),
  status_comment      TEXT,
  status_time         TIMESTAMP WITH TIME ZONE,
  income_currency_id  INTEGER
    CONSTRAINT fk_transfer_income_currency_id
    REFERENCES currency (id),
  transfer_account    TEXT,
  transfer_name       TEXT,
  income_amount       DOUBLE PRECISION,
  await_account       TEXT,
  await_name          TEXT,
  fee                 DOUBLE PRECISION,
  body                DOUBLE PRECISION,
  outcome_currency_id INTEGER
    CONSTRAINT fk_transfer_outcome_currency_id
    REFERENCES currency (id),
  proceed_account     TEXT,
  proceed_name        TEXT,
  outcome_amount      DOUBLE PRECISION,
  cost                DOUBLE PRECISION,
  receive_account     TEXT,
  receive_name        TEXT
);

CREATE INDEX ix_transfer_income_currency_id
  ON transfer (income_currency_id);
CREATE INDEX ix_transfer_outcome_currency_id
  ON transfer (outcome_currency_id);
CREATE INDEX ix_transfer_transfer_status_id
  ON transfer (transfer_status_id);
CREATE INDEX ix_transfer_is_hidden_id
  ON transfer (is_hidden, id);
CREATE UNIQUE INDEX ux_transfer_document_date_document_number
  ON public.transfer (document_date, document_number);

COMMENT ON COLUMN transfer.document_number IS 'Номер документа';
COMMENT ON COLUMN transfer.document_date IS 'Дата документа';
COMMENT ON COLUMN transfer.report_email IS 'Почта для уведомлений по событиям обработки заяки на обмен';
COMMENT ON COLUMN transfer.status_comment IS 'Комментарий оставленный при переводе на текущий статус';
COMMENT ON COLUMN transfer.status_time IS 'Время установки статуса';
COMMENT ON COLUMN transfer.income_currency_id IS 'входящая валюта';
COMMENT ON COLUMN transfer.transfer_account IS 'Номер счёта источника перевода';
COMMENT ON COLUMN transfer.transfer_name IS 'Имя владельца счёта источника перевода';
COMMENT ON COLUMN transfer.income_amount IS 'Входящая сумма';
COMMENT ON COLUMN transfer.await_account IS 'Номер счёта на который ожидается поступление';
COMMENT ON COLUMN transfer.await_name IS 'Имя владельца счёта на который ожидается поступление';
COMMENT ON COLUMN transfer.body IS 'Сумма перевода';
COMMENT ON COLUMN transfer.fee IS 'Комиссия за перевод';
COMMENT ON COLUMN transfer.outcome_currency_id IS 'Исходящая валюта';
COMMENT ON COLUMN transfer.proceed_account IS 'Номер рабочего счёта, с которого будет выполнен перевод';
COMMENT ON COLUMN transfer.proceed_name IS 'Имя владельца рабочего счёта с которого будет выполнен перевод';
COMMENT ON COLUMN transfer.outcome_amount IS 'Исходящая сумма';
COMMENT ON COLUMN transfer.cost IS 'Стоимость перевода';
COMMENT ON COLUMN transfer.receive_account IS 'Номер принимающего счёта';
COMMENT ON COLUMN transfer.receive_name IS 'Владелец принимающего счёта';

/* --==88 DML 88==-- */

INSERT INTO public.transfer_status (is_hidden, code, title, description)
VALUES (0, 'RECEIVED', 'Принята', 'Принятая заявка на перевод');
INSERT INTO public.transfer_status (is_hidden, code, title, description)
VALUES (0, 'ACCOMPLISH', 'Выполнена', 'Выполненная успешно заявка');
INSERT INTO public.transfer_status (is_hidden, code, title, description)
VALUES (0, 'ANNUL', 'Отменена', 'Отменённая ( Аннулированная ) заявка');
