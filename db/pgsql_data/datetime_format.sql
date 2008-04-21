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
-- Name: datetime_format_datetime_format_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('datetime_format_datetime_format_id_seq', 3, true);


--
-- Data for Name: datetime_format; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY datetime_format (datetime_format_id, datetime_format_name, datetime_format) FROM stdin;
1	YYYY-MM-DD 24:MM:SS	Y-m-d H:i:s
2	YYYY-MM-DD 12:MM:SS	Y-m-d g:i:s A
3	Mon, DD, YYYY 12:mm:ss	M j, Y g:i:s A
\.


--
-- PostgreSQL database dump complete
--

