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
-- Name: board_board_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('board_board_id_seq', 7, true);


--
-- Data for Name: board; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY board (board_id, board_category_id, board_name, board_descr, board_locked, news_source, required_permission_id, order_by) FROM stdin;
1	1	News & Announcements	The latest news and annoucements are posted here.	Y	Y	0	1
2	1	General Chat	Discuss the KittoKittoKitto project here.	N	N	0	3
3	1	Suggestions / Bugs	Found a bug? Have a brilliant, earth-shattering idea? Tell us!	N	N	0	6
6	2	Groups	Organization for player-run groups.	N	N	0	1
4	3	Staff Board	This is a board restricted to staff members.	N	N	13	100
5	2	For Sale	Awesome deals on today	N	N	0	0
\.


--
-- PostgreSQL database dump complete
--

