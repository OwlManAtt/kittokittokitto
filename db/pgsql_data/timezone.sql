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
-- Name: timezone_timezone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('timezone_timezone_id_seq', 59, true);


--
-- Data for Name: timezone; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY timezone (timezone_id, timezone_short_name, timezone_long_name, timezone_continent, timezone_offset, order_by) FROM stdin;
1	ACDT	Australian Central Daylight Time	Australia	10.5	1
2	ACST	Australian Central Standard Time	Australia	9.5	1
3	ADT	Atlantic Daylight Time	North America	-3	1
4	AEDT	Australian Eastern Daylight Time	Australia	11	1
5	AEST	Australian Eastern Standard Time	Australia	10	1
6	AKDT	Alaska Daylight Time	North America	-8	1
7	AKST	Alaska Standard Time	North America	-9	1
8	AST	Atlantic Standard Time	North America	-4	1
9	AWDT	Australian Western Daylight Time	Australia	9	1
10	AWST	Australian Western Standard Time	Australia	8	1
11	BST	British Summer Time	Europe	1	1
12	CDT	Central Daylight Time	North America	-5	1
13	CEDT	Central European Daylight Time	Europe	2	1
14	CEST	Central European Summer Time	Europe	2	1
15	CET	Central European Time	Europe	1	1
16	CST	Central Summer (Daylight) Time	Australia	10.5	1
17	CST	Central Standard Time	Australia	9.5	1
18	CST	Central Standard Time	North America	-6	1
19	CXT	Christmas Island Time	Australia	7	1
20	EDT	Eastern Daylight Time	North America	-4	1
21	EEDT	Eastern European Daylight Time	Europe	3	1
22	EEST	Eastern European Summer Time	Europe	3	1
23	EET	Eastern European Time	Europe	2	1
24	EST	Eastern Summer (Daylight) Time	Australia	11	1
25	EST	Eastern Standard Time	Australia	10	1
26	EST	Eastern Standard Time	North America	-5	1
27	GMT	Greenwich Mean Time	Europe	0	1
28	HAA	Heure Avancee de l'Atlantique	North America	-3	1
29	HAC	Heure Avancee du Centre	North America	-5	1
30	HADT	Hawaii-Aleutian Daylight Time	North America	-9	1
31	HAE	Heure Avancee de l'Est	North America	-4	1
32	HAP	Heure Avancee du Pacifique	North America	-7	1
33	HAR	Heure Avancee des Rocheuses	North America	-6	1
34	HAST	Hawaii-Aleutian Standard Time	North America	-10	1
35	HAT	Heure Avancee de Terre-Neuve	North America	-2.5	1
36	HAY	Heure Avancee du Yukon	North America	-8	1
37	HNA	Heure Normale de l'Atlantique	North America	-4	1
38	HNC	Heure Normale du Centre	North America	-6	1
39	HNE	Heure Normale de l'Est	North America	-5	1
40	HNP	Heure Normale du Pacifique	North America	-8	1
41	HNR	Heure Normale des Rocheuses	North America	-7	1
42	HNT	Heure Normale de Terre-Neuve	North America	-3.5	1
43	HNY	Heure Normale du Yukon	North America	-9	1
44	IST	Irish Summer Time	Europe	1	1
45	MDT	Mountain Daylight Time	North America	-6	1
46	MESZ	Mitteleuropaische Sommerzeit	Europe	2	1
47	MEZ	Mitteleuropaische Zeit	Europe	1	1
48	MST	Mountain Standard Time	North America	-7	1
49	NDT	Newfoundland Daylight Time	North America	-2.5	1
50	NFT	Norfolk (Island) Time	Australia	11.5	1
51	NST	Newfoundland Standard Time	North America	-3.5	1
52	PDT	Pacific Daylight Time	North America	-7	1
53	PST	Pacific Standard Time	North America	-8	1
54	UTC	Coordinated Universal Time	Europe	0	0
55	WEDT	Western European Daylight Time	Europe	1	1
56	WEST	Western European Summer Time	Europe	1	1
57	WET	Western European Time	Europe	0	1
58	WST	Western Summer (Daylight) Time	Australia	9	1
59	WST	Western Standard Time	Australia	8	1
\.


--
-- PostgreSQL database dump complete
--

