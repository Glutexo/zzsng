--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.2
-- Dumped by pg_dump version 9.3beta2
-- Started on 2014-03-02 20:28:24 CET

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 186 (class 3079 OID 50372)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2300 (class 0 OID 0)
-- Dependencies: 186
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 170 (class 1259 OID 50377)
-- Name: exam_mistakes; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE exam_mistakes (
    term integer NOT NULL,
    "order" integer NOT NULL
);


ALTER TABLE public.exam_mistakes OWNER TO php;

--
-- TOC entry 171 (class 1259 OID 50380)
-- Name: exam_results; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE exam_results (
    cycle integer NOT NULL,
    hits integer DEFAULT 0 NOT NULL,
    mistakes integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.exam_results OWNER TO php;

--
-- TOC entry 172 (class 1259 OID 50385)
-- Name: exam_terms; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE exam_terms (
    term integer NOT NULL,
    "order" integer NOT NULL
);


ALTER TABLE public.exam_terms OWNER TO php;

--
-- TOC entry 173 (class 1259 OID 50388)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: php
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.groups_id_seq OWNER TO php;

--
-- TOC entry 174 (class 1259 OID 50390)
-- Name: groups; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE groups (
    id integer DEFAULT nextval('groups_id_seq'::regclass) NOT NULL,
    name character varying COLLATE pg_catalog."cs_CZ" NOT NULL,
    created timestamp with time zone NOT NULL,
    last_change timestamp with time zone NOT NULL
);


ALTER TABLE public.groups OWNER TO php;

--
-- TOC entry 175 (class 1259 OID 50397)
-- Name: languages_id_seq; Type: SEQUENCE; Schema: public; Owner: php
--

CREATE SEQUENCE languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.languages_id_seq OWNER TO php;

--
-- TOC entry 176 (class 1259 OID 50399)
-- Name: languages; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE languages (
    id integer DEFAULT nextval('languages_id_seq'::regclass) NOT NULL,
    name character varying COLLATE pg_catalog."cs_CZ" NOT NULL,
    "default" smallint DEFAULT 0 NOT NULL,
    created timestamp with time zone NOT NULL,
    last_change timestamp with time zone NOT NULL
);


ALTER TABLE public.languages OWNER TO php;

--
-- TOC entry 177 (class 1259 OID 50407)
-- Name: lessons_id_seq; Type: SEQUENCE; Schema: public; Owner: php
--

CREATE SEQUENCE lessons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lessons_id_seq OWNER TO php;

--
-- TOC entry 178 (class 1259 OID 50409)
-- Name: lessons; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE lessons (
    id integer DEFAULT nextval('lessons_id_seq'::regclass) NOT NULL,
    name character varying COLLATE pg_catalog."cs_CZ" NOT NULL,
    language integer NOT NULL,
    created timestamp with time zone NOT NULL,
    last_change timestamp with time zone NOT NULL,
    c_term_count integer,
    user_id integer NOT NULL
);


ALTER TABLE public.lessons OWNER TO php;

--
-- TOC entry 179 (class 1259 OID 50416)
-- Name: lessons_groups; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE lessons_groups (
    lekce integer NOT NULL,
    skupina integer NOT NULL
);


ALTER TABLE public.lessons_groups OWNER TO php;

--
-- TOC entry 180 (class 1259 OID 50419)
-- Name: states_id_seq; Type: SEQUENCE; Schema: public; Owner: php
--

CREATE SEQUENCE states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.states_id_seq OWNER TO php;

--
-- TOC entry 181 (class 1259 OID 50421)
-- Name: states; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE states (
    id integer DEFAULT nextval('states_id_seq'::regclass) NOT NULL,
    name character varying COLLATE pg_catalog."cs_CZ" NOT NULL,
    learned smallint DEFAULT 0 NOT NULL,
    problematic smallint DEFAULT 0 NOT NULL,
    created time without time zone NOT NULL,
    last_change time without time zone NOT NULL
);


ALTER TABLE public.states OWNER TO php;

--
-- TOC entry 182 (class 1259 OID 50430)
-- Name: terms_id_seq; Type: SEQUENCE; Schema: public; Owner: php
--

CREATE SEQUENCE terms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.terms_id_seq OWNER TO php;

--
-- TOC entry 183 (class 1259 OID 50432)
-- Name: terms; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE terms (
    id integer DEFAULT nextval('terms_id_seq'::regclass) NOT NULL,
    "order" integer NOT NULL,
    lesson integer NOT NULL,
    term character varying COLLATE pg_catalog."cs_CZ" NOT NULL,
    metadata character varying COLLATE pg_catalog."cs_CZ",
    translation character varying COLLATE pg_catalog."cs_CZ" NOT NULL,
    comment text COLLATE pg_catalog."cs_CZ",
    status integer DEFAULT 0 NOT NULL,
    created timestamp with time zone NOT NULL,
    last_change timestamp with time zone NOT NULL
);


ALTER TABLE public.terms OWNER TO php;

--
-- TOC entry 184 (class 1259 OID 50464)
-- Name: users; Type: TABLE; Schema: public; Owner: php; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    login character varying NOT NULL,
    token uuid,
    is_superuser boolean DEFAULT false NOT NULL
);


ALTER TABLE public.users OWNER TO php;

--
-- TOC entry 185 (class 1259 OID 50470)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: php
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO php;

--
-- TOC entry 2301 (class 0 OID 0)
-- Dependencies: 185
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: php
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 2153 (class 2604 OID 50472)
-- Name: id; Type: DEFAULT; Schema: public; Owner: php
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);

--
-- TOC entry 2292 (class 0 OID 50464)
-- Dependencies: 184
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: php
--

COPY users (id, login, token, is_superuser) FROM stdin;
1	Default user	\N	t
\.


--
-- TOC entry 2307 (class 0 OID 0)
-- Dependencies: 185
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: php
--

SELECT pg_catalog.setval('users_id_seq', 1, true);


--
-- TOC entry 2155 (class 2606 OID 50476)
-- Name: exam_results_pkey; Type: CONSTRAINT; Schema: public; Owner: php; Tablespace: 
--

ALTER TABLE ONLY exam_results
    ADD CONSTRAINT exam_results_pkey PRIMARY KEY (cycle);


--
-- TOC entry 2157 (class 2606 OID 50478)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: php; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 2159 (class 2606 OID 50480)
-- Name: languages_pkey; Type: CONSTRAINT; Schema: public; Owner: php; Tablespace: 
--

ALTER TABLE ONLY languages
    ADD CONSTRAINT languages_pkey PRIMARY KEY (id);


--
-- TOC entry 2162 (class 2606 OID 50482)
-- Name: lessons_pkey; Type: CONSTRAINT; Schema: public; Owner: php; Tablespace: 
--

ALTER TABLE ONLY lessons
    ADD CONSTRAINT lessons_pkey PRIMARY KEY (id);


--
-- TOC entry 2164 (class 2606 OID 50484)
-- Name: states_pkey; Type: CONSTRAINT; Schema: public; Owner: php; Tablespace: 
--

ALTER TABLE ONLY states
    ADD CONSTRAINT states_pkey PRIMARY KEY (id);


--
-- TOC entry 2166 (class 2606 OID 50492)
-- Name: terms_pkey; Type: CONSTRAINT; Schema: public; Owner: php; Tablespace: 
--

ALTER TABLE ONLY terms
    ADD CONSTRAINT terms_pkey PRIMARY KEY (id);


--
-- TOC entry 2169 (class 2606 OID 50494)
-- Name: users_id_pk; Type: CONSTRAINT; Schema: public; Owner: php; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_id_pk PRIMARY KEY (id);


--
-- TOC entry 2160 (class 1259 OID 50495)
-- Name: fki_lessons_user_id_fk; Type: INDEX; Schema: public; Owner: php; Tablespace: 
--

CREATE INDEX fki_lessons_user_id_fk ON lessons USING btree (user_id);


--
-- TOC entry 2167 (class 1259 OID 50496)
-- Name: user_token_idx; Type: INDEX; Schema: public; Owner: php; Tablespace: 
--

CREATE UNIQUE INDEX user_token_idx ON users USING btree (token);


--
-- TOC entry 2170 (class 2606 OID 50497)
-- Name: lessons_user_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: php
--

ALTER TABLE ONLY lessons
    ADD CONSTRAINT lessons_user_id_fk FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2014-03-02 20:28:25 CET

--
-- PostgreSQL database dump complete
--

