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
-- Name: pet_specie_pet_specie_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('pet_specie_pet_specie_id_seq', 3, true);


--
-- Data for Name: pet_specie; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY pet_specie (pet_specie_id, specie_name, specie_descr, relative_image_dir, max_hunger, max_happiness, available) FROM stdin;
2	Zutto	Forever, forever, forever!	zutto	10	10	Y
1	Kitto	<p>Somehow, someone, somewhere!</p>	kitto	10	10	Y
\.


--
-- PostgreSQL database dump complete
--

