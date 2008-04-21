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
-- Name: board_category_board_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('board_category_board_category_id_seq', 3, true);


--
-- Data for Name: board_category; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY board_category (board_category_id, category_name, order_by, required_permission_id) FROM stdin;
1	General	0	0
2	Advertising	1	0
3	Private Boards	100	13
\.


--
-- PostgreSQL database dump complete
--

