--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- Name: cron_tab_cron_tab_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('cron_tab_cron_tab_id_seq', 2, true);


--
-- Data for Name: cron_tab; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY cron_tab (cron_tab_id, cron_class, cron_frequency_seconds, unixtime_next_run, enabled) FROM stdin;
1	Job_RestockShops	3600	1247720078	Y
2	Job_UserOnline	300	1247719854	Y
\.


--
-- PostgreSQL database dump complete
--

