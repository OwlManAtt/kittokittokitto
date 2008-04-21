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
-- Name: avatar_avatar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('avatar_avatar_id_seq', 2, true);


--
-- Data for Name: avatar; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY avatar (avatar_id, avatar_name, avatar_image, active) FROM stdin;
1	Kitto	kitto.png	Y
2	Zutto	zutto.png	Y
\.


--
-- PostgreSQL database dump complete
--

