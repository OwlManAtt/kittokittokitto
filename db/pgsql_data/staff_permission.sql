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
-- Name: staff_permission_staff_permission_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('staff_permission_staff_permission_id_seq', 13, true);


--
-- Data for Name: staff_permission; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY staff_permission (staff_permission_id, api_name, permission_name) FROM stdin;
1	ignore_board_lock	Post In Locked Board
2	delete_post	Delete Post
3	edit_post	Edit Post
4	manage_thread	Lock/Stick Thread
5	admin_panel	Admin Panel Access
6	moderate	Moderation Dropdown
7	manage_permissions	Edit Permissions
8	manage_pets	Edit Pet Species/Colors
9	manage_users	User Manager
10	manage_boards	Manage Boards
11	manage_shops	Manage Shops
12	manage_items	Manage Items
13	forum_access:staff	Forum: Staff Board
\.


--
-- PostgreSQL database dump complete
--

