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
-- Name: jump_page_jump_page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kitto
--

SELECT pg_catalog.setval('jump_page_jump_page_id_seq', 76, true);


--
-- Data for Name: jump_page; Type: TABLE DATA; Schema: public; Owner: kitto
--

COPY jump_page (jump_page_id, page_title, page_html_title, layout_type, show_layout, page_slug, access_level, restricted_permission_api_name, php_script, include_tinymce, active) FROM stdin;
1	Home	Home	deep	Y	home	public		meta/home.php	N	Y
2	Register	Register	deep	Y	register	public		user/register.php	N	Y
3	Logoff	Logoff	deep	Y	logoff	user		user/logout.php	N	Y
4	Login	Login	deep	Y	login	public		user/login.php	N	Y
14	Profile	Profile	deep	Y	profile	user		user/profile.php	N	Y
22	Pets	Pets	deep	Y	pets	user		pets/manage.php	N	Y
23	Pets - Create	Pets - Create	deep	Y	create-pet	user		pets/create.php	N	Y
24	Pets - Abandon	Pets - Abandon	deep	Y	abandon-pet	user		pets/abandon.php	N	Y
25	Items	Items	deep	Y	items	user		items/list.php	N	Y
26	Item - Details	Item - Details	deep	Y	item	user		items/detail.php	N	Y
27	Shops	Shops	deep	Y	shops	user		shops/list.php	N	Y
28	Shops - View Stock	Shops - View Stock	deep	Y	shop	user		shops/shop.php	N	Y
29	Notices	Notices	deep	Y	notice	user		user/notices.php	N	Y
30	Game Corner	Game Corner	deep	Y	games	user		games/list.php	N	Y
31	Magic Trick	Magic Trick	deep	Y	magic-game	user		games/magic_game.php	N	Y
32	Boards	Boards	deep	Y	boards	user		boards/board_list.php	N	Y
33	Boards	Boards	deep	Y	threads	user		boards/thread_list.php	N	Y
34	Boards	Boards	deep	Y	thread	user		boards/post_list.php	Y	Y
35	Boards - Reply	Boards - Reply	deep	Y	thread-reply	user		boards/reply.php	N	Y
36	Create Thread	Create Thread	deep	Y	new-thread	user		boards/create_thread.php	Y	Y
37	Forum Moderation	Forum Moderation	deep	Y	forum-admin	restricted	moderate	boards/moderation.php	N	Y
38	Edit Post	Edit Post	deep	Y	edit-post	user		boards/edit_post.php	Y	Y
39	Edit Thread	Edit Thread	deep	Y	edit-thread	user		boards/edit_thread.php	N	Y
40	Preferences	Preferences	deep	Y	preferences	user		user/preferences.php	Y	Y
41	Pet Profile	Pet Profile	deep	Y	pet	user		pets/profile.php	N	Y
42	Edit Pet Profile	Edit Pet Profile	deep	Y	edit-pet	user		pets/edit.php	Y	Y
43	News	News	deep	Y	news	public		news/list.php	N	Y
44	Messages	Messages	deep	Y	messages	user		messages/list.php	N	Y
45	Compose Message	Compose Message	deep	Y	write-message	user		messages/write.php	Y	Y
46	Send Message	Send Message	deep	Y	send-message	user		messages/send.php	N	Y
47	Read Message	Read Message	deep	Y	message	user		messages/view.php	N	Y
48	Admin Overview	Admin Overview	deep	Y	admin	restricted	admin_panel	admin/links.php	N	Y
49	Permission Editor	Permission Editor	deep	Y	admin-permissions	restricted	manage_permissions	admin/permissions/home.php	N	Y
50	Edit Pet Colors	Edit Pet Colors	deep	Y	admin-pet-colors	restricted	manage_pets	admin/pets/colors/home.php	N	Y
51	Edit Pet Species	Edit Pet Species	deep	Y	admin-pet-species	restricted	manage_pets	admin/pets/species/home.php	N	Y
52	User Admin	User Admin	deep	Y	admin-users	restricted	manage_users	admin/user/home.php	Y	Y
53	Board Admin	Board Admin	deep	Y	admin-boards	restricted	manage_boards	admin/boards/home.php	N	Y
54	Shop Admin	Shop Admin	deep	Y	admin-shops	restricted	manage_shops	admin/shops/home.php	N	Y
55	Item Admin	Item Admin	deep	Y	admin-items	restricted	manage_items	admin/items/home.php	N	Y
56	Permission Editor	Permission Editor	deep	Y	admin-permissions-edit	restricted	manage_permissions	admin/permissions/edit.php	Y	Y
57	Board Creator	Board Creator	deep	Y	admin-boards-create	restricted	manage_boards	admin/boards/create.php	N	N
58	Edit Color	Edit Color	deep	Y	admin-pet-colors-edit	restricted	manage_pets	admin/pets/colors/edit.php	N	Y
59	Edit Specie	Edit Specie	deep	Y	admin-pet-species-edit	restricted	manage_pets	admin/pets/species/edit.php	Y	Y
60	Pet to Color Mapping	Pet to Color Mapping	deep	Y	admin-pet-specie-colors	restricted	manage_pets	admin/pets/species/color_mapping.php	N	Y
61	Board Editor	Board Editor	deep	Y	admin-boards-edit	restricted	manage_boards	admin/boards/edit.php	Y	Y
62	Edit Shop	Edit Shop	deep	Y	admin-shops-edit	restricted	manage_shops	admin/shops/edit.php	Y	Y
63	Edit Item	Edit Item	deep	Y	admin-items-edit	restricted	manage_items	admin/items/edit.php	Y	Y
64	New Item	New Item	deep	Y	admin-items-add	restricted	manage_items	admin/items/new.php	N	Y
65	Edit Restocks	Edit Restocks	deep	Y	admin-restock	restricted	manage_items	admin/items/restock/home.php	N	Y
66	Edit Restocks	Edit Restocks	deep	Y	admin-restock-edit	restricted	manage_items	admin/items/restock/edit.php	N	Y
67	Staff	Staff	deep	Y	staff	public		meta/staff.php	N	Y
68	Reset Password	Reset Password	deep	Y	reset-password	public		user/forgot_password.php	N	Y
69	Terms and Conditions	Terms and Conditions	deep	Y	terms-and-conditions	public		meta/terms.php	N	Y
70	Online Users	Online Users	deep	Y	online	public		meta/online.php	N	Y
71	Search	Search	deep	Y	search	user		meta/search.php	N	Y
72	Manage Recipe Materials	Manage Recipe Materials	deep	Y	admin-recipe	restricted	manage_items	admin/items/recipe_materials.php	N	Y
73	Add Recipe Materials	Add Recipe Materials	deep	Y	admin-recipe-add	restricted	manage_items	admin/items/recipe_add_material.php	N	Y
74	Crafting	Crafting	deep	Y	crafting	user		crafting/list.php	N	Y
75	Crafting	Crafting	deep	Y	craft	user		crafting/recipe.php	N	Y
76	AJAX Item Search Callback		deep	N	item-search-ajax	user		items/ajax_widget_search.php	N	Y
\.


--
-- PostgreSQL database dump complete
--

