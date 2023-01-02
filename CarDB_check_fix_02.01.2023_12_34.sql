PGDMP         "                 {            CarDB    12.12    12.12 �    S           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            T           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            U           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            V           1262    41145    CarDB    DATABASE     �   CREATE DATABASE "CarDB" WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'Polish_Poland.1250' LC_CTYPE = 'Polish_Poland.1250';
    DROP DATABASE "CarDB";
                postgres    false            
            2615    73769    pgagent    SCHEMA        CREATE SCHEMA pgagent;
    DROP SCHEMA pgagent;
                postgres    false            W           0    0    SCHEMA pgagent    COMMENT     6   COMMENT ON SCHEMA pgagent IS 'pgAgent system tables';
                   postgres    false    10                        3079    16384 	   adminpack 	   EXTENSION     A   CREATE EXTENSION IF NOT EXISTS adminpack WITH SCHEMA pg_catalog;
    DROP EXTENSION adminpack;
                   false            X           0    0    EXTENSION adminpack    COMMENT     M   COMMENT ON EXTENSION adminpack IS 'administrative functions for PostgreSQL';
                        false    1                        3079    73770    pgagent 	   EXTENSION     <   CREATE EXTENSION IF NOT EXISTS pgagent WITH SCHEMA pgagent;
    DROP EXTENSION pgagent;
                   false    10            Y           0    0    EXTENSION pgagent    COMMENT     >   COMMENT ON EXTENSION pgagent IS 'A PostgreSQL job scheduler';
                        false    3            O           1247    41147    dokument    TYPE     n   CREATE TYPE public.dokument AS ENUM (
    'dowód osobisty',
    'paszport',
    'prawo jazdy',
    'inny'
);
    DROP TYPE public.dokument;
       public          postgres    false            R           1247    41156    skrzynia    TYPE     e   CREATE TYPE public.skrzynia AS ENUM (
    'manualna',
    'półautomatyczna',
    'automatyczna'
);
    DROP TYPE public.skrzynia;
       public          postgres    false            �           1247    49155    uprawnienia    TYPE     Z   CREATE TYPE public.uprawnienia AS ENUM (
    'admin',
    'kierownik',
    'pracownik'
);
    DROP TYPE public.uprawnienia;
       public          postgres    false            �            1255    73733    check_admin_count()    FUNCTION     �  CREATE FUNCTION public.check_admin_count() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Sprawdź, czy w tabeli pracownicy jest tylko jeden użytkownik o uprawnieniach 'admin'
    IF (SELECT COUNT(*) FROM pracownicy WHERE uprawnienia = 'admin') = 0 THEN
        -- Jeśli tak, zgłoś błąd i anuluj operację
        RAISE EXCEPTION 'Nie można usunąć jedynego użytkownika o uprawnieniach "admin".';
        ROLLBACK;
    END IF;
END;
$$;
 *   DROP FUNCTION public.check_admin_count();
       public          postgres    false                       1255    73763    check_overlap_dates_przeglad()    FUNCTION     �  CREATE FUNCTION public.check_overlap_dates_przeglad() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Sprawdź, czy w tabeli wypozyczenia istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM wypozyczenia WHERE idauta = NEW.idauta AND
        (NEW.dataprzegladu > datapoczatek AND NEW.dataprzegladu < datakoniec)) > 0 THEN
        -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
        RAISE EXCEPTION 'Podane daty nakładają się z istniejącymi rekordami w tabeli "wypozyczenia".';
        ROLLBACK;
    END IF;	

    -- Sprawdź, czy w tabeli przeglad istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM przeglad WHERE idauta = NEW.idauta AND idprzegladu <> NEW.idprzegladu AND
        (NEW.dataprzegladu BETWEEN dataprzegladu AND dataprzegladu)) > 0 THEN
		 -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
		RAISE EXCEPTION 'Podane daty nakładają się z datą przeglądu w tabeli "przeglad".';
        ROLLBACK;
    END IF;

    -- Sprawdź, czy w tabeli serwis istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM serwis WHERE idauta = NEW.idauta AND
        (NEW.dataprzegladu > datapoczatek AND NEW.dataprzegladu < datakoniec)) > 0 THEN
        -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
        RAISE EXCEPTION 'Podane daty nakładają się z istniejącymi rekordami w tabeli "serwis".';
        ROLLBACK;
    END IF;
	
	-- Jeśli nie znaleziono żadnych rekordów z nakładającymi się datami, zwróć nowy rekord
	RETURN NEW;
	
END;
$$;
 5   DROP FUNCTION public.check_overlap_dates_przeglad();
       public          postgres    false                       1255    73764    check_overlap_dates_serwis()    FUNCTION     =  CREATE FUNCTION public.check_overlap_dates_serwis() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Sprawdź, czy w tabeli wypozyczenia istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM wypozyczenia WHERE idauta = NEW.idauta AND
        ((NEW.datapoczatek > datapoczatek AND NEW.datapoczatek < datakoniec) OR (NEW.datakoniec > datapoczatek AND NEW.datakoniec < datakoniec) OR
         (datapoczatek > NEW.datapoczatek AND datapoczatek < NEW.datakoniec) OR (datakoniec > NEW.datapoczatek AND datakoniec < NEW.datakoniec))) > 0 THEN
        -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
        RAISE EXCEPTION 'Podane daty nakładają się z istniejącymi rekordami w tabeli "wypozyczenia".';
        ROLLBACK;
    END IF;	

    -- Sprawdź, czy w tabeli przeglad istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM przeglad WHERE idauta = NEW.idauta AND
		(dataprzegladu > NEW.datapoczatek AND dataprzegladu < NEW.datakoniec)) > 0 THEN
		 -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
		RAISE EXCEPTION 'Podane daty nakładają się z datą przeglądu w tabeli "przeglad".';
        ROLLBACK;
    END IF;

    -- Sprawdź, czy w tabeli serwis istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM serwis WHERE idauta = NEW.idauta AND idserwis <> NEW.idserwis AND
        ((NEW.datapoczatek > datapoczatek AND NEW.datapoczatek < datakoniec) OR (NEW.datakoniec > datapoczatek AND NEW.datakoniec < datakoniec) OR
         (datapoczatek > NEW.datapoczatek AND datapoczatek < NEW.datakoniec) OR (datakoniec > NEW.datapoczatek AND datakoniec < NEW.datakoniec))) > 0 THEN
        -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
        RAISE EXCEPTION 'Podane daty nakładają się z istniejącymi rekordami w tabeli "serwis".';
        ROLLBACK;
    END IF;
	
	-- Jeśli nie znaleziono żadnych rekordów z nakładającymi się datami, zwróć nowy rekord
	RETURN NEW;
	
END;
$$;
 3   DROP FUNCTION public.check_overlap_dates_serwis();
       public          postgres    false                       1255    73762 "   check_overlap_dates_wypozyczenia()    FUNCTION     '  CREATE FUNCTION public.check_overlap_dates_wypozyczenia() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	-- Sprawdź, czy w tabeli wypozyczenia istnieje rekord o podanym idauta i datach nakładających się na podane daty
	IF (SELECT COUNT(*) FROM wypozyczenia WHERE idauta = NEW.idauta AND idwypozyczenia <> NEW.idwypozyczenia AND
		((NEW.datapoczatek > datapoczatek AND NEW.datapoczatek < datakoniec) OR (NEW.datakoniec > datapoczatek AND NEW.datakoniec < datakoniec) OR
		 (datapoczatek > NEW.datapoczatek AND datapoczatek < NEW.datakoniec) OR (datakoniec > NEW.datapoczatek AND datakoniec < NEW.datakoniec))) > 0 THEN
		-- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
		RAISE EXCEPTION 'Podane daty nakładają się z istniejącymi rekordami w tabeli "wypozyczenia".';
		ROLLBACK;
	END IF;

    -- Sprawdź, czy w tabeli przeglad istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM przeglad WHERE idauta = NEW.idauta AND
		(dataprzegladu > NEW.datapoczatek AND dataprzegladu < NEW.datakoniec)) > 0 THEN
		 -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
		RAISE EXCEPTION 'Podane daty nakładają się z datą przeglądu w tabeli "przeglad".';
        ROLLBACK;
    END IF;

    -- Sprawdź, czy w tabeli serwis istnieje rekord o podanym idauta i datach nakładających się na podane daty
    IF (SELECT COUNT(*) FROM serwis WHERE idauta = NEW.idauta AND
        ((NEW.datapoczatek > datapoczatek AND NEW.datapoczatek < datakoniec) OR (NEW.datakoniec > datapoczatek AND NEW.datakoniec < datakoniec) OR
         (datapoczatek > NEW.datapoczatek AND datapoczatek < NEW.datakoniec) OR (datakoniec > NEW.datapoczatek AND datakoniec < NEW.datakoniec))) > 0 THEN
        -- Jeśli taki rekord istnieje, zgłoś błąd i anuluj zapytanie
        RAISE EXCEPTION 'Podane daty nakładają się z istniejącymi rekordami w tabeli "serwis".';
        ROLLBACK;
    END IF;
	
	-- Jeśli nie znaleziono żadnych rekordów z nakładającymi się datami, zwróć nowy rekord
	RETURN NEW;
	
END;
$$;
 9   DROP FUNCTION public.check_overlap_dates_wypozyczenia();
       public          postgres    false            �            1255    73768 #   update_availability_car_startdate()    FUNCTION     �  CREATE FUNCTION public.update_availability_car_startdate() RETURNS void
    LANGUAGE plpgsql
    AS $$
	BEGIN
	  -- aktualizuj pole "dostępny" dla rekordów w tabeli "auta", jeśli odpowiadające im rekordy w tabeli "wypożyczenia" mają już datę końcową w przeszłości
	  UPDATE auta SET dostepny = 'false'
	WHERE idauto IN (SELECT wypozyczenia.idauta FROM wypozyczenia WHERE datapoczatek <= CURRENT_TIMESTAMP);
	END;
$$;
 :   DROP FUNCTION public.update_availability_car_startdate();
       public          postgres    false            �            1259    41163    auta    TABLE     �  CREATE TABLE public.auta (
    idauto integer NOT NULL,
    vin character varying(20) NOT NULL,
    rejestracja character varying(20) NOT NULL,
    idsegment integer NOT NULL,
    idmodel integer NOT NULL,
    idpaliwo integer NOT NULL,
    mockw integer NOT NULL,
    skrzynia public.skrzynia NOT NULL,
    liczbamiejsc integer NOT NULL,
    rok integer NOT NULL,
    sprawny boolean NOT NULL,
    dostepny boolean NOT NULL,
    przebieg integer NOT NULL,
    cenadoba numeric(12,2) NOT NULL,
    cenakm numeric(12,2) NOT NULL,
    uwagi text,
    idzdjecie bigint,
    aktywny boolean DEFAULT true NOT NULL,
    CONSTRAINT ceny_check CHECK (((cenadoba >= (0)::numeric) AND (cenakm >= (0)::numeric))),
    CONSTRAINT miejsca_check CHECK ((liczbamiejsc >= 0)),
    CONSTRAINT moc_check CHECK ((mockw >= 0)),
    CONSTRAINT przebieg_check CHECK ((przebieg >= 0)),
    CONSTRAINT rok_check CHECK (((rok > 1900) AND (rok < 10000)))
);
    DROP TABLE public.auta;
       public         heap    postgres    false    594            �            1259    41174    auta_idauto_seq    SEQUENCE     �   CREATE SEQUENCE public.auta_idauto_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.auta_idauto_seq;
       public          postgres    false    205            Z           0    0    auta_idauto_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.auta_idauto_seq OWNED BY public.auta.idauto;
          public          postgres    false    206            �            1259    41176    idpracownika_seq    SEQUENCE     y   CREATE SEQUENCE public.idpracownika_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.idpracownika_seq;
       public          postgres    false            �            1259    41178    klienci    TABLE     >  CREATE TABLE public.klienci (
    idklienta bigint NOT NULL,
    rodzajdokumentu public.dokument NOT NULL,
    nrdokumentu character varying(30) NOT NULL,
    pesel character varying(12),
    imie character varying(50) NOT NULL,
    nazwisko character varying(50) NOT NULL,
    telefon character varying(30) NOT NULL,
    email character varying(50),
    idmiasto integer NOT NULL,
    ulica character varying(100) NOT NULL,
    nrdomu character varying(10) NOT NULL,
    nrmieszkania character varying(10),
    kodpocztowy character varying(10) NOT NULL,
    uwagi text
);
    DROP TABLE public.klienci;
       public         heap    postgres    false    591            �            1259    41184    klienci_idklienta_seq    SEQUENCE     ~   CREATE SEQUENCE public.klienci_idklienta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.klienci_idklienta_seq;
       public          postgres    false    208            [           0    0    klienci_idklienta_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE public.klienci_idklienta_seq OWNED BY public.klienci.idklienta;
          public          postgres    false    209            �            1259    41334    kraj    TABLE     i   CREATE TABLE public.kraj (
    idkraj integer NOT NULL,
    nazwakraj character varying(100) NOT NULL
);
    DROP TABLE public.kraj;
       public         heap    postgres    false            �            1259    41332    kraj_idkraj_seq    SEQUENCE     �   CREATE SEQUENCE public.kraj_idkraj_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.kraj_idkraj_seq;
       public          postgres    false    228            \           0    0    kraj_idkraj_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.kraj_idkraj_seq OWNED BY public.kraj.idkraj;
          public          postgres    false    227            �            1259    41186    marka    TABLE     l   CREATE TABLE public.marka (
    idmarka integer NOT NULL,
    nazwamarki character varying(100) NOT NULL
);
    DROP TABLE public.marka;
       public         heap    postgres    false            �            1259    41189    marka_idmarka_seq    SEQUENCE     �   CREATE SEQUENCE public.marka_idmarka_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.marka_idmarka_seq;
       public          postgres    false    210            ]           0    0    marka_idmarka_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.marka_idmarka_seq OWNED BY public.marka.idmarka;
          public          postgres    false    211            �            1259    41343    miasto    TABLE     �   CREATE TABLE public.miasto (
    idmiasto integer NOT NULL,
    idkraj integer NOT NULL,
    nazwamiasto character varying(100) NOT NULL
);
    DROP TABLE public.miasto;
       public         heap    postgres    false            �            1259    41341    miasto_idmiasto_seq    SEQUENCE     �   CREATE SEQUENCE public.miasto_idmiasto_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.miasto_idmiasto_seq;
       public          postgres    false    230            ^           0    0    miasto_idmiasto_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.miasto_idmiasto_seq OWNED BY public.miasto.idmiasto;
          public          postgres    false    229            �            1259    41326    model    TABLE     �   CREATE TABLE public.model (
    idmodel integer NOT NULL,
    idmarka integer NOT NULL,
    nazwamodel character varying(100) NOT NULL
);
    DROP TABLE public.model;
       public         heap    postgres    false            �            1259    41324    model_idmodel_seq    SEQUENCE     �   CREATE SEQUENCE public.model_idmodel_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.model_idmodel_seq;
       public          postgres    false    226            _           0    0    model_idmodel_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.model_idmodel_seq OWNED BY public.model.idmodel;
          public          postgres    false    225            �            1259    41191    paliwo    TABLE     o   CREATE TABLE public.paliwo (
    idpaliwo integer NOT NULL,
    nazwapaliwo character varying(100) NOT NULL
);
    DROP TABLE public.paliwo;
       public         heap    postgres    false            �            1259    41194    paliwo_idpaliwo_seq    SEQUENCE     �   CREATE SEQUENCE public.paliwo_idpaliwo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.paliwo_idpaliwo_seq;
       public          postgres    false    212            `           0    0    paliwo_idpaliwo_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.paliwo_idpaliwo_seq OWNED BY public.paliwo.idpaliwo;
          public          postgres    false    213            �            1259    41196 
   pracownicy    TABLE     �  CREATE TABLE public.pracownicy (
    idpracownika integer DEFAULT nextval('public.idpracownika_seq'::regclass) NOT NULL,
    login character varying(30) NOT NULL,
    haslo character varying(255) NOT NULL,
    imie character varying(50) NOT NULL,
    nazwisko character varying(50) NOT NULL,
    telefon character varying(30),
    email character varying(50) NOT NULL,
    zatrudniony boolean DEFAULT true NOT NULL,
    uprawnienia public.uprawnienia NOT NULL
);
    DROP TABLE public.pracownicy;
       public         heap    postgres    false    207    740            �            1259    41208    przeglad_idprzegladu_seq    SEQUENCE     �   CREATE SEQUENCE public.przeglad_idprzegladu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.przeglad_idprzegladu_seq;
       public          postgres    false            �            1259    41201    przeglad    TABLE     3  CREATE TABLE public.przeglad (
    idprzegladu bigint DEFAULT nextval('public.przeglad_idprzegladu_seq'::regclass) NOT NULL,
    idauta integer NOT NULL,
    dataprzegladu date NOT NULL,
    datawaznosci date NOT NULL,
    uwagi text,
    CONSTRAINT waznosc_check CHECK ((datawaznosci >= dataprzegladu))
);
    DROP TABLE public.przeglad;
       public         heap    postgres    false    216            �            1259    41213    segment_idsegment_seq    SEQUENCE     �   CREATE SEQUENCE public.segment_idsegment_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.segment_idsegment_seq;
       public          postgres    false            �            1259    41210    segment    TABLE     �   CREATE TABLE public.segment (
    idsegment integer DEFAULT nextval('public.segment_idsegment_seq'::regclass) NOT NULL,
    nazwasegment character varying(100) NOT NULL
);
    DROP TABLE public.segment;
       public         heap    postgres    false    218            �            1259    41224    serwis_idserwis_seq    SEQUENCE     |   CREATE SEQUENCE public.serwis_idserwis_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.serwis_idserwis_seq;
       public          postgres    false            �            1259    41215    serwis    TABLE     3  CREATE TABLE public.serwis (
    idserwis bigint DEFAULT nextval('public.serwis_idserwis_seq'::regclass) NOT NULL,
    idauta integer NOT NULL,
    idpracownika integer NOT NULL,
    datapoczatek date NOT NULL,
    datakoniec date NOT NULL,
    nazwaserwisu character varying(100) NOT NULL,
    opis text NOT NULL,
    uwagi text,
    koszt numeric(12,2) NOT NULL,
    CONSTRAINT dataserwis_check CHECK ((datakoniec >= datapoczatek)),
    CONSTRAINT koszt_check CHECK ((koszt >= (0)::numeric)),
    CONSTRAINT serwis_check CHECK ((datakoniec >= datapoczatek))
);
    DROP TABLE public.serwis;
       public         heap    postgres    false    220            �            1259    41235    wypozyczenia_idwypozyczenia_seq    SEQUENCE     �   CREATE SEQUENCE public.wypozyczenia_idwypozyczenia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE public.wypozyczenia_idwypozyczenia_seq;
       public          postgres    false            �            1259    41226    wypozyczenia    TABLE     }  CREATE TABLE public.wypozyczenia (
    idwypozyczenia bigint DEFAULT nextval('public.wypozyczenia_idwypozyczenia_seq'::regclass) NOT NULL,
    idauta integer NOT NULL,
    idklienta bigint NOT NULL,
    idpracownika integer NOT NULL,
    datapoczatek date NOT NULL,
    datakoniec date,
    przebiegstart integer NOT NULL,
    przebiegkoniec integer,
    suma numeric(12,2),
    zaplacono boolean,
    uwagi text,
    CONSTRAINT daty_check CHECK (((datakoniec IS NULL) OR (datakoniec >= datapoczatek))),
    CONSTRAINT przebieg_check CHECK ((przebiegkoniec >= przebiegstart)),
    CONSTRAINT suma_check CHECK ((suma >= (0)::numeric))
);
     DROP TABLE public.wypozyczenia;
       public         heap    postgres    false    222            �            1259    41243    zdjecia_idzdjecie_seq    SEQUENCE     ~   CREATE SEQUENCE public.zdjecia_idzdjecie_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.zdjecia_idzdjecie_seq;
       public          postgres    false            �            1259    41237    zdjecia    TABLE     �   CREATE TABLE public.zdjecia (
    idzdjecie bigint DEFAULT nextval('public.zdjecia_idzdjecie_seq'::regclass) NOT NULL,
    tytul character varying(100) NOT NULL,
    sciezka character varying(100) NOT NULL
);
    DROP TABLE public.zdjecia;
       public         heap    postgres    false    224            �            1259    57344    zdjecie-auto_idautozdj_seq    SEQUENCE     �   CREATE SEQUENCE public."zdjecie-auto_idautozdj_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE public."zdjecie-auto_idautozdj_seq";
       public          postgres    false            "           2604    41245    auta idauto    DEFAULT     j   ALTER TABLE ONLY public.auta ALTER COLUMN idauto SET DEFAULT nextval('public.auta_idauto_seq'::regclass);
 :   ALTER TABLE public.auta ALTER COLUMN idauto DROP DEFAULT;
       public          postgres    false    206    205            )           2604    41246    klienci idklienta    DEFAULT     v   ALTER TABLE ONLY public.klienci ALTER COLUMN idklienta SET DEFAULT nextval('public.klienci_idklienta_seq'::regclass);
 @   ALTER TABLE public.klienci ALTER COLUMN idklienta DROP DEFAULT;
       public          postgres    false    209    208            ;           2604    41337    kraj idkraj    DEFAULT     j   ALTER TABLE ONLY public.kraj ALTER COLUMN idkraj SET DEFAULT nextval('public.kraj_idkraj_seq'::regclass);
 :   ALTER TABLE public.kraj ALTER COLUMN idkraj DROP DEFAULT;
       public          postgres    false    227    228    228            *           2604    41247    marka idmarka    DEFAULT     n   ALTER TABLE ONLY public.marka ALTER COLUMN idmarka SET DEFAULT nextval('public.marka_idmarka_seq'::regclass);
 <   ALTER TABLE public.marka ALTER COLUMN idmarka DROP DEFAULT;
       public          postgres    false    211    210            <           2604    41346    miasto idmiasto    DEFAULT     r   ALTER TABLE ONLY public.miasto ALTER COLUMN idmiasto SET DEFAULT nextval('public.miasto_idmiasto_seq'::regclass);
 >   ALTER TABLE public.miasto ALTER COLUMN idmiasto DROP DEFAULT;
       public          postgres    false    230    229    230            :           2604    41329    model idmodel    DEFAULT     n   ALTER TABLE ONLY public.model ALTER COLUMN idmodel SET DEFAULT nextval('public.model_idmodel_seq'::regclass);
 <   ALTER TABLE public.model ALTER COLUMN idmodel DROP DEFAULT;
       public          postgres    false    226    225    226            +           2604    41248    paliwo idpaliwo    DEFAULT     r   ALTER TABLE ONLY public.paliwo ALTER COLUMN idpaliwo SET DEFAULT nextval('public.paliwo_idpaliwo_seq'::regclass);
 >   ALTER TABLE public.paliwo ALTER COLUMN idpaliwo DROP DEFAULT;
       public          postgres    false    213    212                      0    73771    pga_jobagent 
   TABLE DATA           I   COPY pgagent.pga_jobagent (jagpid, jaglogintime, jagstation) FROM stdin;
    pgagent          postgres    false    232   ��                 0    73782    pga_jobclass 
   TABLE DATA           7   COPY pgagent.pga_jobclass (jclid, jclname) FROM stdin;
    pgagent          postgres    false    234   ��                 0    73794    pga_job 
   TABLE DATA           �   COPY pgagent.pga_job (jobid, jobjclid, jobname, jobdesc, jobhostagent, jobenabled, jobcreated, jobchanged, jobagentid, jobnextrun, joblastrun) FROM stdin;
    pgagent          postgres    false    236   ۼ                 0    73846    pga_schedule 
   TABLE DATA           �   COPY pgagent.pga_schedule (jscid, jscjobid, jscname, jscdesc, jscenabled, jscstart, jscend, jscminutes, jschours, jscweekdays, jscmonthdays, jscmonths) FROM stdin;
    pgagent          postgres    false    240   ��                 0    73876    pga_exception 
   TABLE DATA           J   COPY pgagent.pga_exception (jexid, jexscid, jexdate, jextime) FROM stdin;
    pgagent          postgres    false    242   �                  0    73891 
   pga_joblog 
   TABLE DATA           X   COPY pgagent.pga_joblog (jlgid, jlgjobid, jlgstatus, jlgstart, jlgduration) FROM stdin;
    pgagent          postgres    false    244   2�                 0    73820    pga_jobstep 
   TABLE DATA           �   COPY pgagent.pga_jobstep (jstid, jstjobid, jstname, jstdesc, jstenabled, jstkind, jstcode, jstconnstr, jstdbname, jstonerror, jscnextrun) FROM stdin;
    pgagent          postgres    false    238   O�       !          0    73908    pga_jobsteplog 
   TABLE DATA           |   COPY pgagent.pga_jobsteplog (jslid, jsljlgid, jsljstid, jslstatus, jslresult, jslstart, jslduration, jsloutput) FROM stdin;
    pgagent          postgres    false    246   l�       6          0    41163    auta 
   TABLE DATA           �   COPY public.auta (idauto, vin, rejestracja, idsegment, idmodel, idpaliwo, mockw, skrzynia, liczbamiejsc, rok, sprawny, dostepny, przebieg, cenadoba, cenakm, uwagi, idzdjecie, aktywny) FROM stdin;
    public          postgres    false    205   ��       9          0    41178    klienci 
   TABLE DATA           �   COPY public.klienci (idklienta, rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, idmiasto, ulica, nrdomu, nrmieszkania, kodpocztowy, uwagi) FROM stdin;
    public          postgres    false    208   "�       M          0    41334    kraj 
   TABLE DATA           1   COPY public.kraj (idkraj, nazwakraj) FROM stdin;
    public          postgres    false    228   �       ;          0    41186    marka 
   TABLE DATA           4   COPY public.marka (idmarka, nazwamarki) FROM stdin;
    public          postgres    false    210   0�       O          0    41343    miasto 
   TABLE DATA           ?   COPY public.miasto (idmiasto, idkraj, nazwamiasto) FROM stdin;
    public          postgres    false    230   V�       K          0    41326    model 
   TABLE DATA           =   COPY public.model (idmodel, idmarka, nazwamodel) FROM stdin;
    public          postgres    false    226   ��       =          0    41191    paliwo 
   TABLE DATA           7   COPY public.paliwo (idpaliwo, nazwapaliwo) FROM stdin;
    public          postgres    false    212   ��       ?          0    41196 
   pracownicy 
   TABLE DATA           z   COPY public.pracownicy (idpracownika, login, haslo, imie, nazwisko, telefon, email, zatrudniony, uprawnienia) FROM stdin;
    public          postgres    false    214   �       @          0    41201    przeglad 
   TABLE DATA           [   COPY public.przeglad (idprzegladu, idauta, dataprzegladu, datawaznosci, uwagi) FROM stdin;
    public          postgres    false    215   �       B          0    41210    segment 
   TABLE DATA           :   COPY public.segment (idsegment, nazwasegment) FROM stdin;
    public          postgres    false    217   I�       D          0    41215    serwis 
   TABLE DATA           |   COPY public.serwis (idserwis, idauta, idpracownika, datapoczatek, datakoniec, nazwaserwisu, opis, uwagi, koszt) FROM stdin;
    public          postgres    false    219   ��       F          0    41226    wypozyczenia 
   TABLE DATA           �   COPY public.wypozyczenia (idwypozyczenia, idauta, idklienta, idpracownika, datapoczatek, datakoniec, przebiegstart, przebiegkoniec, suma, zaplacono, uwagi) FROM stdin;
    public          postgres    false    221   ,�       H          0    41237    zdjecia 
   TABLE DATA           <   COPY public.zdjecia (idzdjecie, tytul, sciezka) FROM stdin;
    public          postgres    false    223   ��       a           0    0    auta_idauto_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.auta_idauto_seq', 5, true);
          public          postgres    false    206            b           0    0    idpracownika_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.idpracownika_seq', 3, true);
          public          postgres    false    207            c           0    0    klienci_idklienta_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.klienci_idklienta_seq', 3, true);
          public          postgres    false    209            d           0    0    kraj_idkraj_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.kraj_idkraj_seq', 2, true);
          public          postgres    false    227            e           0    0    marka_idmarka_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.marka_idmarka_seq', 1, true);
          public          postgres    false    211            f           0    0    miasto_idmiasto_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.miasto_idmiasto_seq', 3, true);
          public          postgres    false    229            g           0    0    model_idmodel_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.model_idmodel_seq', 1, true);
          public          postgres    false    225            h           0    0    paliwo_idpaliwo_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.paliwo_idpaliwo_seq', 6, true);
          public          postgres    false    213            i           0    0    przeglad_idprzegladu_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.przeglad_idprzegladu_seq', 2, true);
          public          postgres    false    216            j           0    0    segment_idsegment_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.segment_idsegment_seq', 3, true);
          public          postgres    false    218            k           0    0    serwis_idserwis_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.serwis_idserwis_seq', 1, true);
          public          postgres    false    220            l           0    0    wypozyczenia_idwypozyczenia_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('public.wypozyczenia_idwypozyczenia_seq', 2, true);
          public          postgres    false    222            m           0    0    zdjecia_idzdjecie_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.zdjecia_idzdjecie_seq', 1, true);
          public          postgres    false    224            n           0    0    zdjecie-auto_idautozdj_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('public."zdjecie-auto_idautozdj_seq"', 1, true);
          public          postgres    false    231            g           2606    41255    auta auta_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.auta
    ADD CONSTRAINT auta_pkey PRIMARY KEY (idauto);
 8   ALTER TABLE ONLY public.auta DROP CONSTRAINT auta_pkey;
       public            postgres    false    205            q           2606    41257    klienci klienci_pkey 
   CONSTRAINT     Y   ALTER TABLE ONLY public.klienci
    ADD CONSTRAINT klienci_pkey PRIMARY KEY (idklienta);
 >   ALTER TABLE ONLY public.klienci DROP CONSTRAINT klienci_pkey;
       public            postgres    false    208            �           2606    41339    kraj kraj_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.kraj
    ADD CONSTRAINT kraj_pkey PRIMARY KEY (idkraj);
 8   ALTER TABLE ONLY public.kraj DROP CONSTRAINT kraj_pkey;
       public            postgres    false    228            s           2606    41259    marka marka_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY public.marka
    ADD CONSTRAINT marka_pkey PRIMARY KEY (idmarka);
 :   ALTER TABLE ONLY public.marka DROP CONSTRAINT marka_pkey;
       public            postgres    false    210            �           2606    41348    miasto miasto_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.miasto
    ADD CONSTRAINT miasto_pkey PRIMARY KEY (idmiasto);
 <   ALTER TABLE ONLY public.miasto DROP CONSTRAINT miasto_pkey;
       public            postgres    false    230            �           2606    41331    model model_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY public.model
    ADD CONSTRAINT model_pkey PRIMARY KEY (idmodel);
 :   ALTER TABLE ONLY public.model DROP CONSTRAINT model_pkey;
       public            postgres    false    226            u           2606    41261    paliwo paliwo_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.paliwo
    ADD CONSTRAINT paliwo_pkey PRIMARY KEY (idpaliwo);
 <   ALTER TABLE ONLY public.paliwo DROP CONSTRAINT paliwo_pkey;
       public            postgres    false    212            w           2606    41263    pracownicy pracownicy_login_key 
   CONSTRAINT     [   ALTER TABLE ONLY public.pracownicy
    ADD CONSTRAINT pracownicy_login_key UNIQUE (login);
 I   ALTER TABLE ONLY public.pracownicy DROP CONSTRAINT pracownicy_login_key;
       public            postgres    false    214            y           2606    41265    pracownicy pracownicy_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY public.pracownicy
    ADD CONSTRAINT pracownicy_pkey PRIMARY KEY (idpracownika);
 D   ALTER TABLE ONLY public.pracownicy DROP CONSTRAINT pracownicy_pkey;
       public            postgres    false    214            |           2606    41267    przeglad przeglad_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY public.przeglad
    ADD CONSTRAINT przeglad_pkey PRIMARY KEY (idprzegladu);
 @   ALTER TABLE ONLY public.przeglad DROP CONSTRAINT przeglad_pkey;
       public            postgres    false    215            ~           2606    41269    segment segment_pkey 
   CONSTRAINT     Y   ALTER TABLE ONLY public.segment
    ADD CONSTRAINT segment_pkey PRIMARY KEY (idsegment);
 >   ALTER TABLE ONLY public.segment DROP CONSTRAINT segment_pkey;
       public            postgres    false    217            �           2606    41271    serwis serwis_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.serwis
    ADD CONSTRAINT serwis_pkey PRIMARY KEY (idserwis);
 <   ALTER TABLE ONLY public.serwis DROP CONSTRAINT serwis_pkey;
       public            postgres    false    219            n           2606    49153    auta vin_unique 
   CONSTRAINT     I   ALTER TABLE ONLY public.auta
    ADD CONSTRAINT vin_unique UNIQUE (vin);
 9   ALTER TABLE ONLY public.auta DROP CONSTRAINT vin_unique;
       public            postgres    false    205            �           2606    41273    wypozyczenia wypozyczenia_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.wypozyczenia
    ADD CONSTRAINT wypozyczenia_pkey PRIMARY KEY (idwypozyczenia);
 H   ALTER TABLE ONLY public.wypozyczenia DROP CONSTRAINT wypozyczenia_pkey;
       public            postgres    false    221            �           2606    41275    zdjecia zdjecia_pkey 
   CONSTRAINT     Y   ALTER TABLE ONLY public.zdjecia
    ADD CONSTRAINT zdjecia_pkey PRIMARY KEY (idzdjecie);
 >   ALTER TABLE ONLY public.zdjecia DROP CONSTRAINT zdjecia_pkey;
       public            postgres    false    223            h           1259    41276    fki_auto_marka_fkey    INDEX     G   CREATE INDEX fki_auto_marka_fkey ON public.auta USING btree (idmodel);
 '   DROP INDEX public.fki_auto_marka_fkey;
       public            postgres    false    205            i           1259    41360    fki_auto_model_fkey    INDEX     G   CREATE INDEX fki_auto_model_fkey ON public.auta USING btree (idmodel);
 '   DROP INDEX public.fki_auto_model_fkey;
       public            postgres    false    205            j           1259    41277    fki_auto_paliwo_fkey    INDEX     I   CREATE INDEX fki_auto_paliwo_fkey ON public.auta USING btree (idpaliwo);
 (   DROP INDEX public.fki_auto_paliwo_fkey;
       public            postgres    false    205            k           1259    41278    fki_auto_segment_fkey    INDEX     K   CREATE INDEX fki_auto_segment_fkey ON public.auta USING btree (idsegment);
 )   DROP INDEX public.fki_auto_segment_fkey;
       public            postgres    false    205            l           1259    57369    fki_auto_zdjecie_fkey    INDEX     K   CREATE INDEX fki_auto_zdjecie_fkey ON public.auta USING btree (idzdjecie);
 )   DROP INDEX public.fki_auto_zdjecie_fkey;
       public            postgres    false    205            o           1259    41372    fki_klienci_miasto_fkey    INDEX     O   CREATE INDEX fki_klienci_miasto_fkey ON public.klienci USING btree (idmiasto);
 +   DROP INDEX public.fki_klienci_miasto_fkey;
       public            postgres    false    208            �           1259    41366    fki_miasto_kraj_fkey    INDEX     I   CREATE INDEX fki_miasto_kraj_fkey ON public.miasto USING btree (idkraj);
 (   DROP INDEX public.fki_miasto_kraj_fkey;
       public            postgres    false    230            �           1259    41354    fki_model_marka_fkey    INDEX     I   CREATE INDEX fki_model_marka_fkey ON public.model USING btree (idmarka);
 (   DROP INDEX public.fki_model_marka_fkey;
       public            postgres    false    226            z           1259    41279    fki_przeglad_auto_fkey    INDEX     M   CREATE INDEX fki_przeglad_auto_fkey ON public.przeglad USING btree (idauta);
 *   DROP INDEX public.fki_przeglad_auto_fkey;
       public            postgres    false    215                       1259    41280    fki_serwis_auto_fkey    INDEX     I   CREATE INDEX fki_serwis_auto_fkey ON public.serwis USING btree (idauta);
 (   DROP INDEX public.fki_serwis_auto_fkey;
       public            postgres    false    219            �           1259    41281    fki_wypozyczenia_auto_fkey    INDEX     U   CREATE INDEX fki_wypozyczenia_auto_fkey ON public.wypozyczenia USING btree (idauta);
 .   DROP INDEX public.fki_wypozyczenia_auto_fkey;
       public            postgres    false    221            �           1259    41282    fki_wypozyczenia_klienci_fkey    INDEX     [   CREATE INDEX fki_wypozyczenia_klienci_fkey ON public.wypozyczenia USING btree (idklienta);
 1   DROP INDEX public.fki_wypozyczenia_klienci_fkey;
       public            postgres    false    221            �           1259    41283     fki_wypozyczenia_pracownicy_fkey    INDEX     a   CREATE INDEX fki_wypozyczenia_pracownicy_fkey ON public.wypozyczenia USING btree (idpracownika);
 4   DROP INDEX public.fki_wypozyczenia_pracownicy_fkey;
       public            postgres    false    221            �           2620    73734    pracownicy check_admins    TRIGGER     �   CREATE TRIGGER check_admins AFTER INSERT OR UPDATE ON public.pracownicy FOR EACH ROW EXECUTE FUNCTION public.check_admin_count();
 0   DROP TRIGGER check_admins ON public.pracownicy;
       public          postgres    false    214    247            �           2620    81981 %   przeglad check_overlap_dates_przeglad    TRIGGER     �   CREATE TRIGGER check_overlap_dates_przeglad AFTER INSERT OR UPDATE ON public.przeglad FOR EACH ROW EXECUTE FUNCTION public.check_overlap_dates_przeglad();
 >   DROP TRIGGER check_overlap_dates_przeglad ON public.przeglad;
       public          postgres    false    215    268            �           2620    81980 -   wypozyczenia check_overlap_dates_wypozyczenia    TRIGGER     �   CREATE TRIGGER check_overlap_dates_wypozyczenia AFTER INSERT OR UPDATE ON public.wypozyczenia FOR EACH ROW EXECUTE FUNCTION public.check_overlap_dates_wypozyczenia();
 F   DROP TRIGGER check_overlap_dates_wypozyczenia ON public.wypozyczenia;
       public          postgres    false    221    267            �           2620    81982 %   serwis check_overlapping_dates_serwis    TRIGGER     �   CREATE TRIGGER check_overlapping_dates_serwis AFTER INSERT OR UPDATE ON public.serwis FOR EACH ROW EXECUTE FUNCTION public.check_overlap_dates_serwis();
 >   DROP TRIGGER check_overlapping_dates_serwis ON public.serwis;
       public          postgres    false    219    269            �           2606    41355    auta auto_model_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.auta
    ADD CONSTRAINT auto_model_fkey FOREIGN KEY (idmodel) REFERENCES public.model(idmodel) ON DELETE RESTRICT NOT VALID;
 >   ALTER TABLE ONLY public.auta DROP CONSTRAINT auto_model_fkey;
       public          postgres    false    226    2955    205            �           2606    41289    auta auto_paliwo_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.auta
    ADD CONSTRAINT auto_paliwo_fkey FOREIGN KEY (idpaliwo) REFERENCES public.paliwo(idpaliwo) ON DELETE RESTRICT NOT VALID;
 ?   ALTER TABLE ONLY public.auta DROP CONSTRAINT auto_paliwo_fkey;
       public          postgres    false    212    205    2933            �           2606    41294    auta auto_segment_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.auta
    ADD CONSTRAINT auto_segment_fkey FOREIGN KEY (idsegment) REFERENCES public.segment(idsegment) ON DELETE RESTRICT NOT VALID;
 @   ALTER TABLE ONLY public.auta DROP CONSTRAINT auto_segment_fkey;
       public          postgres    false    205    217    2942            �           2606    57364    auta auto_zdjecie_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.auta
    ADD CONSTRAINT auto_zdjecie_fkey FOREIGN KEY (idzdjecie) REFERENCES public.zdjecia(idzdjecie) ON DELETE RESTRICT NOT VALID;
 @   ALTER TABLE ONLY public.auta DROP CONSTRAINT auto_zdjecie_fkey;
       public          postgres    false    2952    205    223            �           2606    41367    klienci klienci_miasto_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.klienci
    ADD CONSTRAINT klienci_miasto_fkey FOREIGN KEY (idmiasto) REFERENCES public.miasto(idmiasto) NOT VALID;
 E   ALTER TABLE ONLY public.klienci DROP CONSTRAINT klienci_miasto_fkey;
       public          postgres    false    230    2960    208            �           2606    41361    miasto miasto_kraj_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.miasto
    ADD CONSTRAINT miasto_kraj_fkey FOREIGN KEY (idkraj) REFERENCES public.kraj(idkraj) ON DELETE RESTRICT NOT VALID;
 A   ALTER TABLE ONLY public.miasto DROP CONSTRAINT miasto_kraj_fkey;
       public          postgres    false    2957    230    228            �           2606    41349    model model_marka_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.model
    ADD CONSTRAINT model_marka_fkey FOREIGN KEY (idmarka) REFERENCES public.marka(idmarka) ON DELETE RESTRICT NOT VALID;
 @   ALTER TABLE ONLY public.model DROP CONSTRAINT model_marka_fkey;
       public          postgres    false    2931    210    226            �           2606    41299    przeglad przeglad_auto_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.przeglad
    ADD CONSTRAINT przeglad_auto_fkey FOREIGN KEY (idauta) REFERENCES public.auta(idauto) ON DELETE RESTRICT NOT VALID;
 E   ALTER TABLE ONLY public.przeglad DROP CONSTRAINT przeglad_auto_fkey;
       public          postgres    false    215    205    2919            �           2606    41304    serwis serwis_auto_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.serwis
    ADD CONSTRAINT serwis_auto_fkey FOREIGN KEY (idauta) REFERENCES public.auta(idauto) ON DELETE RESTRICT NOT VALID;
 A   ALTER TABLE ONLY public.serwis DROP CONSTRAINT serwis_auto_fkey;
       public          postgres    false    219    2919    205            �           2606    41309 #   wypozyczenia wypozyczenia_auto_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.wypozyczenia
    ADD CONSTRAINT wypozyczenia_auto_fkey FOREIGN KEY (idauta) REFERENCES public.auta(idauto) ON DELETE RESTRICT NOT VALID;
 M   ALTER TABLE ONLY public.wypozyczenia DROP CONSTRAINT wypozyczenia_auto_fkey;
       public          postgres    false    205    221    2919            �           2606    41314 &   wypozyczenia wypozyczenia_klienci_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.wypozyczenia
    ADD CONSTRAINT wypozyczenia_klienci_fkey FOREIGN KEY (idklienta) REFERENCES public.klienci(idklienta) ON DELETE RESTRICT NOT VALID;
 P   ALTER TABLE ONLY public.wypozyczenia DROP CONSTRAINT wypozyczenia_klienci_fkey;
       public          postgres    false    221    208    2929            �           2606    41319 )   wypozyczenia wypozyczenia_pracownicy_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.wypozyczenia
    ADD CONSTRAINT wypozyczenia_pracownicy_fkey FOREIGN KEY (idpracownika) REFERENCES public.pracownicy(idpracownika) ON DELETE RESTRICT NOT VALID;
 S   ALTER TABLE ONLY public.wypozyczenia DROP CONSTRAINT wypozyczenia_pracownicy_fkey;
       public          postgres    false    2937    214    221                  x������ � �            x������ � �            x������ � �            x������ � �            x������ � �             x������ � �            x������ � �      !      x������ � �      6   �   x��б
1�9}�#�������r�.uЧ��n-��#�躭k'/������ !��(������<b��J9�,���LRʾ��1��2`*k�������r':&�Ĉi�;�1��:�Ϲc�s���[t      9   �   x�m�;n�0D��)x�X�%D�,7l 'p�� �H��$���\�m:������L5���1=S��4/P�%�

BB�H9��\bb?9ۨ��ZC�!s{}�������l���b�z���c�F9����"���\e��w�]?�K�!��]��$�d�Dc��fz5Pq=��C5rmE��n�����T��H'0��A��뻸fB�K+O�      M      x�3���)�N�2���L�M������ N�      ;      x�3�ɯ�/I����� �m      O   ,   x�3�4��N,�/�LN�2r�s2�lcN#N�Ԣ��<�=... �Q
~      K      x�3�4�L,�,V������ (��      =   O   x�3�tJͫ��K�2�t�L-N��2�	i��s��y�~�\���IE�)�
�0aל�쒢�䪼�X.3�<�4T�=... I�%]      ?   �   x���;n�@D��a��~��.u��i�� ���!�P���^� ��.S��x��Q��)J/ށv�%��I�˥��U �D���D�	*V��qz`�	O��Z�5�ڟ'u=�ΪOnm��#�*ň�Z��`ІJ�Z��p���;Y@�`y��R��M�/��<����;��rZ�6�����vc�_���0���eX����<�{�0���O}�����q�      @   1   x�3�4�4202�5 "0��t*J��2�4�H�C�!L�t� �^[      B   5   x�3�tT�U�M<ڔ�e��bg�fgg�rs:������%��\1z\\\ G�2      D   �   x�E�=
�@F��S|ذY�^[Z��	L~v �,k�<�x/ӈ�{ŃWҎ<y�-�u�_�t�^pӬ+�̫&iZ������X6���'��б��t2�����4�Pǭ�0ID�A#��4�NnzA���-����
�L]c�cX/�      F   �   x�3�4C###]C#]##�X����LN#NC S�)����b��J�L2�,��6)��s���@�Ks��&��K�3S�R�����`��,A����Û�������8M8�9�\��40�P8�����;����W�$�e�Z)p��qqq �9C�      H   /   x�3�,ɯ�/I��L,�,��,���MLO-��u3���
ҹb���� ]2�     