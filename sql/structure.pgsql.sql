--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.4
-- Dumped by pg_dump version 9.2.4
-- Started on 2013-08-04 12:51:19 CEST

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 2255 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA "public"; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA "public" IS 'standard public schema';

SET search_path = "public", pg_catalog;

SET default_with_oids = false;

--
-- TOC entry 168 (class 1259 OID 16533)
-- Name: exam_mistakes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "exam_mistakes" (
    "term" integer NOT NULL,
    "order" integer NOT NULL
);


--
-- TOC entry 169 (class 1259 OID 16536)
-- Name: exam_results; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "exam_results" (
    "cycle" integer NOT NULL,
    "hits" integer DEFAULT 0 NOT NULL,
    "mistakes" integer DEFAULT 0 NOT NULL
);


--
-- TOC entry 170 (class 1259 OID 16543)
-- Name: exam_terms; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "exam_terms" (
    "term" integer NOT NULL,
    "order" integer NOT NULL
);


--
-- TOC entry 179 (class 1259 OID 16599)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "groups_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 171 (class 1259 OID 16546)
-- Name: groups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "groups" (
    "id" integer DEFAULT "nextval"('"groups_id_seq"'::"regclass") NOT NULL,
    "name" character varying COLLATE "pg_catalog"."cs_CZ" NOT NULL,
    "created" timestamp with time zone NOT NULL,
    "last_change" timestamp with time zone NOT NULL
);


--
-- TOC entry 178 (class 1259 OID 16596)
-- Name: languages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "languages_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 172 (class 1259 OID 16554)
-- Name: languages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "languages" (
    "id" integer DEFAULT "nextval"('"languages_id_seq"'::"regclass") NOT NULL,
    "name" character varying COLLATE "pg_catalog"."cs_CZ" NOT NULL,
    "default" smallint DEFAULT 0 NOT NULL,
    "created" timestamp with time zone NOT NULL,
    "last_change" timestamp with time zone NOT NULL
);


--
-- TOC entry 177 (class 1259 OID 16593)
-- Name: lessons_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "lessons_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 173 (class 1259 OID 16563)
-- Name: lessons; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "lessons" (
    "id" integer DEFAULT "nextval"('"lessons_id_seq"'::"regclass") NOT NULL,
    "name" character varying COLLATE "pg_catalog"."cs_CZ" NOT NULL,
    "language" integer NOT NULL,
    "created" timestamp with time zone NOT NULL,
    "last_change" timestamp with time zone NOT NULL,
    "c_term_count" integer
);


--
-- TOC entry 174 (class 1259 OID 16571)
-- Name: lessons_groups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "lessons_groups" (
    "lekce" integer NOT NULL,
    "skupina" integer NOT NULL
);


--
-- TOC entry 180 (class 1259 OID 16602)
-- Name: states_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "states_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 175 (class 1259 OID 16574)
-- Name: states; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "states" (
    "id" integer DEFAULT "nextval"('"states_id_seq"'::"regclass") NOT NULL,
    "name" character varying COLLATE "pg_catalog"."cs_CZ" NOT NULL,
    "learned" smallint DEFAULT 0 NOT NULL,
    "problematic" smallint DEFAULT 0 NOT NULL,
    "created" time without time zone NOT NULL,
    "last_change" time without time zone NOT NULL
);


--
-- TOC entry 181 (class 1259 OID 16605)
-- Name: terms_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "terms_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 176 (class 1259 OID 16584)
-- Name: terms; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "terms" (
    "id" integer DEFAULT "nextval"('"terms_id_seq"'::"regclass") NOT NULL,
    "order" integer NOT NULL,
    "lesson" integer NOT NULL,
    "term" character varying COLLATE "pg_catalog"."cs_CZ" NOT NULL,
    "metadata" character varying COLLATE "pg_catalog"."cs_CZ",
    "translation" character varying COLLATE "pg_catalog"."cs_CZ" NOT NULL,
    "comment" "text" COLLATE "pg_catalog"."cs_CZ",
    "status" integer DEFAULT 0 NOT NULL,
    "created" timestamp with time zone NOT NULL,
    "last_change" timestamp with time zone NOT NULL
);


--
-- TOC entry 2239 (class 2606 OID 16542)
-- Name: exam_results_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "exam_results"
    ADD CONSTRAINT "exam_results_pkey" PRIMARY KEY ("cycle");


--
-- TOC entry 2241 (class 2606 OID 16553)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "groups"
    ADD CONSTRAINT "groups_pkey" PRIMARY KEY ("id");


--
-- TOC entry 2243 (class 2606 OID 16562)
-- Name: languages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "languages"
    ADD CONSTRAINT "languages_pkey" PRIMARY KEY ("id");


--
-- TOC entry 2245 (class 2606 OID 16570)
-- Name: lessons_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "lessons"
    ADD CONSTRAINT "lessons_pkey" PRIMARY KEY ("id");


--
-- TOC entry 2247 (class 2606 OID 16583)
-- Name: states_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "states"
    ADD CONSTRAINT "states_pkey" PRIMARY KEY ("id");


--
-- TOC entry 2249 (class 2606 OID 16592)
-- Name: terms_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "terms"
    ADD CONSTRAINT "terms_pkey" PRIMARY KEY ("id");


-- Completed on 2013-08-04 12:51:20 CEST

--
-- PostgreSQL database dump complete
--

