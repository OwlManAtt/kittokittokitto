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
-- Name: pet_specie_color_pet_specie_color_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('pet_specie_color_pet_specie_color_id_seq', 4, true);


--
-- Data for Name: pet_specie_color; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY pet_specie_color (pet_specie_color_id, color_name, color_img, base_color) FROM stdin;
1	Red	red.png	Y
3	Rozen	rozen.png	N
2	Blue	blue.png	Y
\.


--
-- PostgreSQL database dump complete
--

