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
-- Name: item_type_item_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('item_type_item_type_id_seq', 9, true);


--
-- Data for Name: item_type; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY item_type (item_type_id, item_name, item_descr, item_class_id, happiness_bonus, hunger_bonus, pet_specie_color_id, item_image) FROM stdin;
1	Red Apple	A delicious, healthy red apple.	1	0	3	0	apple.png
6	Grubby Bowl	This is an old wooden bowl. Since there is nothing else, your pet will have to amuse itself with this.	2	1	0	0	bowl.png
7	Red Paintbrush	This will turn your pet red.	3	0	0	1	red.png
8	Blue Paintbrush	This will turn your pet blue.	3	0	0	1	blue.png
5	Rozen Paintbrush	<p>The Rozen paintbrush is delicious paint. You must use it~desu!</p>	3	0	0	3	rozen.png
\.


--
-- PostgreSQL database dump complete
--

