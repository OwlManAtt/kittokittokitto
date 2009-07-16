--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: avatar; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE avatar (
    avatar_id integer NOT NULL,
    avatar_name character varying(50) NOT NULL,
    avatar_image character varying(50) NOT NULL,
    active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    CONSTRAINT avatar_active_check CHECK ((active = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.avatar OWNER TO kitto;

--
-- Name: board; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE board (
    board_id integer NOT NULL,
    board_category_id integer NOT NULL,
    board_name character varying(100) NOT NULL,
    board_descr character varying(255) NOT NULL,
    board_locked character(1) DEFAULT 'N'::bpchar NOT NULL,
    news_source character(1) DEFAULT 'N'::bpchar NOT NULL,
    required_permission_id integer NOT NULL,
    order_by integer NOT NULL,
    CONSTRAINT board_board_locked_check CHECK ((board_locked = ANY (ARRAY['Y'::bpchar, 'N'::bpchar]))),
    CONSTRAINT board_news_source_check CHECK ((news_source = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.board OWNER TO kitto;

--
-- Name: board_category; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE board_category (
    board_category_id integer NOT NULL,
    category_name character varying(50) NOT NULL,
    order_by integer DEFAULT 0 NOT NULL,
    required_permission_id integer NOT NULL
);


ALTER TABLE public.board_category OWNER TO kitto;

--
-- Name: board_thread; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE board_thread (
    board_thread_id integer NOT NULL,
    board_id integer NOT NULL,
    thread_name character varying(60) NOT NULL,
    user_id integer NOT NULL,
    thread_created_datetime timestamp without time zone NOT NULL,
    thread_last_posted_datetime timestamp without time zone NOT NULL,
    stickied integer DEFAULT 0 NOT NULL,
    locked character(1) DEFAULT 'N'::bpchar NOT NULL,
    CONSTRAINT board_thread_locked_check CHECK ((locked = ANY (ARRAY['N'::bpchar, 'Y'::bpchar]))),
    CONSTRAINT board_thread_stickied_check CHECK ((stickied = ANY (ARRAY[0, 1])))
);


ALTER TABLE public.board_thread OWNER TO kitto;

--
-- Name: board_thread_post; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE board_thread_post (
    board_thread_post_id integer NOT NULL,
    board_thread_id integer NOT NULL,
    user_id integer NOT NULL,
    posted_datetime timestamp without time zone NOT NULL,
    post_text text NOT NULL
);


ALTER TABLE public.board_thread_post OWNER TO kitto;

--
-- Name: cron_tab; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE cron_tab (
    cron_tab_id integer NOT NULL,
    cron_class character varying(50) NOT NULL,
    cron_frequency_seconds integer NOT NULL,
    unixtime_next_run integer NOT NULL,
    enabled character(1) DEFAULT 'Y'::bpchar NOT NULL,
    CONSTRAINT cron_tab_enabled_check CHECK ((enabled = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.cron_tab OWNER TO kitto;

--
-- Name: datetime_format; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE datetime_format (
    datetime_format_id integer NOT NULL,
    datetime_format_name character varying(30) NOT NULL,
    datetime_format text NOT NULL
);


ALTER TABLE public.datetime_format OWNER TO kitto;

--
-- Name: item_class; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE item_class (
    item_class_id integer NOT NULL,
    php_class character varying(30) NOT NULL,
    class_descr character varying(30) NOT NULL,
    relative_image_dir character varying(50) NOT NULL,
    verb character varying(30) NOT NULL,
    one_per_use character(1) DEFAULT 'N'::bpchar NOT NULL,
    normal_inventory_display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    CONSTRAINT item_class_one_per_use_check CHECK ((one_per_use = ANY (ARRAY['N'::bpchar, 'Y'::bpchar]))),
    CONSTRAINT normal_inventory_display CHECK ((normal_inventory_display = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.item_class OWNER TO kitto;

--
-- Name: item_recipe_material; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE item_recipe_material (
    item_recipe_material_id integer NOT NULL,
    recipe_item_type_id integer NOT NULL,
    material_item_type_id integer NOT NULL,
    material_quantity integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.item_recipe_material OWNER TO kitto;

--
-- Name: item_recipe_type; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE item_recipe_type (
    item_recipe_type_id integer NOT NULL,
    recipe_type_description character varying(20) NOT NULL
);


ALTER TABLE public.item_recipe_type OWNER TO kitto;

--
-- Name: item_type; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE item_type (
    item_type_id integer NOT NULL,
    item_name character varying(50) NOT NULL,
    item_descr text NOT NULL,
    item_class_id integer NOT NULL,
    happiness_bonus integer NOT NULL,
    hunger_bonus integer NOT NULL,
    pet_specie_color_id integer NOT NULL,
    item_image character varying(200) NOT NULL,
    item_recipe_type_id integer DEFAULT 0 NOT NULL,
    recipe_created_item_type_id integer DEFAULT 0 NOT NULL,
    recipe_batch_quantity integer DEFAULT 0 NOT NULL,
    unique_item character(1) DEFAULT 'N'::bpchar NOT NULL,
    transferable_item character(1) DEFAULT 'Y'::bpchar NOT NULL,
    CONSTRAINT transferable_item CHECK ((transferable_item = ANY (ARRAY['Y'::bpchar, 'N'::bpchar]))),
    CONSTRAINT unique_item CHECK ((unique_item = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.item_type OWNER TO kitto;

--
-- Name: jump_page; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE jump_page (
    jump_page_id integer NOT NULL,
    page_title character varying(50) DEFAULT ''::character varying NOT NULL,
    page_html_title character varying(255) DEFAULT ''::character varying NOT NULL,
    layout_type character varying(5) DEFAULT 'deep'::bpchar NOT NULL,
    show_layout character(1) DEFAULT 'Y'::bpchar NOT NULL,
    page_slug character varying(25) DEFAULT ''::character varying NOT NULL,
    access_level character varying(10) DEFAULT 'user'::bpchar NOT NULL,
    restricted_permission_api_name character varying(35) NOT NULL,
    php_script character varying(100) DEFAULT ''::character varying NOT NULL,
    include_tinymce character(1) DEFAULT 'N'::bpchar NOT NULL,
    active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    CONSTRAINT jump_page_access_level_check CHECK (((access_level)::bpchar = ANY (ARRAY['restricted'::bpchar, 'user'::bpchar, 'public'::bpchar]))),
    CONSTRAINT jump_page_active_check CHECK ((active = ANY (ARRAY['Y'::bpchar, 'N'::bpchar]))),
    CONSTRAINT jump_page_include_tinymce_check CHECK ((include_tinymce = ANY (ARRAY['N'::bpchar, 'Y'::bpchar]))),
    CONSTRAINT jump_page_layout_type_check CHECK (((layout_type)::bpchar = ANY (ARRAY['basic'::bpchar, 'deep'::bpchar]))),
    CONSTRAINT show_layout CHECK ((show_layout = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.jump_page OWNER TO kitto;

--
-- Name: pet_specie; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE pet_specie (
    pet_specie_id integer NOT NULL,
    specie_name character varying(50) NOT NULL,
    specie_descr text NOT NULL,
    relative_image_dir character varying(200) NOT NULL,
    max_hunger integer NOT NULL,
    max_happiness integer NOT NULL,
    available character(1) DEFAULT 'Y'::bpchar NOT NULL,
    CONSTRAINT pet_specie_available_check CHECK ((available = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.pet_specie OWNER TO kitto;

--
-- Name: pet_specie_color; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE pet_specie_color (
    pet_specie_color_id integer NOT NULL,
    color_name character varying(30) NOT NULL,
    color_img character varying(200) NOT NULL,
    base_color character(1) DEFAULT 'N'::bpchar NOT NULL,
    CONSTRAINT pet_specie_color_base_color_check CHECK ((base_color = ANY (ARRAY['N'::bpchar, 'Y'::bpchar])))
);


ALTER TABLE public.pet_specie_color OWNER TO kitto;

--
-- Name: pet_specie_pet_specie_color; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE pet_specie_pet_specie_color (
    pet_specie_pet_specie_color_id integer NOT NULL,
    pet_specie_id integer NOT NULL,
    pet_specie_color_id integer NOT NULL
);


ALTER TABLE public.pet_specie_pet_specie_color OWNER TO kitto;

--
-- Name: shop; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE shop (
    shop_id integer NOT NULL,
    shop_name character varying(30) NOT NULL,
    shop_image character varying(200) NOT NULL,
    welcome_text text NOT NULL
);


ALTER TABLE public.shop OWNER TO kitto;

--
-- Name: shop_inventory; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE shop_inventory (
    shop_inventory_id integer NOT NULL,
    item_type_id integer NOT NULL,
    shop_id integer NOT NULL,
    quantity integer NOT NULL,
    price integer NOT NULL
);


ALTER TABLE public.shop_inventory OWNER TO kitto;

--
-- Name: shop_restock; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE shop_restock (
    shop_restock_id integer NOT NULL,
    shop_id integer NOT NULL,
    item_type_id integer NOT NULL,
    restock_frequency_seconds integer NOT NULL,
    unixtime_next_restock integer NOT NULL,
    min_price integer NOT NULL,
    max_price integer NOT NULL,
    min_quantity integer NOT NULL,
    max_quantity integer NOT NULL,
    store_quantity_cap integer NOT NULL
);


ALTER TABLE public.shop_restock OWNER TO kitto;

--
-- Name: staff_group; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE staff_group (
    staff_group_id integer NOT NULL,
    group_name character varying(50) NOT NULL,
    group_descr text NOT NULL,
    show_staff_group character(1) DEFAULT 'Y'::bpchar NOT NULL,
    order_by integer DEFAULT 0 NOT NULL,
    CONSTRAINT staff_group_show_staff_group_check CHECK ((show_staff_group = ANY (ARRAY['Y'::bpchar, 'N'::bpchar])))
);


ALTER TABLE public.staff_group OWNER TO kitto;

--
-- Name: staff_group_staff_permission; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE staff_group_staff_permission (
    staff_group_staff_permission integer NOT NULL,
    staff_group_id integer NOT NULL,
    staff_permission_id integer NOT NULL
);


ALTER TABLE public.staff_group_staff_permission OWNER TO kitto;

--
-- Name: staff_permission; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE staff_permission (
    staff_permission_id integer NOT NULL,
    api_name character varying(50) NOT NULL,
    permission_name character varying(50) NOT NULL
);


ALTER TABLE public.staff_permission OWNER TO kitto;

--
-- Name: timezone; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE timezone (
    timezone_id integer NOT NULL,
    timezone_short_name character varying(4) NOT NULL,
    timezone_long_name character varying(32) NOT NULL,
    timezone_continent character varying(13) NOT NULL,
    timezone_offset real NOT NULL,
    order_by integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.timezone OWNER TO kitto;

--
-- Name: user; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE "user" (
    user_id integer NOT NULL,
    currency integer NOT NULL,
    user_name character varying(25) NOT NULL,
    password_hash character(32) DEFAULT NULL::bpchar,
    password_hash_salt character(32) NOT NULL,
    current_salt character(32) NOT NULL,
    current_salt_expiration timestamp without time zone NOT NULL,
    registered_ip_addr character varying(16) DEFAULT NULL::character varying,
    last_ip_addr character varying(16) NOT NULL,
    last_activity timestamp without time zone NOT NULL,
    access_level character varying(4) DEFAULT 'user'::character varying NOT NULL,
    email text NOT NULL,
    age integer NOT NULL,
    gender character varying(6) NOT NULL,
    profile text NOT NULL,
    signature text NOT NULL,
    avatar_id integer NOT NULL,
    user_title character varying(20) DEFAULT 'User'::character varying NOT NULL,
    datetime_created timestamp without time zone NOT NULL,
    post_count integer NOT NULL,
    textarea_preference character varying(10) DEFAULT 'tinymce'::character varying NOT NULL,
    datetime_last_post timestamp without time zone NOT NULL,
    active_user_pet_id integer NOT NULL,
    timezone_id integer NOT NULL,
    datetime_format_id integer NOT NULL,
    password_reset_requested timestamp without time zone NOT NULL,
    password_reset_confirm character varying(32) NOT NULL,
    show_online_status character(1) DEFAULT 'Y'::bpchar NOT NULL,
    CONSTRAINT user_access_level_check CHECK (((access_level)::text = ANY ((ARRAY['banned'::character varying, 'user'::character varying])::text[]))),
    CONSTRAINT user_gender_check CHECK (((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying])::text[]))),
    CONSTRAINT user_show_online_status_check CHECK ((show_online_status = ANY (ARRAY['Y'::bpchar, 'N'::bpchar]))),
    CONSTRAINT user_textarea_preference_check CHECK (((textarea_preference)::text = ANY ((ARRAY['tinymce'::character varying, 'plain'::character varying])::text[])))
);


ALTER TABLE public."user" OWNER TO kitto;

--
-- Name: user_item; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE user_item (
    user_item_id integer NOT NULL,
    user_id integer NOT NULL,
    item_type_id integer NOT NULL,
    quantity integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.user_item OWNER TO kitto;

--
-- Name: user_message; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE user_message (
    user_message_id integer NOT NULL,
    sender_user_id integer NOT NULL,
    recipient_user_id integer NOT NULL,
    recipient_list text NOT NULL,
    message_title character varying(255) NOT NULL,
    message_body text NOT NULL,
    sent_at timestamp without time zone NOT NULL,
    message_read character(1) DEFAULT 'N'::bpchar NOT NULL,
    CONSTRAINT user_message_message_read_check CHECK ((message_read = ANY (ARRAY['N'::bpchar, 'Y'::bpchar])))
);


ALTER TABLE public.user_message OWNER TO kitto;

--
-- Name: user_notification; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE user_notification (
    user_notification_id integer NOT NULL,
    user_id integer NOT NULL,
    notification_text text NOT NULL,
    notification_url text NOT NULL,
    notification_datetime timestamp without time zone NOT NULL
);


ALTER TABLE public.user_notification OWNER TO kitto;

--
-- Name: user_online; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE user_online (
    user_online_id integer NOT NULL,
    user_type character varying(5) DEFAULT 'guest'::character varying NOT NULL,
    user_id integer NOT NULL,
    client_ip character varying(15) NOT NULL,
    client_user_agent character varying(255) NOT NULL,
    datetime_last_active timestamp without time zone NOT NULL,
    CONSTRAINT user_online_user_type_check CHECK (((user_type)::text = ANY ((ARRAY['user'::character varying, 'guest'::character varying])::text[])))
);


ALTER TABLE public.user_online OWNER TO kitto;

--
-- Name: user_pet; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE user_pet (
    user_pet_id integer NOT NULL,
    user_id integer NOT NULL,
    pet_specie_id integer NOT NULL,
    pet_specie_color_id integer NOT NULL,
    pet_name character varying(25) NOT NULL,
    hunger integer NOT NULL,
    happiness integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    unixtime_next_decrement integer NOT NULL,
    profile text NOT NULL
);


ALTER TABLE public.user_pet OWNER TO kitto;

--
-- Name: user_staff_group; Type: TABLE; Schema: public; Owner: kitto; Tablespace: 
--

CREATE TABLE user_staff_group (
    user_staff_group_id integer NOT NULL,
    user_id integer NOT NULL,
    staff_group_id integer NOT NULL
);


ALTER TABLE public.user_staff_group OWNER TO kitto;

--
-- Name: avatar_avatar_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE avatar_avatar_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.avatar_avatar_id_seq OWNER TO kitto;

--
-- Name: avatar_avatar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE avatar_avatar_id_seq OWNED BY avatar.avatar_id;


--
-- Name: board_board_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE board_board_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.board_board_id_seq OWNER TO kitto;

--
-- Name: board_board_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE board_board_id_seq OWNED BY board.board_id;


--
-- Name: board_category_board_category_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE board_category_board_category_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.board_category_board_category_id_seq OWNER TO kitto;

--
-- Name: board_category_board_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE board_category_board_category_id_seq OWNED BY board_category.board_category_id;


--
-- Name: board_thread_board_thread_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE board_thread_board_thread_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.board_thread_board_thread_id_seq OWNER TO kitto;

--
-- Name: board_thread_board_thread_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE board_thread_board_thread_id_seq OWNED BY board_thread.board_thread_id;


--
-- Name: board_thread_post_board_thread_post_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE board_thread_post_board_thread_post_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.board_thread_post_board_thread_post_id_seq OWNER TO kitto;

--
-- Name: board_thread_post_board_thread_post_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE board_thread_post_board_thread_post_id_seq OWNED BY board_thread_post.board_thread_post_id;


--
-- Name: cron_tab_cron_tab_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE cron_tab_cron_tab_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cron_tab_cron_tab_id_seq OWNER TO kitto;

--
-- Name: cron_tab_cron_tab_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE cron_tab_cron_tab_id_seq OWNED BY cron_tab.cron_tab_id;


--
-- Name: datetime_format_datetime_format_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE datetime_format_datetime_format_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.datetime_format_datetime_format_id_seq OWNER TO kitto;

--
-- Name: datetime_format_datetime_format_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE datetime_format_datetime_format_id_seq OWNED BY datetime_format.datetime_format_id;


--
-- Name: item_class_item_class_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE item_class_item_class_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.item_class_item_class_id_seq OWNER TO kitto;

--
-- Name: item_class_item_class_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE item_class_item_class_id_seq OWNED BY item_class.item_class_id;


--
-- Name: item_recipe_material_item_recipe_material_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE item_recipe_material_item_recipe_material_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.item_recipe_material_item_recipe_material_id_seq OWNER TO kitto;

--
-- Name: item_recipe_material_item_recipe_material_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE item_recipe_material_item_recipe_material_id_seq OWNED BY item_recipe_material.item_recipe_material_id;


--
-- Name: item_recipe_type_item_recipe_type_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE item_recipe_type_item_recipe_type_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.item_recipe_type_item_recipe_type_id_seq OWNER TO kitto;

--
-- Name: item_recipe_type_item_recipe_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE item_recipe_type_item_recipe_type_id_seq OWNED BY item_recipe_type.item_recipe_type_id;


--
-- Name: item_type_item_type_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE item_type_item_type_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.item_type_item_type_id_seq OWNER TO kitto;

--
-- Name: item_type_item_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE item_type_item_type_id_seq OWNED BY item_type.item_type_id;


--
-- Name: jump_page_jump_page_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE jump_page_jump_page_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.jump_page_jump_page_id_seq OWNER TO kitto;

--
-- Name: jump_page_jump_page_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE jump_page_jump_page_id_seq OWNED BY jump_page.jump_page_id;


--
-- Name: pet_specie_color_pet_specie_color_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE pet_specie_color_pet_specie_color_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.pet_specie_color_pet_specie_color_id_seq OWNER TO kitto;

--
-- Name: pet_specie_color_pet_specie_color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE pet_specie_color_pet_specie_color_id_seq OWNED BY pet_specie_color.pet_specie_color_id;


--
-- Name: pet_specie_pet_specie_color_pet_specie_pet_specie_color_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE pet_specie_pet_specie_color_pet_specie_pet_specie_color_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.pet_specie_pet_specie_color_pet_specie_pet_specie_color_id_seq OWNER TO kitto;

--
-- Name: pet_specie_pet_specie_color_pet_specie_pet_specie_color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE pet_specie_pet_specie_color_pet_specie_pet_specie_color_id_seq OWNED BY pet_specie_pet_specie_color.pet_specie_pet_specie_color_id;


--
-- Name: pet_specie_pet_specie_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE pet_specie_pet_specie_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.pet_specie_pet_specie_id_seq OWNER TO kitto;

--
-- Name: pet_specie_pet_specie_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE pet_specie_pet_specie_id_seq OWNED BY pet_specie.pet_specie_id;


--
-- Name: shop_inventory_shop_inventory_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE shop_inventory_shop_inventory_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shop_inventory_shop_inventory_id_seq OWNER TO kitto;

--
-- Name: shop_inventory_shop_inventory_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE shop_inventory_shop_inventory_id_seq OWNED BY shop_inventory.shop_inventory_id;


--
-- Name: shop_restock_shop_restock_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE shop_restock_shop_restock_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shop_restock_shop_restock_id_seq OWNER TO kitto;

--
-- Name: shop_restock_shop_restock_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE shop_restock_shop_restock_id_seq OWNED BY shop_restock.shop_restock_id;


--
-- Name: shop_shop_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE shop_shop_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shop_shop_id_seq OWNER TO kitto;

--
-- Name: shop_shop_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE shop_shop_id_seq OWNED BY shop.shop_id;


--
-- Name: staff_group_staff_group_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE staff_group_staff_group_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.staff_group_staff_group_id_seq OWNER TO kitto;

--
-- Name: staff_group_staff_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE staff_group_staff_group_id_seq OWNED BY staff_group.staff_group_id;


--
-- Name: staff_group_staff_permission_staff_group_staff_permission_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE staff_group_staff_permission_staff_group_staff_permission_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.staff_group_staff_permission_staff_group_staff_permission_seq OWNER TO kitto;

--
-- Name: staff_group_staff_permission_staff_group_staff_permission_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE staff_group_staff_permission_staff_group_staff_permission_seq OWNED BY staff_group_staff_permission.staff_group_staff_permission;


--
-- Name: staff_permission_staff_permission_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE staff_permission_staff_permission_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.staff_permission_staff_permission_id_seq OWNER TO kitto;

--
-- Name: staff_permission_staff_permission_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE staff_permission_staff_permission_id_seq OWNED BY staff_permission.staff_permission_id;


--
-- Name: timezone_timezone_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE timezone_timezone_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.timezone_timezone_id_seq OWNER TO kitto;

--
-- Name: timezone_timezone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE timezone_timezone_id_seq OWNED BY timezone.timezone_id;


--
-- Name: user_item_user_item_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE user_item_user_item_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_item_user_item_id_seq OWNER TO kitto;

--
-- Name: user_item_user_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE user_item_user_item_id_seq OWNED BY user_item.user_item_id;


--
-- Name: user_message_user_message_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE user_message_user_message_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_message_user_message_id_seq OWNER TO kitto;

--
-- Name: user_message_user_message_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE user_message_user_message_id_seq OWNED BY user_message.user_message_id;


--
-- Name: user_notification_user_notification_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE user_notification_user_notification_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_notification_user_notification_id_seq OWNER TO kitto;

--
-- Name: user_notification_user_notification_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE user_notification_user_notification_id_seq OWNED BY user_notification.user_notification_id;


--
-- Name: user_online_user_online_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE user_online_user_online_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_online_user_online_id_seq OWNER TO kitto;

--
-- Name: user_online_user_online_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE user_online_user_online_id_seq OWNED BY user_online.user_online_id;


--
-- Name: user_pet_user_pet_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE user_pet_user_pet_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_pet_user_pet_id_seq OWNER TO kitto;

--
-- Name: user_pet_user_pet_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE user_pet_user_pet_id_seq OWNED BY user_pet.user_pet_id;


--
-- Name: user_staff_group_user_staff_group_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE user_staff_group_user_staff_group_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_staff_group_user_staff_group_id_seq OWNER TO kitto;

--
-- Name: user_staff_group_user_staff_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE user_staff_group_user_staff_group_id_seq OWNED BY user_staff_group.user_staff_group_id;


--
-- Name: user_user_id_seq; Type: SEQUENCE; Schema: public; Owner: kitto
--

CREATE SEQUENCE user_user_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_user_id_seq OWNER TO kitto;

--
-- Name: user_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kitto
--

ALTER SEQUENCE user_user_id_seq OWNED BY "user".user_id;


--
-- Name: avatar_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE avatar ALTER COLUMN avatar_id SET DEFAULT nextval('avatar_avatar_id_seq'::regclass);


--
-- Name: board_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE board ALTER COLUMN board_id SET DEFAULT nextval('board_board_id_seq'::regclass);


--
-- Name: board_category_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE board_category ALTER COLUMN board_category_id SET DEFAULT nextval('board_category_board_category_id_seq'::regclass);


--
-- Name: board_thread_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE board_thread ALTER COLUMN board_thread_id SET DEFAULT nextval('board_thread_board_thread_id_seq'::regclass);


--
-- Name: board_thread_post_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE board_thread_post ALTER COLUMN board_thread_post_id SET DEFAULT nextval('board_thread_post_board_thread_post_id_seq'::regclass);


--
-- Name: cron_tab_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE cron_tab ALTER COLUMN cron_tab_id SET DEFAULT nextval('cron_tab_cron_tab_id_seq'::regclass);


--
-- Name: datetime_format_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE datetime_format ALTER COLUMN datetime_format_id SET DEFAULT nextval('datetime_format_datetime_format_id_seq'::regclass);


--
-- Name: item_class_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE item_class ALTER COLUMN item_class_id SET DEFAULT nextval('item_class_item_class_id_seq'::regclass);


--
-- Name: item_recipe_material_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE item_recipe_material ALTER COLUMN item_recipe_material_id SET DEFAULT nextval('item_recipe_material_item_recipe_material_id_seq'::regclass);


--
-- Name: item_recipe_type_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE item_recipe_type ALTER COLUMN item_recipe_type_id SET DEFAULT nextval('item_recipe_type_item_recipe_type_id_seq'::regclass);


--
-- Name: item_type_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE item_type ALTER COLUMN item_type_id SET DEFAULT nextval('item_type_item_type_id_seq'::regclass);


--
-- Name: jump_page_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE jump_page ALTER COLUMN jump_page_id SET DEFAULT nextval('jump_page_jump_page_id_seq'::regclass);


--
-- Name: pet_specie_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE pet_specie ALTER COLUMN pet_specie_id SET DEFAULT nextval('pet_specie_pet_specie_id_seq'::regclass);


--
-- Name: pet_specie_color_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE pet_specie_color ALTER COLUMN pet_specie_color_id SET DEFAULT nextval('pet_specie_color_pet_specie_color_id_seq'::regclass);


--
-- Name: pet_specie_pet_specie_color_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE pet_specie_pet_specie_color ALTER COLUMN pet_specie_pet_specie_color_id SET DEFAULT nextval('pet_specie_pet_specie_color_pet_specie_pet_specie_color_id_seq'::regclass);


--
-- Name: shop_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE shop ALTER COLUMN shop_id SET DEFAULT nextval('shop_shop_id_seq'::regclass);


--
-- Name: shop_inventory_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE shop_inventory ALTER COLUMN shop_inventory_id SET DEFAULT nextval('shop_inventory_shop_inventory_id_seq'::regclass);


--
-- Name: shop_restock_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE shop_restock ALTER COLUMN shop_restock_id SET DEFAULT nextval('shop_restock_shop_restock_id_seq'::regclass);


--
-- Name: staff_group_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE staff_group ALTER COLUMN staff_group_id SET DEFAULT nextval('staff_group_staff_group_id_seq'::regclass);


--
-- Name: staff_group_staff_permission; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE staff_group_staff_permission ALTER COLUMN staff_group_staff_permission SET DEFAULT nextval('staff_group_staff_permission_staff_group_staff_permission_seq'::regclass);


--
-- Name: staff_permission_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE staff_permission ALTER COLUMN staff_permission_id SET DEFAULT nextval('staff_permission_staff_permission_id_seq'::regclass);


--
-- Name: timezone_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE timezone ALTER COLUMN timezone_id SET DEFAULT nextval('timezone_timezone_id_seq'::regclass);


--
-- Name: user_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE "user" ALTER COLUMN user_id SET DEFAULT nextval('user_user_id_seq'::regclass);


--
-- Name: user_item_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE user_item ALTER COLUMN user_item_id SET DEFAULT nextval('user_item_user_item_id_seq'::regclass);


--
-- Name: user_message_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE user_message ALTER COLUMN user_message_id SET DEFAULT nextval('user_message_user_message_id_seq'::regclass);


--
-- Name: user_notification_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE user_notification ALTER COLUMN user_notification_id SET DEFAULT nextval('user_notification_user_notification_id_seq'::regclass);


--
-- Name: user_online_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE user_online ALTER COLUMN user_online_id SET DEFAULT nextval('user_online_user_online_id_seq'::regclass);


--
-- Name: user_pet_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE user_pet ALTER COLUMN user_pet_id SET DEFAULT nextval('user_pet_user_pet_id_seq'::regclass);


--
-- Name: user_staff_group_id; Type: DEFAULT; Schema: public; Owner: kitto
--

ALTER TABLE user_staff_group ALTER COLUMN user_staff_group_id SET DEFAULT nextval('user_staff_group_user_staff_group_id_seq'::regclass);


--
-- Name: avatar_avatar_image_key; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY avatar
    ADD CONSTRAINT avatar_avatar_image_key UNIQUE (avatar_image);


--
-- Name: avatar_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY avatar
    ADD CONSTRAINT avatar_pkey PRIMARY KEY (avatar_id);


--
-- Name: board_category_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY board_category
    ADD CONSTRAINT board_category_pkey PRIMARY KEY (board_category_id);


--
-- Name: board_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY board
    ADD CONSTRAINT board_pkey PRIMARY KEY (board_id);


--
-- Name: board_thread_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY board_thread
    ADD CONSTRAINT board_thread_pkey PRIMARY KEY (board_thread_id);


--
-- Name: board_thread_post_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY board_thread_post
    ADD CONSTRAINT board_thread_post_pkey PRIMARY KEY (board_thread_post_id);


--
-- Name: cron_tab_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY cron_tab
    ADD CONSTRAINT cron_tab_pkey PRIMARY KEY (cron_tab_id);


--
-- Name: datetime_format_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY datetime_format
    ADD CONSTRAINT datetime_format_pkey PRIMARY KEY (datetime_format_id);


--
-- Name: item_class_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY item_class
    ADD CONSTRAINT item_class_pkey PRIMARY KEY (item_class_id);


--
-- Name: item_recipe_material_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY item_recipe_material
    ADD CONSTRAINT item_recipe_material_pkey PRIMARY KEY (item_recipe_material_id);


--
-- Name: item_recipe_material_unqiue; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY item_recipe_material
    ADD CONSTRAINT item_recipe_material_unqiue UNIQUE (recipe_item_type_id, material_item_type_id);


--
-- Name: item_recipe_type_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY item_recipe_type
    ADD CONSTRAINT item_recipe_type_pkey PRIMARY KEY (item_recipe_type_id);


--
-- Name: item_type_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY item_type
    ADD CONSTRAINT item_type_pkey PRIMARY KEY (item_type_id);


--
-- Name: jump_page_page_slug_key; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY jump_page
    ADD CONSTRAINT jump_page_page_slug_key UNIQUE (page_slug);


--
-- Name: jump_page_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY jump_page
    ADD CONSTRAINT jump_page_pkey PRIMARY KEY (jump_page_id);


--
-- Name: pet_specie_color_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY pet_specie_color
    ADD CONSTRAINT pet_specie_color_pkey PRIMARY KEY (pet_specie_color_id);


--
-- Name: pet_specie_pet_specie_color_pet_specie_id_key; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY pet_specie_pet_specie_color
    ADD CONSTRAINT pet_specie_pet_specie_color_pet_specie_id_key UNIQUE (pet_specie_id, pet_specie_color_id);


--
-- Name: pet_specie_pet_specie_color_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY pet_specie_pet_specie_color
    ADD CONSTRAINT pet_specie_pet_specie_color_pkey PRIMARY KEY (pet_specie_pet_specie_color_id);


--
-- Name: pet_specie_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY pet_specie
    ADD CONSTRAINT pet_specie_pkey PRIMARY KEY (pet_specie_id);


--
-- Name: shop_inventory_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY shop_inventory
    ADD CONSTRAINT shop_inventory_pkey PRIMARY KEY (shop_inventory_id);


--
-- Name: shop_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY shop
    ADD CONSTRAINT shop_pkey PRIMARY KEY (shop_id);


--
-- Name: shop_restock_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY shop_restock
    ADD CONSTRAINT shop_restock_pkey PRIMARY KEY (shop_restock_id);


--
-- Name: staff_group_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY staff_group
    ADD CONSTRAINT staff_group_pkey PRIMARY KEY (staff_group_id);


--
-- Name: staff_group_staff_permission_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY staff_group_staff_permission
    ADD CONSTRAINT staff_group_staff_permission_pkey PRIMARY KEY (staff_group_staff_permission);


--
-- Name: staff_group_staff_permission_staff_group_id_key; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY staff_group_staff_permission
    ADD CONSTRAINT staff_group_staff_permission_staff_group_id_key UNIQUE (staff_group_id, staff_permission_id);


--
-- Name: staff_permission_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY staff_permission
    ADD CONSTRAINT staff_permission_pkey PRIMARY KEY (staff_permission_id);


--
-- Name: timezone_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY timezone
    ADD CONSTRAINT timezone_pkey PRIMARY KEY (timezone_id);


--
-- Name: user_item_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY user_item
    ADD CONSTRAINT user_item_pkey PRIMARY KEY (user_item_id);


--
-- Name: user_message_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY user_message
    ADD CONSTRAINT user_message_pkey PRIMARY KEY (user_message_id);


--
-- Name: user_notification_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY user_notification
    ADD CONSTRAINT user_notification_pkey PRIMARY KEY (user_notification_id);


--
-- Name: user_online_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY user_online
    ADD CONSTRAINT user_online_pkey PRIMARY KEY (user_online_id);


--
-- Name: user_pet_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY user_pet
    ADD CONSTRAINT user_pet_pkey PRIMARY KEY (user_pet_id);


--
-- Name: user_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (user_id);


--
-- Name: user_staff_group_pkey; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY user_staff_group
    ADD CONSTRAINT user_staff_group_pkey PRIMARY KEY (user_staff_group_id);


--
-- Name: user_staff_group_user_id_key; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY user_staff_group
    ADD CONSTRAINT user_staff_group_user_id_key UNIQUE (user_id, staff_group_id);


--
-- Name: user_user_name_key; Type: CONSTRAINT; Schema: public; Owner: kitto; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_user_name_key UNIQUE (user_name);


--
-- Name: board_category_id_index; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX board_category_id_index ON board USING btree (board_category_id);


--
-- Name: board_thread__board_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX board_thread__board_id ON board_thread USING btree (board_id);


--
-- Name: board_thread__user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX board_thread__user_id ON board_thread USING btree (user_id);


--
-- Name: board_thread_post__board_thread_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX board_thread_post__board_thread_id ON board_thread_post USING btree (board_thread_id);


--
-- Name: board_thread_post__user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX board_thread_post__user_id ON board_thread_post USING btree (user_id);


--
-- Name: item_type__item_class_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX item_type__item_class_id ON item_type USING btree (item_class_id);


--
-- Name: item_type__item_name; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX item_type__item_name ON item_type USING btree (item_name);


--
-- Name: item_type__item_recipe_type_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX item_type__item_recipe_type_id ON item_type USING btree (item_recipe_type_id);


--
-- Name: item_type__pet_specie_color_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX item_type__pet_specie_color_id ON item_type USING btree (pet_specie_color_id);


--
-- Name: item_type__recipe_created_item_type_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX item_type__recipe_created_item_type_id ON item_type USING btree (recipe_created_item_type_id);


--
-- Name: required_permission_id_category_index; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX required_permission_id_category_index ON board_category USING btree (required_permission_id);


--
-- Name: required_permission_id_index; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX required_permission_id_index ON board USING btree (required_permission_id);


--
-- Name: shop_inventory__item_type_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX shop_inventory__item_type_id ON shop_inventory USING btree (item_type_id);


--
-- Name: shop_inventory__shop_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX shop_inventory__shop_id ON shop_inventory USING btree (shop_id);


--
-- Name: shop_restock__item_type_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX shop_restock__item_type_id ON shop_restock USING btree (item_type_id);


--
-- Name: shop_restock__shop_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX shop_restock__shop_id ON shop_restock USING btree (shop_id);


--
-- Name: user__active_user_pet_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user__active_user_pet_id ON "user" USING btree (active_user_pet_id);


--
-- Name: user__avatar_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user__avatar_id ON "user" USING btree (avatar_id);


--
-- Name: user__datetime_format_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user__datetime_format_id ON "user" USING btree (datetime_format_id);


--
-- Name: user__timezone_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user__timezone_id ON "user" USING btree (timezone_id);


--
-- Name: user_item__item_type_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_item__item_type_id ON user_item USING btree (item_type_id);


--
-- Name: user_item__user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_item__user_id ON user_item USING btree (user_id);


--
-- Name: user_message__recipient_user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_message__recipient_user_id ON user_message USING btree (recipient_user_id);


--
-- Name: user_message__sender_user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_message__sender_user_id ON user_message USING btree (sender_user_id);


--
-- Name: user_notification__user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_notification__user_id ON user_notification USING btree (user_id);


--
-- Name: user_online__client_ip; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_online__client_ip ON user_online USING btree (client_ip);


--
-- Name: user_online__user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_online__user_id ON user_online USING btree (user_id);


--
-- Name: user_pet__pet_specie_color_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_pet__pet_specie_color_id ON user_pet USING btree (pet_specie_color_id);


--
-- Name: user_pet__pet_specie_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_pet__pet_specie_id ON user_pet USING btree (pet_specie_id);


--
-- Name: user_pet__user_id; Type: INDEX; Schema: public; Owner: kitto; Tablespace: 
--

CREATE INDEX user_pet__user_id ON user_pet USING btree (user_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

