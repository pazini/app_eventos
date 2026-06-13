
CREATE TABLE public.app_buyers (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    doc_type character varying(255) DEFAULT 'cpf'::character varying,
    doc_num character varying(255),
    name character varying(255),
    email character varying(255),
    birth_date date,
    contact_country integer,
    contact_ddd integer,
    contact_num integer,
    card_description character varying(255),
    card_token character varying(255),
    card_validate_mm character varying,
    card_validate_aaaa character varying,
    address character varying(255),
    address_number character varying(20),
    address_complement character varying(100),
    address_reference character varying(100),
    city_neighborhood character varying(100),
    city character varying(100),
    state character varying(2),
    country character varying(3) DEFAULT 'BRA'::character varying,
    zip_code character varying(20)
);

CREATE TABLE public.app_callback (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    callback_key character varying(255) NOT NULL,
    callback_target character varying(255) NOT NULL,
    callback_json text,
    callback_processed_date timestamp(0) without time zone,
    callback_processed_status character varying(255)
);

CREATE TABLE public.app_config (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid,
    app_key character varying(255) NOT NULL,
    app_value text,
    app_config_active boolean DEFAULT true NOT NULL,
    app_description character varying(255)
);

CREATE TABLE public.app_events (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    organizer_id uuid NOT NULL,
    organizer_slug character varying(255) NOT NULL,
    event_slug character varying(255) NOT NULL,
    type_id bigint NOT NULL,
    category_id bigint NOT NULL,
    active boolean DEFAULT true NOT NULL,
    status character varying(255) NOT NULL,
    event_visibility_public character varying(255) DEFAULT '1'::character varying NOT NULL,
    json_event text NOT NULL
);

CREATE TABLE public.app_events_notifications (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    order_id uuid NOT NULL,
    notification_type character varying(255),
    notification_json_info text,
    notification_payload text,
    notification_status text,
    notification_datetime_send timestamp(0) without time zone
);

CREATE TABLE public.app_events_orders (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    channel_order character varying(255) NOT NULL,
    channel_user_id uuid,
    status character varying(255),
    order_control character varying(255),
    order_amount integer,
    order_amount_pay integer,
    order_description character varying(255),
    order_generation_datetime timestamp(0) without time zone,
    order_cancel_datetime timestamp(0) without time zone,
    order_cancel_description character varying(255),
    buyer_name character varying(255),
    buyer_email character varying(255),
    buyer_doc_type character varying(255),
    buyer_doc_num character varying(255),
    buyer_contact_country integer DEFAULT 55,
    buyer_contact_ddd integer,
    buyer_contact_num bigint,
    buyer_birth_date date,
    buyer_json_answers text,
    order_items_ticket_type_id uuid,
    order_items_qtd integer,
    order_items_amount integer,
    order_items_amount_total integer,
    order_items_amount_paid integer,
    order_items_amount_liquid integer,
    code_promo_id uuid,
    code_promo_discount_amount bigint DEFAULT 0,
    order_amount_received bigint DEFAULT 0,
    order_amount_received_liquid bigint DEFAULT 0,
    order_json json,
    reservation_expiration_date timestamp(0) without time zone,
    status_old character varying,
    status_old_datetime timestamp(0) without time zone,
    notifica_sucesso character varying,
    notifica_sucesso_datahora timestamp(0) without time zone,
    payment_id uuid,
    buyer_id uuid,
    slip_id uuid,
    slip_description character varying(255),
    order_terms text,
    code_promo_label character varying,
    code_promo_price_old integer,
    code_promo_price_less integer,
    code_promo_price_new integer
);

COMMENT ON COLUMN public.app_events_orders.channel_order IS 'Canal onde a ordem foi gerada';

COMMENT ON COLUMN public.app_events_orders.order_items_qtd IS 'Quantidade de itens';

COMMENT ON COLUMN public.app_events_orders.order_items_amount IS 'Valor do item';

COMMENT ON COLUMN public.app_events_orders.order_items_amount_total IS 'Valor total do item';

COMMENT ON COLUMN public.app_events_orders.order_items_amount_paid IS 'Valor pago do item';

COMMENT ON COLUMN public.app_events_orders.order_items_amount_liquid IS 'Valor liquido da compra';

COMMENT ON COLUMN public.app_events_orders.order_amount_received IS 'Valore recebido pago até o momento';

COMMENT ON COLUMN public.app_events_orders.order_amount_received_liquid IS 'Valor liquido do pagamento recebido ';

COMMENT ON COLUMN public.app_events_orders.slip_id IS 'ID DO CARNE ONLINE';

CREATE TABLE public.app_events_orders_items (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    order_id uuid NOT NULL,
    item_ticket_type_id uuid,
    item_status character varying(255),
    item_amount integer DEFAULT 0 NOT NULL,
    item_amount_pay integer DEFAULT 0 NOT NULL,
    item_amount_pay_liquid integer DEFAULT 0 NOT NULL,
    user_name character varying(255),
    user_email character varying(255),
    user_doc_type character varying(255),
    user_doc_num character varying(255),
    user_contact_country integer DEFAULT 55 NOT NULL,
    user_contact_ddd integer,
    user_contact_num integer,
    user_birth_date date,
    user_json_answers text,
    item_description character varying
);

CREATE TABLE public.app_events_orders_sponsorship (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    plan_id uuid NOT NULL,
    channel_order character varying(255) NOT NULL,
    channel_user_id uuid,
    status character varying(255),
    order_control character varying(255),
    order_amount integer,
    order_amount_pay integer,
    order_description character varying(255),
    order_generation_datetime timestamp(0) without time zone,
    order_cancel_datetime timestamp(0) without time zone,
    buyer_name character varying(255),
    buyer_email character varying(255),
    buyer_doc_type character varying(255),
    buyer_doc_num character varying(255),
    buyer_contact_country integer DEFAULT 55,
    buyer_contact_ddd integer,
    buyer_contact_num integer,
    buyer_url_logo text,
    buyer_url_website text,
    buyer_url_instagram text,
    buyer_json_answers text,
    buyer_segment character varying,
    buyer_description character varying,
    buyer_contact_name character varying,
    order_json json,
    sponsorship_id uuid,
    order_amount_received integer,
    reservation_expiration_date timestamp(0) without time zone,
    status_old character varying
);

COMMENT ON COLUMN public.app_events_orders_sponsorship.channel_order IS 'Canal onde a ordem foi gerada';

CREATE TABLE public.app_events_orders_tickets (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    order_id uuid NOT NULL,
    organizer_id character varying(255),
    organizer_name character varying(255),
    event_id uuid NOT NULL,
    event_name character varying(255),
    event_description character varying(255),
    event_datetime timestamp(0) without time zone,
    event_ticket_id uuid,
    event_ticket_slug character varying(255),
    event_ticket_name character varying(255),
    event_ticket_price integer DEFAULT 0,
    ticket_control character varying(255),
    ticket_status character varying(255) DEFAULT 'gerado'::character varying,
    ticket_generation_datetime timestamp(0) without time zone,
    ticket_checkin_datetime timestamp(0) without time zone,
    ticket_cancel_datetime timestamp(0) without time zone,
    ticket_cancel_description character varying(255),
    user_name character varying(255),
    user_email character varying(255),
    user_doc_type character varying(255),
    user_doc_num character varying(255),
    user_contact_country integer DEFAULT 55,
    user_contact_ddd integer,
    user_contact_num integer,
    user_birth_date date,
    user_json_answers json,
    event_ticket_code_promo_id uuid,
    event_ticket_price_discount integer DEFAULT 0,
    event_ticket_price_paid integer DEFAULT 0,
    order_item_id uuid
);

COMMENT ON COLUMN public.app_events_orders_tickets.event_ticket_id IS 'Tipo do ingresso ID';

COMMENT ON COLUMN public.app_events_orders_tickets.event_ticket_name IS 'Tipo do ingresso';

CREATE TABLE public.app_events_organizers (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    slug character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    json_organizer character varying(255) NOT NULL
);

CREATE TABLE public.app_notifica (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    buyer_id uuid,
    order_id uuid,
    payment_id uuid,
    tipo character varying(255),
    canal character varying(255),
    envio_destino character varying(255),
    envio_datahora timestamp(0) without time zone,
    subject character varying(255),
    body text,
    job_id text,
    job_json text
);

CREATE TABLE public.app_payments (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_ref character varying(255) DEFAULT 'app_event'::character varying,
    app_ref_order_id uuid NOT NULL,
    gateway_id uuid,
    gateway_slug character varying(255),
    status character varying(255),
    status_old character varying(255),
    description character varying(255),
    paid_label character varying(255),
    paid_description character varying(255),
    value_paid integer,
    value_liquid integer,
    value_fees integer,
    fee_percentage_used double precision,
    pay_transaction_id character varying(255),
    pay_nsu character varying(255),
    pay_type character varying(255),
    pay_datetime timestamp(0) without time zone,
    pay_installments_number integer,
    pay_installment_value integer,
    pay_card_first character varying(255),
    pay_card_last character varying(255),
    pay_card_name character varying(255),
    pay_card_brand character varying(255),
    pay_boleto_barcode character varying(255),
    pay_boleto_expiration_date character varying(255),
    pay_boleto_url character varying(255),
    pay_postback_url text,
    pay_gateway_direct_client boolean,
    pay_json_request text,
    pay_json_response text,
    pay_code_promo_id uuid,
    pay_pix_key character varying,
    pay_pix_qr_code text,
    pay_pix_qr_code_url text,
    pay_pix_expires_at timestamp(0) without time zone,
    pay_pix_end_to_end_id character varying,
    pay_integration_type character varying,
    gateway_sandbox boolean,
    order_slip_id uuid,
    pay_code_promo_discount_amount integer DEFAULT 0,
    pay_value_paid integer,
    pay_value_fees integer,
    pay_value_liquid integer,
    notifica_sucesso character varying,
    notifica_sucesso_datahora timestamp without time zone,
    value_amortization integer
);

CREATE TABLE public.app_payments_callbacks (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    order_id uuid,
    payment_id uuid,
    callback_type character varying(255),
    gateway_slug character varying(255),
    gateway_id uuid,
    gateway_transaction_id character varying(255),
    gateway_msg character varying(255),
    status character varying(255),
    status_old character varying(255),
    value_paid integer,
    nsu character varying(255),
    pay_type character varying(255),
    card_first character varying(255),
    card_last character varying(255),
    card_name character varying(255),
    card_brand character varying(255),
    boleto_barcode character varying(255),
    boleto_expiration_date character varying(255),
    boleto_url character varying(255),
    postback_id character varying(255),
    postback_url text,
    json_response text,
    postback_processed timestamp(0) without time zone,
    pix_qr_code text,
    pix_qr_code_url text,
    pix_expires_at character varying,
    pix_end_to_end_id character varying,
    pay_datetime timestamp(0) without time zone,
    ref_controle character varying,
    postback_processed_status character varying,
    postback_processed_json text
);

CREATE TABLE public.app_payments_slip (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id uuid,
    slip_id uuid,
    slip_installment_id_previous uuid,
    slip_installment_control bigint DEFAULT 0,
    slip_installment_available integer DEFAULT 0,
    slip_installment integer DEFAULT 0,
    installment_description character varying(255),
    installment_date_due timestamp(0) without time zone,
    installment_pay_type character varying(255),
    installment_value integer DEFAULT 0,
    status character varying(255),
    paid_datetime timestamp(0) without time zone,
    paid_value integer DEFAULT 0,
    paid_label character varying(255),
    payment_id uuid,
    order_ref character varying DEFAULT 'app_event'::character varying,
    order_id uuid,
    installment_value_fees integer DEFAULT 0,
    installment_value_liquid integer DEFAULT 0,
    installment_value_amortization integer DEFAULT 0,
    installment_fee_percentage_used double precision
);

COMMENT ON COLUMN public.app_payments_slip.slip_id IS 'CARNE ONLINE ID COMUM A TODOS';

COMMENT ON COLUMN public.app_payments_slip.slip_installment_id_previous IS 'PARCELA ANTERIOR';

COMMENT ON COLUMN public.app_payments_slip.slip_installment_control IS 'TIMESTAMP SERA USADO COMO GARANTIA ORDENAÇÃO';

COMMENT ON COLUMN public.app_payments_slip.slip_installment_available IS 'PARCELA DISPONIVEL PARA PAGAMENTO';

COMMENT ON COLUMN public.app_payments_slip.slip_installment IS 'NUMERO DA PARCELA';

COMMENT ON COLUMN public.app_payments_slip.installment_date_due IS 'DATA DE VENCIMENTO';

COMMENT ON COLUMN public.app_payments_slip.installment_pay_type IS 'TIPO DO PAGAMENTO SELECIONADO';

COMMENT ON COLUMN public.app_payments_slip.installment_value IS 'VALOR DA PERCELA';

CREATE TABLE public.app_sponsorship_orders (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_ref character varying(255) DEFAULT 'app_event'::character varying,
    app_ref_order_id uuid NOT NULL,
    channel_order character varying(255),
    channel_user_id uuid,
    status character varying(255),
    control character varying(255),
    amount integer,
    amount_pay integer,
    description character varying(255),
    cancel_datetime timestamp(0) without time zone,
    cancel_description character varying(255),
    sponsor_name character varying(255),
    sponsor_name_corporate character varying(255),
    sponsor_email character varying(255),
    sponsor_doc_type character varying(255),
    sponsor_doc_num character varying(255),
    sponsor_contact_country integer DEFAULT 55 NOT NULL,
    sponsor_contact_ddd integer,
    sponsor_contact_num integer,
    sponsor_json_answers text
);

COMMENT ON COLUMN public.app_sponsorship_orders.channel_order IS 'Canal onde a ordem foi gerada';

CREATE SEQUENCE IF NOT EXISTS public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE SEQUENCE IF NOT EXISTS public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE SEQUENCE IF NOT EXISTS public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.ref_app_states (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid,
    customer_id uuid,
    ref_slug character varying(255) NOT NULL,
    ref_value character varying(255) NOT NULL,
    ref_label character varying(255),
    ref_description character varying(255),
    ref_placeholder character varying(255),
    ref_options character varying(255),
    to_view boolean DEFAULT true NOT NULL,
    ref_icon character varying(255) DEFAULT 'check-circle'::character varying NOT NULL,
    ref_color character varying(255) DEFAULT 'white'::character varying NOT NULL,
    ref_color_bg character varying(255) DEFAULT 'gray-700'::character varying NOT NULL
);

CREATE SEQUENCE public.ref_app_estados_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.ref_app_estados_id_seq OWNED BY public.ref_app_states.id;

CREATE TABLE public.ref_app_event_category (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    ref_slug character varying(255) NOT NULL,
    ref_value character varying(255) NOT NULL,
    ref_label character varying(255),
    ref_description character varying(255),
    ref_placeholder character varying(255),
    ref_options character varying(255),
    to_view boolean DEFAULT true NOT NULL,
    ref_icon character varying(255) DEFAULT 'check-circle'::character varying NOT NULL,
    ref_color character varying(255) DEFAULT 'white'::character varying NOT NULL,
    ref_color_bg character varying(255) DEFAULT 'gray-700'::character varying NOT NULL
);

CREATE SEQUENCE public.ref_app_event_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.ref_app_event_category_id_seq OWNED BY public.ref_app_event_category.id;

CREATE TABLE public.ref_app_event_type (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    ref_slug character varying(255) NOT NULL,
    ref_value character varying(255) NOT NULL,
    ref_label character varying(255),
    ref_description character varying(255),
    ref_placeholder character varying(255),
    ref_options character varying(255),
    to_view boolean DEFAULT true NOT NULL,
    ref_icon character varying(255) DEFAULT 'check-circle'::character varying NOT NULL,
    ref_color character varying(255) DEFAULT 'white'::character varying NOT NULL,
    ref_color_bg character varying(255) DEFAULT 'gray-700'::character varying NOT NULL
);

CREATE SEQUENCE public.ref_app_event_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.ref_app_event_type_id_seq OWNED BY public.ref_app_event_type.id;

CREATE TABLE public.ref_app_notification_type (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    ref_slug character varying(255) NOT NULL,
    ref_value character varying(255) NOT NULL,
    ref_label character varying(255),
    ref_description character varying(255),
    ref_placeholder character varying(255),
    ref_options character varying(255),
    to_view boolean DEFAULT true NOT NULL,
    ref_icon character varying(255) DEFAULT 'check-circle'::character varying NOT NULL,
    ref_color character varying(255) DEFAULT 'white'::character varying NOT NULL,
    ref_color_bg character varying(255) DEFAULT 'gray-700'::character varying NOT NULL
);

CREATE SEQUENCE public.ref_app_notification_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.ref_app_notification_type_id_seq OWNED BY public.ref_app_notification_type.id;

CREATE TABLE public.ref_event_category (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid,
    customer_id uuid,
    ref_slug character varying(255) NOT NULL,
    ref_value character varying(255) NOT NULL,
    ref_label character varying(255),
    ref_description character varying(255),
    ref_placeholder character varying(255),
    ref_options character varying(255),
    to_view boolean DEFAULT true NOT NULL,
    ref_icon character varying(255) DEFAULT 'check-circle'::character varying NOT NULL,
    ref_color character varying(255) DEFAULT 'white'::character varying NOT NULL,
    ref_color_bg character varying(255) DEFAULT 'gray-700'::character varying NOT NULL
);

CREATE SEQUENCE public.ref_event_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.ref_event_category_id_seq OWNED BY public.ref_event_category.id;

CREATE TABLE public.ref_event_status (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid,
    customer_id uuid,
    ref_slug character varying(255) NOT NULL,
    ref_value character varying(255) NOT NULL,
    ref_label character varying(255),
    ref_description character varying(255),
    ref_placeholder character varying(255),
    ref_options character varying(255),
    to_view boolean DEFAULT true NOT NULL,
    ref_icon character varying(255) DEFAULT 'check-circle'::character varying NOT NULL,
    ref_color character varying(255) DEFAULT 'white'::character varying NOT NULL,
    ref_color_bg character varying(255) DEFAULT 'gray-700'::character varying NOT NULL
);

CREATE SEQUENCE public.ref_event_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.ref_event_status_id_seq OWNED BY public.ref_event_status.id;

CREATE TABLE public.ref_event_type (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid,
    customer_id uuid,
    ref_slug character varying(255) NOT NULL,
    ref_value character varying(255) NOT NULL,
    ref_label character varying(255),
    ref_description character varying(255),
    ref_placeholder character varying(255),
    ref_options character varying(255),
    to_view boolean DEFAULT true NOT NULL,
    ref_icon character varying(255) DEFAULT 'check-circle'::character varying NOT NULL,
    ref_color character varying(255) DEFAULT 'white'::character varying NOT NULL,
    ref_color_bg character varying(255) DEFAULT 'gray-700'::character varying NOT NULL
);

CREATE SEQUENCE public.ref_event_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.ref_event_type_id_seq OWNED BY public.ref_event_type.id;



CREATE TABLE public.tb_app (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_name character varying(255),
    app_description character varying(255),
    app_license character varying(255),
    app_limit_date date,
    app_active boolean DEFAULT true NOT NULL,
    owner_name character varying(255),
    owner_email character varying(255),
    owner_phone_country integer DEFAULT 55 NOT NULL,
    owner_phone_ddd integer,
    owner_phone_num integer,
    url_base character varying(255),
    domain_primary character varying(255),
    domain_aliases text,
    color_primary character varying(7) DEFAULT '#1a202c'::character varying,
    color_secondary character varying(7) DEFAULT '#2d3748'::character varying,
    color_accent character varying(7) DEFAULT '#3182ce'::character varying,
    url_image_logo_dark character varying(255),
    url_image_favicon character varying(255),
    email_from_name character varying(100),
    email_from_address character varying(100),
    email_reply_to character varying(100),
    meta_title text,
    meta_description text,
    meta_keywords character varying(500),
    meta_image character varying(255),
    settings json,
    branding_updated_at timestamp(0) without time zone,
    url_image_logo character varying(255)
);

COMMENT ON COLUMN public.tb_app.domain_primary IS 'Domínio principal do app (ex: proeventpay.com)';

COMMENT ON COLUMN public.tb_app.domain_aliases IS 'Domínios alternativos (JSON array)';

COMMENT ON COLUMN public.tb_app.color_primary IS 'Cor primária do tema (hex)';

COMMENT ON COLUMN public.tb_app.color_secondary IS 'Cor secundária do tema (hex)';

COMMENT ON COLUMN public.tb_app.color_accent IS 'Cor de destaque/botões (hex)';

COMMENT ON COLUMN public.tb_app.url_image_logo_dark IS 'Logo para modo escuro';

COMMENT ON COLUMN public.tb_app.url_image_favicon IS 'Favicon do app';

COMMENT ON COLUMN public.tb_app.email_from_name IS 'Nome do remetente nos e-mails';

COMMENT ON COLUMN public.tb_app.email_from_address IS 'E-mail remetente';

COMMENT ON COLUMN public.tb_app.email_reply_to IS 'E-mail para resposta';

COMMENT ON COLUMN public.tb_app.meta_title IS 'Título padrão para SEO';

COMMENT ON COLUMN public.tb_app.meta_description IS 'Descrição padrão para SEO';

COMMENT ON COLUMN public.tb_app.meta_keywords IS 'Keywords para SEO';

COMMENT ON COLUMN public.tb_app.meta_image IS 'Imagem padrão para compartilhamento social';

COMMENT ON COLUMN public.tb_app.settings IS 'Configurações extras em JSON';

COMMENT ON COLUMN public.tb_app.branding_updated_at IS 'Última atualização do branding';

COMMENT ON COLUMN public.tb_app.url_image_logo IS 'URL do logo principal do app';

CREATE TABLE public.tb_app_faturamento (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    pay_status character varying(255) NOT NULL,
    pay_amont integer DEFAULT 0 NOT NULL,
    pay_date date,
    pay_nfe_url text,
    pay_nfe_cnpj character varying,
    tipo_faturamento character varying,
    vendas_valor_ticket integer DEFAULT 0 NOT NULL,
    vendas_qtd_max integer DEFAULT 0 NOT NULL,
    vendas_valor_total integer DEFAULT 0 NOT NULL,
    descricao character varying(255),
    valor integer DEFAULT 0 NOT NULL,
    qtd_parcelas integer DEFAULT 0 NOT NULL,
    nota_fiscal character varying
);

CREATE TABLE public.tb_app_faturamento_pagamentos (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    faturamento_id uuid NOT NULL,
    pay_status character varying(255) DEFAULT 'pendente'::character varying NOT NULL,
    pay_descricao character varying(255) NOT NULL,
    pay_tipo character varying(255) DEFAULT 'boleto'::character varying NOT NULL,
    pay_valor integer DEFAULT 0 NOT NULL,
    pay_data_vencimento date,
    pay_boleto_url text,
    pay_data date,
    pay_ref integer
);

CREATE TABLE public.tb_app_modules (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid,
    module_name character varying(255) NOT NULL,
    module_description character varying(255),
    module_active boolean DEFAULT true NOT NULL,
    slug character varying(255),
    model_name character varying(255),
    singular_name character varying(255)
);

CREATE TABLE public.tb_app_pay_gateways (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid NOT NULL,
    gateway_slug character varying(255) NOT NULL,
    gateway_name character varying(255) NOT NULL,
    gateway_description character varying(255),
    gateway_installment_fees_json character varying(255),
    token_live character varying(255),
    token_live_secret character varying(255),
    token_test character varying(255),
    token_test_secret character varying(255),
    split_live_recipient_id character varying(255),
    split_test_recipient_id character varying(255),
    pay_boleto integer DEFAULT 1,
    pay_pix integer DEFAULT 1,
    pay_card_credit integer DEFAULT 1,
    pay_card_debit integer DEFAULT 0,
    pay_slip_pix integer DEFAULT 0,
    pay_slip_pix_fees_json text,
    pay_slip_pix_split_receiver_id integer,
    pay_slip_pix_split_receiver_name character varying
);

COMMENT ON COLUMN public.tb_app_pay_gateways.gateway_installment_fees_json IS 'JSON com as taxas fornecidas pelo gateway';

COMMENT ON COLUMN public.tb_app_pay_gateways.pay_slip_pix_split_receiver_id IS '''ID DA CONTA SAFE QUE RECEBERÁ O SPLIT''';

COMMENT ON COLUMN public.tb_app_pay_gateways.pay_slip_pix_split_receiver_name IS '''NOME DA CONTA SAFE QUE RECEBERÁ O SPLIT''';

CREATE TABLE public.tb_customers (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid NOT NULL,
    customer_slug character varying(255) NOT NULL,
    prefix_url character varying(255) NOT NULL,
    name_corporate character varying(255) NOT NULL,
    name_fantasy character varying(255),
    doc_type character varying(255) NOT NULL,
    doc_num character varying(255) NOT NULL,
    comercial_contact_name character varying(255) NOT NULL,
    comercial_contact_email character varying(255) NOT NULL,
    comercial_contact_country integer DEFAULT 55 NOT NULL,
    comercial_contact_ddd integer NOT NULL,
    comercial_contact_num integer NOT NULL,
    financial_contact_name character varying(255),
    financial_contact_email character varying(255),
    financial_contact_country integer DEFAULT 55 NOT NULL,
    financial_contact_ddd integer,
    financial_contact_num integer,
    address character varying(255),
    address_number character varying(255),
    address_complement character varying(255),
    address_reference character varying(255),
    city_neighborhood character varying(255),
    city character varying(255),
    state character varying(255),
    zip_code character varying(255),
    url_image_logo character varying(255),
    url_image_thumbnail character varying(255),
    url_image character varying(255),
    url_image_bg character varying(255),
    url_site character varying(255),
    url_instagram character varying(255),
    url_facebook character varying(255),
    country character varying DEFAULT 'brasil'::character varying,
    name_short character varying,
    generate_invoice integer DEFAULT 0,
    CONSTRAINT tb_customers_doc_type_check CHECK (((doc_type)::text = ANY (ARRAY[('CPF'::character varying)::text, ('CNPJ'::character varying)::text])))
);

COMMENT ON COLUMN public.tb_customers.prefix_url IS 'Prefixo portal';

CREATE TABLE public.tb_customers_app_modules (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    module_id uuid NOT NULL
);

CREATE TABLE public.tb_customers_organizations (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    organization_slug character varying(255) NOT NULL,
    organization_name character varying(255) NOT NULL,
    organization_description character varying(255),
    organization_url_image_logo character varying(255),
    organization_url_image_thumbnail character varying(255),
    organization_url_image character varying(255),
    organization_url_image_bg character varying(255),
    organization_doc_tipo character varying DEFAULT 'cnpj'::character varying,
    organization_doc_num character varying,
    organization_razao_social character varying
);

CREATE TABLE public.tb_customers_organizations_places (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    organization_id uuid NOT NULL,
    place_name character varying(255) NOT NULL,
    place_description character varying(255),
    address character varying(255),
    address_number character varying(255),
    address_complement character varying(255),
    address_reference character varying(255),
    city_neighborhood character varying(255),
    city character varying(255),
    state character varying(255),
    zip_code character varying(255),
    iframe_google_maps text,
    cod_latitude character varying(255),
    cod_longitude character varying(255),
    place_slug character varying
);

CREATE TABLE public.tb_customers_organizations_subs (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    organization_id uuid NOT NULL,
    organization_sub_name character varying(255) NOT NULL,
    organization_sub_description character varying(255),
    organization_sub_url_image_logo character varying(255),
    organization_sub_url_image_thumbnail character varying(255),
    organization_sub_url_image character varying(255),
    organization_sub_url_image_bg character varying(255),
    organization_sub_slug character varying
);

CREATE TABLE public.tb_customers_organizers (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    organization_id uuid,
    organization_sub_id uuid,
    organizer_slug character varying(255) NOT NULL,
    organizer_name character varying(255) NOT NULL,
    organizer_name_full character varying(255) NOT NULL,
    owner_name character varying(255),
    owner_email character varying(255),
    owner_phone_country character varying(255),
    owner_phone_ddd character varying(255),
    owner_phone_num character varying(255),
    url_image_logo character varying(255),
    url_image_thumbnail character varying(255),
    url_image character varying(255),
    url_image_bg character varying(255),
    url_site character varying(255),
    url_instagram character varying(255),
    url_facebook character varying(255),
    customer_pay_gateway_id uuid,
    customer_pay_gateway_seller_recipient_id character varying(255)
);

COMMENT ON COLUMN public.tb_customers_organizers.customer_pay_gateway_seller_recipient_id IS 'Referencia do Seller que vai receber/adm os pagamentos desse organizador';

CREATE TABLE public.tb_customers_pay_gateways (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    pay_gateway_id uuid NOT NULL,
    pay_gateway_slug character varying(255),
    pay_gateway_label character varying(255),
    pay_gateway_description character varying(255),
    pay_gateway_installment_fees_json text,
    pay_gateway_direct_client boolean DEFAULT false NOT NULL,
    pay_boleto boolean DEFAULT false NOT NULL,
    pay_pix boolean DEFAULT false NOT NULL,
    pay_card_debit boolean DEFAULT false NOT NULL,
    pay_card_credit boolean DEFAULT false NOT NULL,
    pay_card_credit_installment_max integer DEFAULT 10 NOT NULL,
    pay_card_credit_installment_amount_min integer DEFAULT 100 NOT NULL,
    token_live character varying(255),
    token_test character varying(255),
    token_live_pass character varying,
    token_test_pass character varying,
    value_additional integer,
    percentage_anticipation character varying,
    apply_percentage_anticipation boolean DEFAULT true,
    apply_value_additional boolean DEFAULT false,
    apply_installment_fees boolean DEFAULT false,
    pay_active integer DEFAULT 0,
    pay_slip_pix integer DEFAULT 1,
    pay_slip_pix_installment_max integer DEFAULT 10,
    pay_slip_pix_installment_amount_min integer DEFAULT 100,
    pay_slip_pix_fees_json text,
    gateway_comment character varying,
    use_events boolean DEFAULT true NOT NULL,
    use_campaigns boolean DEFAULT true NOT NULL
);

COMMENT ON COLUMN public.tb_customers_pay_gateways.pay_gateway_direct_client IS 'true = Transaciona direto cliente / false = pagamentos passa pelo app';

COMMENT ON COLUMN public.tb_customers_pay_gateways.use_events IS 'Indica se o gateway deve ser usado em eventos';

COMMENT ON COLUMN public.tb_customers_pay_gateways.use_campaigns IS 'Indica se o gateway deve ser usado em campanhas';

CREATE TABLE public.tb_customers_pay_gateways_fees (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    pay_gateway_id uuid NOT NULL,
    pay_type character varying(255) NOT NULL,
    pay_installment integer DEFAULT 0 NOT NULL,
    percentage_fee double precision DEFAULT (0)::double precision NOT NULL,
    percentage_adjust double precision DEFAULT (0)::double precision NOT NULL,
    value_additional integer DEFAULT 0 NOT NULL,
    value_additional_apply boolean DEFAULT false NOT NULL
);

COMMENT ON COLUMN public.tb_customers_pay_gateways_fees.value_additional IS 'Valor adicional na parcela';

COMMENT ON COLUMN public.tb_customers_pay_gateways_fees.value_additional_apply IS 'Aplicar valor adicional na parcela';

CREATE TABLE public.tb_notificacoes (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    target_ref character varying(255) NOT NULL,
    target_id character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'criada'::character varying NOT NULL,
    envio_tipo character varying(255) DEFAULT 'email'::character varying NOT NULL,
    envio_nome character varying(255) NOT NULL,
    envio_descricao character varying(255),
    envio_assunto character varying(255) NOT NULL,
    envio_assunto_nome boolean DEFAULT false NOT NULL,
    envio_header text,
    envio_header_nome boolean DEFAULT false NOT NULL,
    envio_body text,
    envio_footer text,
    envio_url_logo text,
    envio_color_bg text,
    programado boolean,
    programado_datahora timestamp(0) without time zone,
    data_envio_ini timestamp(0) without time zone,
    data_envio_fim timestamp(0) without time zone
);

CREATE TABLE public.tb_notificacoes_envios (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    notificacao_id uuid NOT NULL,
    status character varying(255) NOT NULL,
    tipo character varying(255) NOT NULL,
    datahora timestamp(0) without time zone,
    destino character varying(255) NOT NULL,
    destino_nome character varying(255),
    assunto text,
    header text,
    body text,
    footer text,
    url_logo text,
    color_bg character varying(255)
);

CREATE TABLE public.tb_providers (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    organization_id uuid,
    organization_sub_id uuid,
    provider_slug character varying(255) NOT NULL,
    provider_name character varying(255) NOT NULL,
    provider_name_full character varying(255),
    provider_email character varying(255),
    provider_contact_country integer DEFAULT 55 NOT NULL,
    provider_contact_ddd integer,
    provider_contact_num integer,
    provider_contact_secondary_country integer DEFAULT 55 NOT NULL,
    provider_contact_secondary_ddd integer,
    provider_contact_secondary_num integer
);

CREATE TABLE public.tb_sponsorship (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid,
    organizer_id uuid,
    doc_type character varying(255),
    doc_num character varying(255),
    name character varying(255),
    segment character varying(255),
    description character varying(255),
    email character varying(255),
    contact_name character varying(255),
    contact_country integer DEFAULT 55,
    contact_ddd integer,
    contact_num integer,
    url_logo text,
    url_website text,
    url_instagram text
);

CREATE TABLE public.tbc_campaign (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    organization_id uuid,
    customer_organization_slug character varying(255),
    slug character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    name_short character varying(255),
    description text,
    about text,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    visibility_public boolean DEFAULT false NOT NULL,
    datetime_start timestamp(0) without time zone,
    datetime_finish timestamp(0) without time zone,
    goal_amount bigint,
    goal_leads integer,
    goal_conversions integer,
    amount_min bigint DEFAULT 1000 NOT NULL,
    color_primary character varying(20),
    color_secondary character varying(20),
    url_image_logo character varying(255),
    url_image_bg character varying(255),
    url_image_banner character varying(255),
    url_image_thumb character varying(255),
    pay_gateway_id uuid,
    pay_sandbox boolean DEFAULT false NOT NULL,
    pay_pix boolean DEFAULT false NOT NULL,
    pay_boleto boolean DEFAULT false NOT NULL,
    pay_card_credit boolean DEFAULT false NOT NULL,
    pay_card_credit_installment_max smallint,
    show_goal_amount boolean DEFAULT true NOT NULL,
    show_goal_leads boolean DEFAULT true NOT NULL,
    show_goal_conversions boolean DEFAULT true NOT NULL,
    show_progress boolean DEFAULT true NOT NULL,
    pay_card_credit_installment_fee_payer character varying(20) DEFAULT 'customer'::character varying NOT NULL,
    campaign_type character varying(20) DEFAULT 'doacao'::character varying NOT NULL,
    enable_questions boolean DEFAULT true NOT NULL,
    require_doc boolean DEFAULT true NOT NULL,
    allow_anonymous boolean DEFAULT false NOT NULL,
    pay_card_credit_installment_amount_min integer,
    organizer_id uuid,
    pay_pix_direto boolean DEFAULT false NOT NULL
);

COMMENT ON COLUMN public.tbc_campaign.amount_min IS 'Valor mínimo de participação em centavos (ex: 1000 = R$ 10,00)';

COMMENT ON COLUMN public.tbc_campaign.pay_card_credit_installment_fee_payer IS 'customer ou merchant - define quem paga os juros do parcelamento';

COMMENT ON COLUMN public.tbc_campaign.pay_card_credit_installment_amount_min IS 'Valor mínimo da parcela em centavos (ex: 12345 = R$ 123,45)';

CREATE TABLE public.tbc_campaign_metric (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_id uuid NOT NULL,
    date_ref date NOT NULL,
    leads_count integer DEFAULT 0 NOT NULL,
    clicks_count integer DEFAULT 0 NOT NULL,
    conversions_count integer DEFAULT 0 NOT NULL,
    revenue_amount bigint DEFAULT '0'::numeric NOT NULL
);

COMMENT ON COLUMN public.tbc_campaign_metric.revenue_amount IS 'Receita acumulada em centavos';

CREATE TABLE public.tbc_campaign_order (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_id uuid NOT NULL,
    order_control character varying(255) NOT NULL,
    buyer_name character varying(255),
    buyer_email character varying(255),
    buyer_doc_num character varying(255),
    buyer_contact_ddd character varying(255),
    buyer_contact_num character varying(255),
    amount_total bigint NOT NULL,
    amount_paid bigint DEFAULT '0'::numeric NOT NULL,
    amount_discount bigint DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    paid_at timestamp(0) without time zone,
    cancelled_at timestamp(0) without time zone,
    metadata json,
    buyer_id uuid,
    is_anonymous boolean DEFAULT false NOT NULL,
    ip_address character varying(45),
    user_agent text,
    referer text,
    current_payment_slip_id uuid,
    slip_group_id uuid
);

COMMENT ON COLUMN public.tbc_campaign_order.amount_total IS 'Valor total do pedido em centavos';

COMMENT ON COLUMN public.tbc_campaign_order.amount_paid IS 'Valor pago em centavos';

COMMENT ON COLUMN public.tbc_campaign_order.amount_discount IS 'Desconto aplicado em centavos';

CREATE TABLE public.tbc_campaign_order_answer (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_order_id uuid NOT NULL,
    campaign_question_id uuid NOT NULL,
    answer_value text NOT NULL
);

CREATE TABLE public.tbc_campaign_organizer (
    id uuid NOT NULL,
    customer_id uuid NOT NULL,
    organization_id uuid,
    organizer_slug character varying(255) NOT NULL,
    organizer_name character varying(255) NOT NULL,
    organizer_name_full character varying(255),
    owner_name character varying(255),
    owner_email character varying(255),
    owner_phone_country character varying(10),
    owner_phone_ddd character varying(5),
    owner_phone_num character varying(20),
    url_image_logo character varying(255),
    url_image_thumbnail character varying(255),
    url_image character varying(255),
    url_image_bg character varying(255),
    url_site character varying(255),
    url_instagram character varying(255),
    url_facebook character varying(255),
    customer_pay_gateway_id uuid,
    customer_pay_gateway_seller_recipient_id character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE TABLE public.tbc_campaign_payment (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_id uuid NOT NULL,
    campaign_order_id uuid NOT NULL,
    campaign_payment_slip_id uuid NOT NULL,
    slip_group_id uuid NOT NULL,
    installment_number integer,
    description character varying(255),
    customer_pay_gateway_id uuid,
    gateway_slug character varying(255),
    gateway_sandbox boolean DEFAULT false NOT NULL,
    pay_integration_type character varying(20),
    status character varying(50) DEFAULT 'pending'::character varying NOT NULL,
    status_old character varying(50),
    pay_type character varying(20),
    value_paid bigint DEFAULT '0'::bigint NOT NULL,
    value_fees bigint DEFAULT '0'::bigint NOT NULL,
    value_liquid bigint DEFAULT '0'::bigint NOT NULL,
    fee_percentage_used numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    pay_transaction_id character varying(255),
    pay_nsu character varying(100),
    paid_label character varying(100),
    paid_description text,
    pay_pix_key text,
    pay_pix_qr_code text,
    pay_pix_qr_code_url character varying(500),
    pay_pix_expires_at timestamp(0) without time zone,
    pay_pix_end_to_end_id character varying(100),
    pay_boleto_barcode character varying(100),
    pay_boleto_expiration_date date,
    pay_boleto_url character varying(500),
    pay_installments_number integer DEFAULT 1 NOT NULL,
    pay_installment_value bigint DEFAULT '0'::bigint NOT NULL,
    pay_card_first character varying(6),
    pay_card_last character varying(4),
    pay_card_name character varying(100),
    pay_card_brand character varying(50),
    pay_datetime timestamp(0) without time zone,
    paid_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    pay_json_request json,
    pay_json_response json
);

CREATE TABLE public.tbc_campaign_payment_attempt (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_id uuid NOT NULL,
    campaign_order_id uuid NOT NULL,
    campaign_payment_id uuid,
    pay_type character varying(20),
    gateway_slug character varying(255),
    status character varying(50) NOT NULL,
    error_message text,
    request_data json,
    response_data json,
    attempted_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE public.tbc_campaign_payment_slip (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_id uuid NOT NULL,
    campaign_order_id uuid NOT NULL,
    slip_group_id uuid NOT NULL,
    description character varying(255),
    status character varying(50) DEFAULT 'pending'::character varying NOT NULL,
    paid_at timestamp(0) without time zone,
    cancelled_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    total_amount bigint DEFAULT '0'::bigint NOT NULL,
    amount_paid bigint DEFAULT '0'::bigint NOT NULL,
    amount_fees bigint DEFAULT '0'::bigint NOT NULL,
    amount_liquid bigint DEFAULT '0'::bigint NOT NULL,
    installments_total integer DEFAULT 1 NOT NULL,
    installments_paid integer DEFAULT 0 NOT NULL,
    customer_pay_gateway_id uuid,
    gateway_slug character varying(255),
    gateway_sandbox boolean DEFAULT false NOT NULL,
    installment_control integer DEFAULT 1 NOT NULL,
    due_date timestamp(0) without time zone
);

COMMENT ON COLUMN public.tbc_campaign_payment_slip.installment_control IS 'Número de controle sequencial para ordenação das parcelas (1, 2, 3...)';

COMMENT ON COLUMN public.tbc_campaign_payment_slip.due_date IS 'Data de vencimento da parcela para controle e contato com doadores';

CREATE TABLE public.tbc_campaign_payment_webhook (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_id uuid,
    campaign_order_id uuid,
    campaign_payment_id uuid,
    gateway_slug character varying(255) NOT NULL,
    webhook_type character varying(50),
    external_transaction_id character varying(255),
    reference character varying(255),
    status character varying(50),
    amount bigint DEFAULT '0'::bigint NOT NULL,
    payload json,
    processing_status character varying(50) DEFAULT 'pending'::character varying NOT NULL,
    processing_error text,
    processed_at timestamp(0) without time zone
);

CREATE TABLE public.tbc_campaign_question (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    campaign_id uuid NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    question_type character varying(20) DEFAULT 'text'::character varying NOT NULL,
    question_text text NOT NULL,
    question_options text,
    is_required boolean DEFAULT false NOT NULL,
    placeholder character varying(255),
    help_text character varying(255)
);

CREATE TABLE public.tev_events (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    organizer_id uuid NOT NULL,
    event_slug character varying(255) NOT NULL,
    event_name character varying(255) NOT NULL,
    event_name_short character varying(255),
    event_description text,
    event_text_header text,
    event_text_footer text,
    notification_text_1 character varying(255),
    notification_text_2 character varying(255),
    notification_text_pos_btn character varying(255),
    active boolean DEFAULT false NOT NULL,
    status character varying(255),
    type character varying(255),
    category character varying(255),
    event_visibility_public character varying(255),
    event_datetime_label character varying(255),
    event_datetime_start timestamp(0) without time zone,
    event_datetime_finish timestamp(0) without time zone,
    event_tickets_nomenclature character varying(255) DEFAULT 'ingresso'::character varying NOT NULL,
    event_online boolean DEFAULT false NOT NULL,
    place_id uuid,
    address character varying(255),
    address_number character varying(255),
    address_complement character varying(255),
    address_reference character varying(255),
    city_neighborhood character varying(255),
    city character varying(255),
    state character varying(255),
    zip_code character varying(255),
    google_maps_iframe text,
    cod_latitude character varying(255),
    cod_longitude character varying(255),
    color_primary character varying(255) DEFAULT '#38bdf8'::character varying,
    color_secondary character varying(255) DEFAULT '#38bdf8'::character varying,
    color_default character varying(255) DEFAULT '#15803d'::character varying,
    url_image_logo text,
    url_image_thumbnail text,
    url_image text,
    url_image_bg text,
    questions_buyer_pre_purchase boolean DEFAULT true,
    questions_buyer_json text,
    questions_user_pre_purchase boolean DEFAULT true,
    questions_user_json text,
    pay_gateway_id uuid,
    pay_sandbox boolean DEFAULT false NOT NULL,
    pay_boleto boolean DEFAULT false,
    pay_pix boolean DEFAULT false,
    pay_card_debit boolean DEFAULT false,
    pay_card_credit boolean DEFAULT false,
    pay_card_credit_installment_max integer DEFAULT 1 NOT NULL,
    pay_card_credit_installment_amount_min integer DEFAULT 1000 NOT NULL,
    sales_label character varying(255) DEFAULT 'INGRESSOS'::character varying,
    sales_btn character varying DEFAULT 'COMPRAR INGRESSO'::character varying,
    sales_theme character varying DEFAULT 'gray'::character varying,
    sales_amount_max bigint,
    color_default_inverse character varying DEFAULT '#ffffff'::character varying,
    preview_summary integer DEFAULT 0 NOT NULL,
    preview_summary_json json,
    preview_summary_update timestamp(0) without time zone,
    preview_budget_management_entries integer DEFAULT 0 NOT NULL,
    preview_budget_management_entries_json json,
    preview_budget_management_outputs integer DEFAULT 0 NOT NULL,
    preview_budget_management_json json,
    preview_budget_management_update timestamp(0) without time zone,
    event_about text,
    pay_boleto_date_end timestamp(0) without time zone,
    status_old character varying(255),
    status_old_datetime timestamp(0) without time zone,
    sales_label_item character varying(255) DEFAULT 'participante'::character varying NOT NULL,
    sales_label_item_multiple character varying(255) DEFAULT 'participantes'::character varying NOT NULL,
    sales_items_per_purchase character varying(255) DEFAULT '1'::character varying NOT NULL,
    pay_limit_installment_date_event integer DEFAULT 0,
    pay_slip_pix integer DEFAULT 0,
    pay_slip_pix_installment_max_auto integer DEFAULT 0,
    pay_slip_pix_installment_max integer DEFAULT 1,
    pay_slip_pix_installment_max_days_before integer DEFAULT 1,
    pay_slip_pix_installment_amount_min integer DEFAULT 1000,
    pay_slip_pix_installment_max_event_date_finish integer DEFAULT 0
);

COMMENT ON COLUMN public.tev_events.event_tickets_nomenclature IS 'ingresso / inscrição / contribuição';

COMMENT ON COLUMN public.tev_events.pay_gateway_id IS 'gateway usado par aprocessar esse evento';

COMMENT ON COLUMN public.tev_events.pay_slip_pix_installment_max_days_before IS 'ULTIMA PARCELA ATE QUANTOS DIAS ANTES';

CREATE TABLE public.tev_events_budgets (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    budget_title character varying(255) NOT NULL,
    budget_subtitle character varying(255),
    budget_operation character varying(255) NOT NULL
);

CREATE TABLE public.tev_events_budgets_items (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    event_budget_id uuid NOT NULL,
    item_date date NOT NULL,
    item_name character varying(255) NOT NULL,
    item_label character varying(255),
    item_description character varying(255),
    item_operation character varying(255) DEFAULT 'add'::character varying NOT NULL,
    provider_id uuid,
    provider_name character varying(255),
    item_status character varying(255),
    item_qtd integer DEFAULT 0 NOT NULL,
    item_amount integer DEFAULT 0 NOT NULL,
    item_amount_total integer DEFAULT 0 NOT NULL,
    item_amount_investment integer DEFAULT 0 NOT NULL,
    item_amount_paid integer DEFAULT 0 NOT NULL,
    item_amount_liquid integer,
    user_id uuid
);

COMMENT ON COLUMN public.tev_events_budgets_items.item_operation IS 'add ou sub';

COMMENT ON COLUMN public.tev_events_budgets_items.item_status IS 'Situação do Item';

COMMENT ON COLUMN public.tev_events_budgets_items.item_qtd IS 'Quantidade do item';

COMMENT ON COLUMN public.tev_events_budgets_items.item_amount IS 'Valor do item';

COMMENT ON COLUMN public.tev_events_budgets_items.item_amount_total IS 'Valor total do item';

COMMENT ON COLUMN public.tev_events_budgets_items.item_amount_investment IS 'Valor investimento do item';

COMMENT ON COLUMN public.tev_events_budgets_items.item_amount_paid IS 'Valor pago do item';

CREATE TABLE public.tev_events_page (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    page_view boolean DEFAULT true NOT NULL,
    page_view_order integer DEFAULT 1 NOT NULL,
    page_title character varying(255),
    page_title_view boolean DEFAULT true NOT NULL,
    page_description character varying(255),
    page_description_view boolean DEFAULT true NOT NULL,
    page_color_bg character varying(255) DEFAULT 'bg-white'::character varying NOT NULL,
    page_color_text character varying(255) DEFAULT 'text-gray-800'::character varying NOT NULL,
    page_color_text_title character varying(255) DEFAULT 'text-gray-800'::character varying NOT NULL,
    page_color_text_description character varying(255) DEFAULT 'text-gray-800'::character varying NOT NULL,
    page_body text,
    page_footer text
);

CREATE TABLE public.tev_events_publishs (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    user_id uuid,
    publish_status character varying(255) DEFAULT 'pendente'::character varying NOT NULL,
    publish_control character varying(255) NOT NULL,
    publish_json_event character varying(255) NOT NULL,
    publish_datetime_start character varying(255),
    publish_datetime_finish character varying(255)
);

COMMENT ON COLUMN public.tev_events_publishs.user_id IS 'Responsavel pela publicação';

CREATE TABLE public.tev_events_sponsorship (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    slug character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255),
    about text,
    url_document_plan text,
    visible boolean DEFAULT true NOT NULL,
    sponsorship_json_questions text
);

CREATE TABLE public.tev_events_sponsorship_plans (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    sponsorship_id uuid NOT NULL,
    slug character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255),
    price integer DEFAULT 0,
    installments_max integer DEFAULT 1,
    installments_fees_pay boolean DEFAULT false,
    amount integer DEFAULT 0,
    amount_sales integer DEFAULT 0,
    plan_active boolean DEFAULT true,
    pay_pix boolean DEFAULT false,
    pay_credit boolean DEFAULT true,
    pay_boleto boolean DEFAULT false,
    pay_boleto_date_max timestamp(0) without time zone,
    data_finish timestamp(0) without time zone
);

COMMENT ON COLUMN public.tev_events_sponsorship_plans.installments_fees_pay IS 'Patrocinador paga as taxas';

COMMENT ON COLUMN public.tev_events_sponsorship_plans.amount IS 'Qtd.total';

COMMENT ON COLUMN public.tev_events_sponsorship_plans.amount_sales IS 'Qtd.vendida';

CREATE TABLE public.tev_events_tickets_codes_promo (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    event_ticket_id uuid,
    code_name character varying(255) NOT NULL,
    code_description character varying(255),
    discount_type character varying(255) DEFAULT 'percentage'::character varying NOT NULL,
    discount_value real DEFAULT (0)::double precision NOT NULL,
    code_active boolean,
    code_datetime_validade_start timestamp(0) without time zone,
    code_datetime_validade_finish timestamp(0) without time zone,
    code_use_amount bigint,
    code_use_amount_used bigint,
    code_used boolean,
    code_used_order_id uuid,
    generate_user_id uuid
);

COMMENT ON COLUMN public.tev_events_tickets_codes_promo.code_name IS 'Cumpom de desconto';

COMMENT ON COLUMN public.tev_events_tickets_codes_promo.discount_type IS 'Tipo: Percentual, valor';

COMMENT ON COLUMN public.tev_events_tickets_codes_promo.generate_user_id IS 'Usuário que gerou o codigo';

CREATE TABLE public.tev_events_tickets_sponsorships (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    sponsorship_slug character varying(255) NOT NULL,
    sponsorship_name character varying(255) NOT NULL,
    sponsorship_description character varying(255),
    price integer DEFAULT 0 NOT NULL,
    amount integer,
    notification_message character varying(255),
    notification_instruction_1 character varying(255),
    notification_instruction_2 character varying(255),
    notification_instruction_footer_1 character varying(255),
    notification_instruction_footer_2 character varying(255)
);

COMMENT ON COLUMN public.tev_events_tickets_sponsorships.amount IS 'Qtd de cotas de patrocinio. null = ilimitada';

CREATE TABLE public.tev_events_tickets_types (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event_id uuid NOT NULL,
    ticket_slug text NOT NULL,
    ticket_name text NOT NULL,
    ticket_description text,
    ticket_free boolean DEFAULT false,
    ticket_free_qtd integer,
    price double precision DEFAULT (0)::double precision NOT NULL,
    amount character varying(255) DEFAULT '0'::character varying NOT NULL,
    amount_sales character varying(255) DEFAULT '0'::character varying NOT NULL,
    visible boolean DEFAULT false NOT NULL,
    sale_period_type character varying(255) DEFAULT 'data'::character varying NOT NULL,
    sale_start_ticket_id_finish uuid,
    sale_start_datetime timestamp(0) without time zone,
    sale_finish_datetime timestamp(0) without time zone,
    sale_amount_min character varying(255) DEFAULT '1'::character varying NOT NULL,
    sale_amount_max character varying(255) DEFAULT '1'::character varying NOT NULL,
    sale_ticket_availability character varying(255) DEFAULT 'publico'::character varying NOT NULL,
    sale_label_title character varying(255) DEFAULT 'INGRESSO'::character varying,
    sale_label_btn character varying(255) DEFAULT 'COMPRAR'::character varying,
    sale_view_grid_pre boolean DEFAULT false,
    sale_view_grid_pos boolean DEFAULT false,
    color_primary character varying(255),
    color_secondary character varying(255),
    color_default character varying(255),
    url_image_thumbnail character varying(255),
    url_image character varying(255),
    url_image_bg character varying(255),
    questions_buyer_pre_purchase boolean DEFAULT false NOT NULL,
    questions_buyer_json character varying(255),
    questions_item_pre_purchase boolean DEFAULT false NOT NULL,
    questions_item_json character varying(255),
    lote_publico integer DEFAULT 1,
    view_order integer DEFAULT 1
);

COMMENT ON COLUMN public.tev_events_tickets_types.amount IS 'Qtd.total';

COMMENT ON COLUMN public.tev_events_tickets_types.amount_sales IS 'Qtd.vendida';

COMMENT ON COLUMN public.tev_events_tickets_types.sale_period_type IS 'Periodo de venda por data ou lote';

COMMENT ON COLUMN public.tev_events_tickets_types.sale_start_ticket_id_finish IS 'Se por lote id lote anterior';

COMMENT ON COLUMN public.tev_events_tickets_types.sale_ticket_availability IS 'Disponibilidade da venda: Público / e-mail / Link';

CREATE TABLE public.users (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    remember_token character varying(100),
    email_verified_at timestamp(0) without time zone,
    profile_photo_path character varying(2048),
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    birth_date date,
    doc_type character varying(255),
    doc_num character varying(255),
    contact_country integer DEFAULT 55 NOT NULL,
    contact_ddd integer,
    contact_num integer,
    CONSTRAINT users_doc_type_check CHECK (((doc_type)::text = ANY (ARRAY[('CPF'::character varying)::text, ('CNPJ'::character varying)::text])))
);

CREATE TABLE public.users_app (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    app_id uuid NOT NULL,
    user_id uuid NOT NULL,
    user_active boolean DEFAULT true NOT NULL,
    user_role character varying(255) DEFAULT 'owner'::character varying NOT NULL
);

CREATE TABLE public.users_campaign_organizer (
    id uuid NOT NULL,
    user_id uuid NOT NULL,
    organizer_id uuid NOT NULL,
    campaign_id uuid,
    user_active boolean DEFAULT true NOT NULL,
    user_role character varying(50) DEFAULT 'user'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE TABLE public.users_customer (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    customer_id uuid NOT NULL,
    user_id uuid NOT NULL,
    user_active boolean DEFAULT true NOT NULL,
    user_role character varying(255) DEFAULT 'user'::character varying NOT NULL,
    organization_id uuid,
    can_events boolean DEFAULT true NOT NULL,
    can_campaigns boolean DEFAULT false NOT NULL
);

CREATE TABLE public.users_customer_organization (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id uuid NOT NULL,
    organization_id uuid NOT NULL,
    user_active boolean DEFAULT true NOT NULL,
    user_role character varying(255) DEFAULT 'user'::character varying NOT NULL
);

CREATE TABLE public.users_customer_organization_sub (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id uuid NOT NULL,
    organization_sub_id uuid NOT NULL,
    user_active boolean DEFAULT true NOT NULL,
    user_role character varying(255) DEFAULT 'user'::character varying NOT NULL
);

CREATE TABLE public.users_customer_organizer (
    id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id uuid NOT NULL,
    organizer_id uuid NOT NULL,
    event_id uuid,
    user_active boolean DEFAULT true NOT NULL,
    user_role character varying(255) DEFAULT 'user'::character varying NOT NULL
);

COMMENT ON COLUMN public.users_customer_organizer.user_id IS 'Usuario que terá acesso a todo o organizador ou apenas ao evento especifico';

COMMENT ON COLUMN public.users_customer_organizer.organizer_id IS 'Associado ao organizador';

COMMENT ON COLUMN public.users_customer_organizer.event_id IS 'Acesso apenas a esse evento do organizador';

CREATE VIEW public.vw_callback_status AS
 SELECT pca.id,
    pca.created_at,
    pca.updated_at,
    pca.order_id,
    pca.payment_id,
    pca.ref_controle,
    pca.callback_type,
    pca.gateway_slug,
    pca.gateway_transaction_id,
    pca.gateway_msg,
    pca.value_paid,
    pca.nsu,
    aeo.order_control,
    pca.status AS callback_status,
    pca.status_old AS callback_status_old,
    aeo.status AS order_status,
    pca.postback_processed,
    pca.postback_processed_status,
    'EOF'::text AS eof
   FROM (public.app_payments_callbacks pca
     LEFT JOIN public.app_events_orders aeo ON ((pca.order_id = aeo.id)))
  WHERE ((pca.status)::text = 'autorizado'::text);

ALTER TABLE ONLY public.ref_app_event_category ALTER COLUMN id SET DEFAULT nextval('public.ref_app_event_category_id_seq'::regclass);

ALTER TABLE ONLY public.ref_app_event_type ALTER COLUMN id SET DEFAULT nextval('public.ref_app_event_type_id_seq'::regclass);

ALTER TABLE ONLY public.ref_app_notification_type ALTER COLUMN id SET DEFAULT nextval('public.ref_app_notification_type_id_seq'::regclass);

ALTER TABLE ONLY public.ref_app_states ALTER COLUMN id SET DEFAULT nextval('public.ref_app_estados_id_seq'::regclass);

ALTER TABLE ONLY public.ref_event_category ALTER COLUMN id SET DEFAULT nextval('public.ref_event_category_id_seq'::regclass);

ALTER TABLE ONLY public.ref_event_status ALTER COLUMN id SET DEFAULT nextval('public.ref_event_status_id_seq'::regclass);

ALTER TABLE ONLY public.ref_event_type ALTER COLUMN id SET DEFAULT nextval('public.ref_event_type_id_seq'::regclass);

SELECT pg_catalog.setval('public.ref_app_estados_id_seq', 1, false);

SELECT pg_catalog.setval('public.ref_app_event_category_id_seq', 1, false);

SELECT pg_catalog.setval('public.ref_app_event_type_id_seq', 5, true);

SELECT pg_catalog.setval('public.ref_app_notification_type_id_seq', 1, false);

SELECT pg_catalog.setval('public.ref_event_category_id_seq', 1, false);

SELECT pg_catalog.setval('public.ref_event_status_id_seq', 4, true);

SELECT pg_catalog.setval('public.ref_event_type_id_seq', 5, true);

ALTER TABLE ONLY public.app_buyers
    ADD CONSTRAINT app_buyers_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_config
    ADD CONSTRAINT app_config_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_events_notifications
    ADD CONSTRAINT app_events_notifications_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_events_orders_items
    ADD CONSTRAINT app_events_orders_items_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_events_orders
    ADD CONSTRAINT app_events_orders_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_payments_slip
    ADD CONSTRAINT app_events_orders_slip_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_events_organizers
    ADD CONSTRAINT app_events_organizers_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_notifica
    ADD CONSTRAINT app_notifica_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_payments_callbacks
    ADD CONSTRAINT app_payments_callbacks_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.app_sponsorship_orders
    ADD CONSTRAINT app_sponsorship_orders_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_app_states
    ADD CONSTRAINT ref_app_estados_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_app_states
    ADD CONSTRAINT ref_app_estados_ref_slug_unique UNIQUE (ref_slug);

ALTER TABLE ONLY public.ref_app_event_category
    ADD CONSTRAINT ref_app_event_category_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_app_event_category
    ADD CONSTRAINT ref_app_event_category_ref_slug_unique UNIQUE (ref_slug);

ALTER TABLE ONLY public.ref_app_event_type
    ADD CONSTRAINT ref_app_event_type_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_app_event_type
    ADD CONSTRAINT ref_app_event_type_ref_slug_unique UNIQUE (ref_slug);

ALTER TABLE ONLY public.ref_app_notification_type
    ADD CONSTRAINT ref_app_notification_type_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_app_notification_type
    ADD CONSTRAINT ref_app_notification_type_ref_slug_unique UNIQUE (ref_slug);

ALTER TABLE ONLY public.ref_event_category
    ADD CONSTRAINT ref_event_category_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_event_category
    ADD CONSTRAINT ref_event_category_ref_slug_unique UNIQUE (ref_slug);

ALTER TABLE ONLY public.ref_event_status
    ADD CONSTRAINT ref_event_status_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_event_status
    ADD CONSTRAINT ref_event_status_ref_slug_unique UNIQUE (ref_slug);

ALTER TABLE ONLY public.ref_event_type
    ADD CONSTRAINT ref_event_type_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.ref_event_type
    ADD CONSTRAINT ref_event_type_ref_slug_unique UNIQUE (ref_slug);



ALTER TABLE ONLY public.tb_app_faturamento_pagamentos
    ADD CONSTRAINT tb_app_faturamento_pagamentos_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_app_faturamento
    ADD CONSTRAINT tb_app_faturamento_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_app_modules
    ADD CONSTRAINT tb_app_modules_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_app_pay_gateways
    ADD CONSTRAINT tb_app_pay_gateways_app_id_gateway_slug_unique UNIQUE (app_id, gateway_slug);

ALTER TABLE ONLY public.tb_app_pay_gateways
    ADD CONSTRAINT tb_app_pay_gateways_gateway_slug_unique UNIQUE (gateway_slug);

ALTER TABLE ONLY public.tb_app_pay_gateways
    ADD CONSTRAINT tb_app_pay_gateways_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_app
    ADD CONSTRAINT tb_app_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers
    ADD CONSTRAINT tb_customers_app_id_customer_slug_unique UNIQUE (app_id, customer_slug);

ALTER TABLE ONLY public.tb_customers_app_modules
    ADD CONSTRAINT tb_customers_app_modules_customer_id_module_id_unique UNIQUE (customer_id, module_id);

ALTER TABLE ONLY public.tb_customers_app_modules
    ADD CONSTRAINT tb_customers_app_modules_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers_organizations
    ADD CONSTRAINT tb_customers_organizations_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers_organizations_places
    ADD CONSTRAINT tb_customers_organizations_places_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers_organizations_subs
    ADD CONSTRAINT tb_customers_organizations_subs_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers_organizers
    ADD CONSTRAINT tb_customers_organizers_organizer_slug_unique UNIQUE (organizer_slug);

ALTER TABLE ONLY public.tb_customers_organizers
    ADD CONSTRAINT tb_customers_organizers_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers_pay_gateways_fees
    ADD CONSTRAINT tb_customers_pay_gateways_fees_pay_gateway_id_pay_type_pay_inst UNIQUE (pay_gateway_id, pay_type, pay_installment);

ALTER TABLE ONLY public.tb_customers_pay_gateways_fees
    ADD CONSTRAINT tb_customers_pay_gateways_fees_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers_pay_gateways
    ADD CONSTRAINT tb_customers_pay_gateways_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_customers
    ADD CONSTRAINT tb_customers_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_notificacoes_envios
    ADD CONSTRAINT tb_notificacoes_envios_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_notificacoes
    ADD CONSTRAINT tb_notificacoes_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_providers
    ADD CONSTRAINT tb_providers_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tb_providers
    ADD CONSTRAINT tb_providers_provider_slug_unique UNIQUE (provider_slug);

ALTER TABLE ONLY public.tb_sponsorship
    ADD CONSTRAINT tb_sponsorship_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_metric
    ADD CONSTRAINT tbc_camp_metric_campaign_date_unique UNIQUE (campaign_id, date_ref);

ALTER TABLE ONLY public.tbc_campaign_metric
    ADD CONSTRAINT tbc_campaign_metric_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_order_answer
    ADD CONSTRAINT tbc_campaign_order_answer_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_order
    ADD CONSTRAINT tbc_campaign_order_order_control_unique UNIQUE (order_control);

ALTER TABLE ONLY public.tbc_campaign_order
    ADD CONSTRAINT tbc_campaign_order_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_organizer
    ADD CONSTRAINT tbc_campaign_organizer_organizer_slug_unique UNIQUE (organizer_slug);

ALTER TABLE ONLY public.tbc_campaign_organizer
    ADD CONSTRAINT tbc_campaign_organizer_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_payment_attempt
    ADD CONSTRAINT tbc_campaign_payment_attempt_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_payment
    ADD CONSTRAINT tbc_campaign_payment_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_payment_slip
    ADD CONSTRAINT tbc_campaign_payment_slip_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_payment_slip
    ADD CONSTRAINT tbc_campaign_payment_slip_slip_group_id_unique UNIQUE (slip_group_id);

ALTER TABLE ONLY public.tbc_campaign_payment_webhook
    ADD CONSTRAINT tbc_campaign_payment_webhook_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign
    ADD CONSTRAINT tbc_campaign_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign_question
    ADD CONSTRAINT tbc_campaign_question_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tbc_campaign
    ADD CONSTRAINT tbc_campaign_slug_unique UNIQUE (slug);

ALTER TABLE ONLY public.tev_events_budgets_items
    ADD CONSTRAINT tev_events_budgets_items_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events_budgets
    ADD CONSTRAINT tev_events_budgets_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_event_slug_unique UNIQUE (event_slug);

ALTER TABLE ONLY public.tev_events_page
    ADD CONSTRAINT tev_events_page_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events_publishs
    ADD CONSTRAINT tev_events_publishs_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events_sponsorship
    ADD CONSTRAINT tev_events_sponsorship_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events_sponsorship_plans
    ADD CONSTRAINT tev_events_sponsorship_plans_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events_tickets_codes_promo
    ADD CONSTRAINT tev_events_tickets_codes_promo_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events_tickets_sponsorships
    ADD CONSTRAINT tev_events_tickets_sponsorships_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.tev_events_tickets_types
    ADD CONSTRAINT tev_events_tickets_types_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users_app
    ADD CONSTRAINT users_app_app_id_user_id_unique UNIQUE (app_id, user_id);

ALTER TABLE ONLY public.users_app
    ADD CONSTRAINT users_app_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users_campaign_organizer
    ADD CONSTRAINT users_campaign_organizer_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users_campaign_organizer
    ADD CONSTRAINT users_campaign_organizer_user_id_organizer_id_unique UNIQUE (user_id, organizer_id);

ALTER TABLE ONLY public.users_customer_organization
    ADD CONSTRAINT users_customer_organization_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users_customer_organization_sub
    ADD CONSTRAINT users_customer_organization_sub_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users_customer_organizer
    ADD CONSTRAINT users_customer_organizer_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users_customer
    ADD CONSTRAINT users_customer_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

CREATE INDEX app_events_orders_order_control_idx ON public.app_events_orders USING btree (order_control);

CREATE INDEX app_events_organizers_slug_index ON public.app_events_organizers USING btree (slug);

CREATE INDEX idx_app_active ON public.tb_app USING btree (app_active);

CREATE INDEX idx_app_domain_primary ON public.tb_app USING btree (domain_primary);

CREATE INDEX idx_campaign_customer_id ON public.tbc_campaign USING btree (customer_id);

CREATE INDEX idx_customer_app_id ON public.tb_customers USING btree (app_id);

CREATE INDEX idx_order_campaign_id ON public.tbc_campaign_order USING btree (campaign_id);

CREATE INDEX tbc_campaign_customer_id_organization_id_index ON public.tbc_campaign USING btree (customer_id, organization_id);

CREATE INDEX tbc_campaign_customer_organization_slug_slug_index ON public.tbc_campaign USING btree (customer_organization_slug, slug);

CREATE INDEX tbc_campaign_metric_campaign_id_date_ref_index ON public.tbc_campaign_metric USING btree (campaign_id, date_ref);

CREATE INDEX tbc_campaign_order_answer_campaign_order_id_campaign_question_i ON public.tbc_campaign_order_answer USING btree (campaign_order_id, campaign_question_id);

CREATE INDEX tbc_campaign_order_buyer_email_index ON public.tbc_campaign_order USING btree (buyer_email);

CREATE INDEX tbc_campaign_order_buyer_id_index ON public.tbc_campaign_order USING btree (buyer_id);

CREATE INDEX tbc_campaign_order_campaign_id_status_index ON public.tbc_campaign_order USING btree (campaign_id, status);

CREATE INDEX tbc_campaign_order_current_payment_slip_id_index ON public.tbc_campaign_order USING btree (current_payment_slip_id);

CREATE INDEX tbc_campaign_order_order_control_index ON public.tbc_campaign_order USING btree (order_control);

CREATE INDEX tbc_campaign_order_slip_group_id_index ON public.tbc_campaign_order USING btree (slip_group_id);

CREATE INDEX tbc_campaign_organizer_customer_id_organization_id_index ON public.tbc_campaign_organizer USING btree (customer_id, organization_id);

CREATE INDEX tbc_campaign_organizer_id_index ON public.tbc_campaign USING btree (organizer_id);

CREATE INDEX tbc_campaign_payment_attempt_attempted_at_index ON public.tbc_campaign_payment_attempt USING btree (attempted_at);

CREATE INDEX tbc_campaign_payment_attempt_campaign_id_index ON public.tbc_campaign_payment_attempt USING btree (campaign_id);

CREATE INDEX tbc_campaign_payment_attempt_campaign_order_id_index ON public.tbc_campaign_payment_attempt USING btree (campaign_order_id);

CREATE INDEX tbc_campaign_payment_attempt_campaign_payment_id_index ON public.tbc_campaign_payment_attempt USING btree (campaign_payment_id);

CREATE INDEX tbc_campaign_payment_campaign_id_index ON public.tbc_campaign_payment USING btree (campaign_id);

CREATE INDEX tbc_campaign_payment_campaign_order_id_created_at_index ON public.tbc_campaign_payment USING btree (campaign_order_id, created_at);

CREATE INDEX tbc_campaign_payment_campaign_order_id_index ON public.tbc_campaign_payment USING btree (campaign_order_id);

CREATE INDEX tbc_campaign_payment_campaign_payment_slip_id_index ON public.tbc_campaign_payment USING btree (campaign_payment_slip_id);

CREATE INDEX tbc_campaign_payment_pay_nsu_index ON public.tbc_campaign_payment USING btree (pay_nsu);

CREATE INDEX tbc_campaign_payment_pay_transaction_id_index ON public.tbc_campaign_payment USING btree (pay_transaction_id);

CREATE INDEX tbc_campaign_payment_slip_campaign_id_index ON public.tbc_campaign_payment_slip USING btree (campaign_id);

CREATE INDEX tbc_campaign_payment_slip_campaign_order_id_index ON public.tbc_campaign_payment_slip USING btree (campaign_order_id);

CREATE INDEX tbc_campaign_payment_slip_group_id_index ON public.tbc_campaign_payment USING btree (slip_group_id);

CREATE INDEX tbc_campaign_payment_slip_slip_group_id_index ON public.tbc_campaign_payment_slip USING btree (slip_group_id);

CREATE INDEX tbc_campaign_payment_slip_status_index ON public.tbc_campaign_payment_slip USING btree (status);

CREATE INDEX tbc_campaign_payment_status_index ON public.tbc_campaign_payment USING btree (status);

CREATE INDEX tbc_campaign_payment_webhook_campaign_id_index ON public.tbc_campaign_payment_webhook USING btree (campaign_id);

CREATE INDEX tbc_campaign_payment_webhook_campaign_order_id_index ON public.tbc_campaign_payment_webhook USING btree (campaign_order_id);

CREATE INDEX tbc_campaign_payment_webhook_campaign_payment_id_index ON public.tbc_campaign_payment_webhook USING btree (campaign_payment_id);

CREATE INDEX tbc_campaign_payment_webhook_created_at_index ON public.tbc_campaign_payment_webhook USING btree (created_at);

CREATE INDEX tbc_campaign_payment_webhook_external_transaction_id_index ON public.tbc_campaign_payment_webhook USING btree (external_transaction_id);

CREATE INDEX tbc_campaign_payment_webhook_processing_status_index ON public.tbc_campaign_payment_webhook USING btree (processing_status);

CREATE INDEX tbc_campaign_payment_webhook_reference_index ON public.tbc_campaign_payment_webhook USING btree (reference);

CREATE INDEX tbc_campaign_question_campaign_id_order_index ON public.tbc_campaign_question USING btree (campaign_id, "order");

CREATE INDEX tbc_campaign_status_datetime_start_datetime_finish_index ON public.tbc_campaign USING btree (status, datetime_start, datetime_finish);

CREATE INDEX users_campaign_organizer_organizer_id_user_active_index ON public.users_campaign_organizer USING btree (organizer_id, user_active);

ALTER TABLE ONLY public.app_config
    ADD CONSTRAINT app_config_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.app_events_notifications
    ADD CONSTRAINT app_events_notifications_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.app_events_orders(id);

ALTER TABLE ONLY public.app_events_orders
    ADD CONSTRAINT app_events_orders_buyer_id_foreign FOREIGN KEY (buyer_id) REFERENCES public.app_buyers(id);

ALTER TABLE ONLY public.app_events_orders
    ADD CONSTRAINT app_events_orders_channel_user_id_foreign FOREIGN KEY (channel_user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.app_events_orders_items
    ADD CONSTRAINT app_events_orders_items_item_ticket_type_id_foreign FOREIGN KEY (item_ticket_type_id) REFERENCES public.tev_events_tickets_types(id);

ALTER TABLE ONLY public.app_events_orders
    ADD CONSTRAINT app_events_orders_order_item_ticket_type_id_foreign FOREIGN KEY (order_items_ticket_type_id) REFERENCES public.tev_events_tickets_types(id);

ALTER TABLE ONLY public.app_payments_slip
    ADD CONSTRAINT app_events_orders_slip_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.app_events_orders_sponsorship
    ADD CONSTRAINT app_events_orders_sponsorship_channel_user_id_foreign FOREIGN KEY (channel_user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.app_events_orders_sponsorship
    ADD CONSTRAINT app_events_orders_sponsorship_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.app_events_orders_sponsorship
    ADD CONSTRAINT app_events_orders_sponsorship_plan_id_foreign FOREIGN KEY (plan_id) REFERENCES public.tev_events_sponsorship_plans(id);

ALTER TABLE ONLY public.app_sponsorship_orders
    ADD CONSTRAINT app_sponsorship_orders_channel_user_id_foreign FOREIGN KEY (channel_user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.ref_app_states
    ADD CONSTRAINT ref_app_estados_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.ref_app_states
    ADD CONSTRAINT ref_app_estados_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.ref_event_category
    ADD CONSTRAINT ref_event_category_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.ref_event_category
    ADD CONSTRAINT ref_event_category_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.ref_event_status
    ADD CONSTRAINT ref_event_status_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.ref_event_status
    ADD CONSTRAINT ref_event_status_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.ref_event_type
    ADD CONSTRAINT ref_event_type_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.ref_event_type
    ADD CONSTRAINT ref_event_type_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_app_faturamento
    ADD CONSTRAINT tb_app_faturamento_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tb_app_faturamento_pagamentos
    ADD CONSTRAINT tb_app_faturamento_pagamentos_faturamento_id_foreign FOREIGN KEY (faturamento_id) REFERENCES public.tb_app_faturamento(id);

ALTER TABLE ONLY public.tb_app_modules
    ADD CONSTRAINT tb_app_modules_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.tb_app_pay_gateways
    ADD CONSTRAINT tb_app_pay_gateways_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.tb_customers
    ADD CONSTRAINT tb_customers_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.tb_customers_app_modules
    ADD CONSTRAINT tb_customers_app_modules_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_customers_app_modules
    ADD CONSTRAINT tb_customers_app_modules_module_id_foreign FOREIGN KEY (module_id) REFERENCES public.tb_app_modules(id);

ALTER TABLE ONLY public.tb_customers_organizations
    ADD CONSTRAINT tb_customers_organizations_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_customers_organizations_places
    ADD CONSTRAINT tb_customers_organizations_places_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.tb_customers_organizations(id);

ALTER TABLE ONLY public.tb_customers_organizations_subs
    ADD CONSTRAINT tb_customers_organizations_subs_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_customers_organizers
    ADD CONSTRAINT tb_customers_organizers_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_customers_organizers
    ADD CONSTRAINT tb_customers_organizers_customer_pay_gateway_id_foreign FOREIGN KEY (customer_pay_gateway_id) REFERENCES public.tb_customers_pay_gateways(id);

ALTER TABLE ONLY public.tb_customers_organizers
    ADD CONSTRAINT tb_customers_organizers_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.tb_customers_organizations(id);

ALTER TABLE ONLY public.tb_customers_organizers
    ADD CONSTRAINT tb_customers_organizers_organization_sub_id_foreign FOREIGN KEY (organization_sub_id) REFERENCES public.tb_customers_organizations_subs(id);

ALTER TABLE ONLY public.tb_customers_pay_gateways
    ADD CONSTRAINT tb_customers_pay_gateways_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_customers_pay_gateways_fees
    ADD CONSTRAINT tb_customers_pay_gateways_fees_pay_gateway_id_foreign FOREIGN KEY (pay_gateway_id) REFERENCES public.tb_customers_pay_gateways(id);

ALTER TABLE ONLY public.tb_customers_pay_gateways
    ADD CONSTRAINT tb_customers_pay_gateways_pay_gateway_id_foreign FOREIGN KEY (pay_gateway_id) REFERENCES public.tb_app_pay_gateways(id);

ALTER TABLE ONLY public.tb_notificacoes_envios
    ADD CONSTRAINT tb_notificacoes_envios_notificacao_id_foreign FOREIGN KEY (notificacao_id) REFERENCES public.tb_notificacoes(id);

ALTER TABLE ONLY public.tb_providers
    ADD CONSTRAINT tb_providers_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_providers
    ADD CONSTRAINT tb_providers_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.tb_customers_organizations(id);

ALTER TABLE ONLY public.tb_providers
    ADD CONSTRAINT tb_providers_organization_sub_id_foreign FOREIGN KEY (organization_sub_id) REFERENCES public.tb_customers_organizations_subs(id);

ALTER TABLE ONLY public.tb_sponsorship
    ADD CONSTRAINT tb_sponsorship_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tb_sponsorship
    ADD CONSTRAINT tb_sponsorship_organizer_id_foreign FOREIGN KEY (organizer_id) REFERENCES public.tb_customers_organizers(id);

ALTER TABLE ONLY public.tbc_campaign_order_answer
    ADD CONSTRAINT tbc_campaign_order_answer_campaign_order_id_foreign FOREIGN KEY (campaign_order_id) REFERENCES public.tbc_campaign_order(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_order_answer
    ADD CONSTRAINT tbc_campaign_order_answer_campaign_question_id_foreign FOREIGN KEY (campaign_question_id) REFERENCES public.tbc_campaign_question(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_order
    ADD CONSTRAINT tbc_campaign_order_current_payment_slip_id_foreign FOREIGN KEY (current_payment_slip_id) REFERENCES public.tbc_campaign_payment_slip(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.tbc_campaign_organizer
    ADD CONSTRAINT tbc_campaign_organizer_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign
    ADD CONSTRAINT tbc_campaign_organizer_id_foreign FOREIGN KEY (organizer_id) REFERENCES public.tbc_campaign_organizer(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.tbc_campaign_organizer
    ADD CONSTRAINT tbc_campaign_organizer_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.tb_customers_organizations(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.tbc_campaign_payment_attempt
    ADD CONSTRAINT tbc_campaign_payment_attempt_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.tbc_campaign(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment_attempt
    ADD CONSTRAINT tbc_campaign_payment_attempt_campaign_order_id_foreign FOREIGN KEY (campaign_order_id) REFERENCES public.tbc_campaign_order(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment_attempt
    ADD CONSTRAINT tbc_campaign_payment_attempt_campaign_payment_id_foreign FOREIGN KEY (campaign_payment_id) REFERENCES public.tbc_campaign_payment(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment
    ADD CONSTRAINT tbc_campaign_payment_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.tbc_campaign(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment
    ADD CONSTRAINT tbc_campaign_payment_campaign_order_id_foreign FOREIGN KEY (campaign_order_id) REFERENCES public.tbc_campaign_order(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment
    ADD CONSTRAINT tbc_campaign_payment_campaign_payment_slip_id_foreign FOREIGN KEY (campaign_payment_slip_id) REFERENCES public.tbc_campaign_payment_slip(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment
    ADD CONSTRAINT tbc_campaign_payment_customer_pay_gateway_id_foreign FOREIGN KEY (customer_pay_gateway_id) REFERENCES public.tb_customers_pay_gateways(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.tbc_campaign_payment_slip
    ADD CONSTRAINT tbc_campaign_payment_slip_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.tbc_campaign(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment_slip
    ADD CONSTRAINT tbc_campaign_payment_slip_campaign_order_id_foreign FOREIGN KEY (campaign_order_id) REFERENCES public.tbc_campaign_order(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment_slip
    ADD CONSTRAINT tbc_campaign_payment_slip_customer_pay_gateway_id_foreign FOREIGN KEY (customer_pay_gateway_id) REFERENCES public.tb_customers_pay_gateways(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.tbc_campaign_payment_webhook
    ADD CONSTRAINT tbc_campaign_payment_webhook_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.tbc_campaign(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment_webhook
    ADD CONSTRAINT tbc_campaign_payment_webhook_campaign_order_id_foreign FOREIGN KEY (campaign_order_id) REFERENCES public.tbc_campaign_order(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tbc_campaign_payment_webhook
    ADD CONSTRAINT tbc_campaign_payment_webhook_campaign_payment_id_foreign FOREIGN KEY (campaign_payment_id) REFERENCES public.tbc_campaign_payment(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.tbc_campaign_question
    ADD CONSTRAINT tbc_campaign_question_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.tbc_campaign(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.tev_events_budgets
    ADD CONSTRAINT tev_events_budgets_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events_budgets_items
    ADD CONSTRAINT tev_events_budgets_items_event_budget_id_foreign FOREIGN KEY (event_budget_id) REFERENCES public.tev_events_budgets(id);

ALTER TABLE ONLY public.tev_events_budgets_items
    ADD CONSTRAINT tev_events_budgets_items_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events_budgets_items
    ADD CONSTRAINT tev_events_budgets_items_provider_id_foreign FOREIGN KEY (provider_id) REFERENCES public.tb_providers(id);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_category_foreign FOREIGN KEY (category) REFERENCES public.ref_event_category(ref_slug);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_organizer_id_foreign FOREIGN KEY (organizer_id) REFERENCES public.tb_customers_organizers(id);

ALTER TABLE ONLY public.tev_events_page
    ADD CONSTRAINT tev_events_page_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_pay_gateway_id_foreign FOREIGN KEY (pay_gateway_id) REFERENCES public.tb_customers_pay_gateways(id);

ALTER TABLE ONLY public.tev_events_publishs
    ADD CONSTRAINT tev_events_publishs_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events_publishs
    ADD CONSTRAINT tev_events_publishs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.tev_events_sponsorship
    ADD CONSTRAINT tev_events_sponsorship_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events_sponsorship_plans
    ADD CONSTRAINT tev_events_sponsorship_plans_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events_sponsorship_plans
    ADD CONSTRAINT tev_events_sponsorship_plans_sponsorship_id_foreign FOREIGN KEY (sponsorship_id) REFERENCES public.tev_events_sponsorship(id);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_status_foreign FOREIGN KEY (status) REFERENCES public.ref_event_status(ref_slug);

ALTER TABLE ONLY public.tev_events_tickets_codes_promo
    ADD CONSTRAINT tev_events_tickets_codes_promo_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events_tickets_codes_promo
    ADD CONSTRAINT tev_events_tickets_codes_promo_event_ticket_id_foreign FOREIGN KEY (event_ticket_id) REFERENCES public.tev_events_tickets_types(id);

ALTER TABLE ONLY public.tev_events_tickets_sponsorships
    ADD CONSTRAINT tev_events_tickets_sponsorships_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events_tickets_types
    ADD CONSTRAINT tev_events_tickets_types_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.tev_events
    ADD CONSTRAINT tev_events_type_foreign FOREIGN KEY (type) REFERENCES public.ref_event_type(ref_slug);

ALTER TABLE ONLY public.users_app
    ADD CONSTRAINT users_app_app_id_foreign FOREIGN KEY (app_id) REFERENCES public.tb_app(id);

ALTER TABLE ONLY public.users_app
    ADD CONSTRAINT users_app_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.users_campaign_organizer
    ADD CONSTRAINT users_campaign_organizer_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.tbc_campaign(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.users_campaign_organizer
    ADD CONSTRAINT users_campaign_organizer_organizer_id_foreign FOREIGN KEY (organizer_id) REFERENCES public.tbc_campaign_organizer(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.users_campaign_organizer
    ADD CONSTRAINT users_campaign_organizer_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.users_customer
    ADD CONSTRAINT users_customer_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.tb_customers(id);

ALTER TABLE ONLY public.users_customer
    ADD CONSTRAINT users_customer_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.tb_customers_organizations(id);

ALTER TABLE ONLY public.users_customer_organization
    ADD CONSTRAINT users_customer_organization_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.tb_customers_organizations(id);

ALTER TABLE ONLY public.users_customer_organization_sub
    ADD CONSTRAINT users_customer_organization_sub_organization_sub_id_foreign FOREIGN KEY (organization_sub_id) REFERENCES public.tb_customers_organizations_subs(id);

ALTER TABLE ONLY public.users_customer_organization_sub
    ADD CONSTRAINT users_customer_organization_sub_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.users_customer_organization
    ADD CONSTRAINT users_customer_organization_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.users_customer_organizer
    ADD CONSTRAINT users_customer_organizer_event_id_foreign FOREIGN KEY (event_id) REFERENCES public.tev_events(id);

ALTER TABLE ONLY public.users_customer_organizer
    ADD CONSTRAINT users_customer_organizer_organizer_id_foreign FOREIGN KEY (organizer_id) REFERENCES public.tb_customers_organizers(id);

ALTER TABLE ONLY public.users_customer_organizer
    ADD CONSTRAINT users_customer_organizer_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);

ALTER TABLE ONLY public.users_customer
    ADD CONSTRAINT users_customer_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);

-- PostgreSQL database dump complete







