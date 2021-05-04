--
-- PostgreSQL database dump
--

-- Dumped from database version 13.1 (Debian 13.1-1.pgdg100+1)
-- Dumped by pg_dump version 13.1 (Debian 13.1-1.pgdg100+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: connection_string; Type: TABLE; Schema: public; Owner: faq
--

CREATE TABLE public.connection_string (
    connection_string_id bigint NOT NULL,
    connection_string text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: connection_string_connection_string_id_seq; Type: SEQUENCE; Schema: public; Owner: faq
--

CREATE SEQUENCE public.connection_string_connection_string_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: connection_string_connection_string_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: faq
--

ALTER SEQUENCE public.connection_string_connection_string_id_seq OWNED BY public.connection_string.connection_string_id;


--
-- Name: data_source; Type: TABLE; Schema: public; Owner: faq
--

CREATE TABLE public.data_source (
    data_source_id bigint NOT NULL,
    data_source_type character varying(255) DEFAULT 'sql'::character varying NOT NULL,
    connection_string_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    data_source_sql text,
    CONSTRAINT data_source_data_source_type_check CHECK (((data_source_type)::text = 'sql'::text))
);



--
-- Name: data_source_data_source_id_seq; Type: SEQUENCE; Schema: public; Owner: faq
--

CREATE SEQUENCE public.data_source_data_source_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: data_source_data_source_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: faq
--

ALTER SEQUENCE public.data_source_data_source_id_seq OWNED BY public.data_source.data_source_id;


--
-- Name: element; Type: TABLE; Schema: public; Owner: faq
--

CREATE TABLE public.element (
    element_id bigint NOT NULL,
    element_type character varying(255) NOT NULL,
    data_source_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    element_config jsonb
);


--
-- Name: entity_entity_id_seq; Type: SEQUENCE; Schema: public; Owner: faq
--

CREATE SEQUENCE public.entity_entity_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: entity_entity_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: faq
--

ALTER SEQUENCE public.entity_entity_id_seq OWNED BY public.element.element_id;


--
-- Name: test_table; Type: TABLE; Schema: public; Owner: faq
--

CREATE TABLE public.test_table (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);



--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: faq
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: faq
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.test_table.id;


--
-- Name: connection_string connection_string_id; Type: DEFAULT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.connection_string ALTER COLUMN connection_string_id SET DEFAULT nextval('public.connection_string_connection_string_id_seq'::regclass);


--
-- Name: data_source data_source_id; Type: DEFAULT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.data_source ALTER COLUMN data_source_id SET DEFAULT nextval('public.data_source_data_source_id_seq'::regclass);


--
-- Name: element element_id; Type: DEFAULT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.element ALTER COLUMN element_id SET DEFAULT nextval('public.entity_entity_id_seq'::regclass);


--
-- Name: test_table id; Type: DEFAULT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.test_table ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: connection_string; Type: TABLE DATA; Schema: public; Owner: faq
--

COPY public.connection_string (connection_string_id, connection_string, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: data_source; Type: TABLE DATA; Schema: public; Owner: faq
--

COPY public.data_source (data_source_id, data_source_type, connection_string_id, created_at, updated_at, data_source_sql) FROM stdin;
2	sql	\N	2021-02-19 03:51:01	2021-02-19 03:51:01	\N
4	sql	\N	2021-02-19 03:52:41	2021-02-19 03:52:41	\N
7	sql	\N	2021-02-19 07:03:17	2021-02-19 07:04:00	SELECT * FROM ...
8	sql	\N	2021-02-19 07:52:18	2021-02-19 07:52:18	\N
9	sql	\N	2021-02-19 08:00:28	2021-02-19 08:00:28	\N
10	sql	\N	2021-02-19 08:00:28	2021-02-19 08:00:28	\N
11	sql	\N	2021-02-19 08:00:29	2021-02-19 08:00:29	\N
12	sql	\N	2021-02-19 08:01:14	2021-02-19 08:01:14	\N
13	sql	\N	2021-02-19 08:01:15	2021-02-19 08:01:15	\N
14	sql	\N	2021-02-19 08:02:09	2021-02-19 08:02:09	\N
3	sql	\N	2021-02-19 03:52:31	2021-02-19 06:14:42	SELECT * FROM "users";
5	sql	\N	2021-02-19 06:57:16	2021-02-19 06:57:16	\N
6	sql	\N	2021-02-19 06:58:03	2021-02-19 06:58:03	\N
\.


--
-- Data for Name: element; Type: TABLE DATA; Schema: public; Owner: faq
--

COPY public.element (element_id, element_type, data_source_id, created_at, updated_at, element_config) FROM stdin;
5	chart	3	2021-02-19 03:52:31	2021-02-19 07:00:59	{"metric": "percent", "dimension": "year", "breakdownDimension": 3}
6	chart	4	2021-02-19 03:52:41	2021-02-19 07:01:10	{"metric": "percent", "dimension": "year", "breakdownDimension": 0}
7	chart	5	2021-02-19 06:57:16	2021-02-19 07:02:35	{"metric": "percent", "dimension": "name"}
8	chart	6	2021-02-19 06:58:03	2021-02-19 07:03:00	{"metric": "data", "dimension": "year"}
9	chart	7	2021-02-19 07:03:17	2021-02-19 07:03:25	{"metric": "data", "dimension": "year"}
10	chart	8	2021-02-19 07:52:18	2021-02-19 07:52:18	\N
11	chart	9	2021-02-19 08:00:28	2021-02-19 08:00:28	\N
12	chart	10	2021-02-19 08:00:29	2021-02-19 08:00:29	\N
13	chart	11	2021-02-19 08:00:29	2021-02-19 08:00:29	\N
14	chart	12	2021-02-19 08:01:14	2021-02-19 08:01:14	\N
15	chart	13	2021-02-19 08:01:15	2021-02-19 08:01:15	\N
16	text	14	2021-02-19 08:02:09	2021-02-19 08:02:09	{"text": null}
\.


--
-- Data for Name: test_table; Type: TABLE DATA; Schema: public; Owner: faq
--

COPY public.test_table (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
2	sad sad	dsadsad	2021-02-19 11:26:02	2	\N	\N	\N
\.


--
-- Name: connection_string_connection_string_id_seq; Type: SEQUENCE SET; Schema: public; Owner: faq
--

SELECT pg_catalog.setval('public.connection_string_connection_string_id_seq', 1, false);


--
-- Name: data_source_data_source_id_seq; Type: SEQUENCE SET; Schema: public; Owner: faq
--

SELECT pg_catalog.setval('public.data_source_data_source_id_seq', 14, true);


--
-- Name: entity_entity_id_seq; Type: SEQUENCE SET; Schema: public; Owner: faq
--

SELECT pg_catalog.setval('public.entity_entity_id_seq', 16, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: faq
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- Name: connection_string connection_string_pkey; Type: CONSTRAINT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.connection_string
    ADD CONSTRAINT connection_string_pkey PRIMARY KEY (connection_string_id);


--
-- Name: data_source data_source_pkey; Type: CONSTRAINT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.data_source
    ADD CONSTRAINT data_source_pkey PRIMARY KEY (data_source_id);


--
-- Name: element entity_pkey; Type: CONSTRAINT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.element
    ADD CONSTRAINT entity_pkey PRIMARY KEY (element_id);


--
-- Name: test_table users_email_unique; Type: CONSTRAINT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.test_table
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: test_table users_pkey; Type: CONSTRAINT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.test_table
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: entity_entity_type_index; Type: INDEX; Schema: public; Owner: faq
--

CREATE INDEX entity_entity_type_index ON public.element USING btree (element_type);


--
-- Name: data_source data_source_connection_string_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.data_source
    ADD CONSTRAINT data_source_connection_string_id_foreign FOREIGN KEY (connection_string_id) REFERENCES public.connection_string(connection_string_id);


--
-- Name: element entity_data_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: faq
--

ALTER TABLE ONLY public.element
    ADD CONSTRAINT entity_data_source_id_foreign FOREIGN KEY (data_source_id) REFERENCES public.data_source(data_source_id);


--
-- PostgreSQL database dump complete
--

