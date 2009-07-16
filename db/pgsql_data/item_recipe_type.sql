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
-- Name: item_recipe_type_item_recipe_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('item_recipe_type_item_recipe_type_id_seq', 2, true);


--
-- Data for Name: item_recipe_type; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY item_recipe_type (item_recipe_type_id, recipe_type_description) FROM stdin;
1	Recipe
2	Blueprint
\.


--
-- PostgreSQL database dump complete
--

