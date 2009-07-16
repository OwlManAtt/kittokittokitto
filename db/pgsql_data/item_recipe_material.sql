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
-- Name: item_recipe_material_item_recipe_material_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('item_recipe_material_item_recipe_material_id_seq', 2, true);


--
-- Data for Name: item_recipe_material; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY item_recipe_material (item_recipe_material_id, recipe_item_type_id, material_item_type_id, material_quantity) FROM stdin;
1	10	1	2
2	10	6	1
\.


--
-- PostgreSQL database dump complete
--

