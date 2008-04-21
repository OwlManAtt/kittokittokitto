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
-- Name: shop_shop_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('shop_shop_id_seq', 2, true);


--
-- Data for Name: shop; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY shop (shop_id, shop_name, shop_image, welcome_text) FROM stdin;
1	General Store	general_store.png	Welcome to the general store. We can supply you with anything you might need.
\.


--
-- PostgreSQL database dump complete
--

