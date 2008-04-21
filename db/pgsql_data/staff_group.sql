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
-- Name: staff_group_staff_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('staff_group_staff_group_id_seq', 19, true);


--
-- Data for Name: staff_group; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY staff_group (staff_group_id, group_name, group_descr, show_staff_group, order_by) FROM stdin;
1	High Administrators	The High Administrators are the owner(s) of the site. It is their job to coordinate the efforts of all other staff members and keep the site on-course.	Y	-1
2	Moderators	Moderators keep the boards running smoothly and deal with user complaints.	Y	0
15	Writers	Writers create item descriptions and other backstory for the game.	Y	0
14	Artists	Artists. They draw pictures.	Y	0
\.


--
-- PostgreSQL database dump complete
--

