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
-- Name: shop_restock_shop_restock_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('shop_restock_shop_restock_id_seq', 6, true);


--
-- Data for Name: shop_restock; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY shop_restock (shop_restock_id, shop_id, item_type_id, restock_frequency_seconds, unixtime_next_restock, min_price, max_price, min_quantity, max_quantity, store_quantity_cap) FROM stdin;
5	1	5	2800	1208733868	1000	5000	1	2	2
3	1	8	2600	1208733668	50	100	2	5	10
4	1	7	2600	1208733668	50	100	2	5	10
2	1	6	1200	1208732268	5	15	5	10	15
1	1	1	3600	1208734668	1	15	5	10	30
\.


--
-- PostgreSQL database dump complete
--

