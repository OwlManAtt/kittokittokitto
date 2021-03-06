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
-- Name: item_class_item_class_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('item_class_item_class_id_seq', 4, true);


--
-- Data for Name: item_class; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY item_class (item_class_id, php_class, class_descr, relative_image_dir, verb, one_per_use, normal_inventory_display) FROM stdin;
1	Food_Item	Food	food	feed	N	Y
2	Toy_Item	Toy	toys	play with	N	Y
3	Paint_Item	Paint	paints	paint	Y	Y
4	Recipe_Item	Recipe	recipe	creates	N	N
\.


--
-- PostgreSQL database dump complete
--

