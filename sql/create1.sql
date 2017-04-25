DROP TABLE UzytkownicyP CASCADE;
DROP TABLE UzytkownicyW CASCADE;
DROP TABLE OsiagnieciaU CASCADE;
DROP TABLE Klany CASCADE;
DROP TABLE KlanyW CASCADE;
DROP TABLE Silnik CASCADE;
DROP TABLE Dzialo CASCADE;
DROP TABLE Rola CASCADE;
DROP TABLE Czolg CASCADE;
DROP TABLE Zalogant CASCADE;
DROP TABLE CzolgiWGarazu CASCADE;
DROP TABLE Admin;
DROP SEQUENCE seqU;
DROP SEQUENCE seqK;
DROP SEQUENCE seqS;
DROP SEQUENCE seqC;
DROP SEQUENCE seqD;
DROP SEQUENCE seqZ;
DROP SEQUENCE seqCWG;
DROP SEQUENCE seqA;
DROP SEQUENCE seqR;
DROP DOMAIN number;
DROP DOMAIN url;
DROP DOMAIN oneten;
DROP DOMAIN zeroten;
DROP DOMAIN onehundred;

-- SEKWENCJE DO ID TABEL
CREATE SEQUENCE seqU INCREMENT 1 START 600000; --uzytkownicy
CREATE SEQUENCE seqK INCREMENT 1 START 50000; --klany
CREATE SEQUENCE seqC INCREMENT 1 START 4000; --czolgi
CREATE SEQUENCE seqD INCREMENT 1 START 300; --dziala
CREATE SEQUENCE seqS INCREMENT 1 START 200; --silniki
CREATE SEQUENCE seqZ INCREMENT 1 START 1; --zaloga
CREATE SEQUENCE seqCWG INCREMENT 1 START 1; --czolgiwgarazu
CREATE SEQUENCE seqA INCREMENT 1 START 1;
CREATE SEQUENCE seqR INCREMENT 1 START 1;

--DOMENY
CREATE DOMAIN number AS TEXT
CHECK(VALUE ~ '^[0-9]+$');

CREATE DOMAIN url AS TEXT
CHECK (VALUE ~ '[^:]+:\/\/[A-Za-z][-a-zA-Z0-9]*(\.[A-Za-z][-a-zA-Z0-9]*)*/');

CREATE DOMAIN zeroten AS TEXT
CHECK (VALUE ~ '[0-9]|10');

CREATE DOMAIN oneten AS TEXT
CHECK (VALUE ~ '[1-9]|10');

CREATE DOMAIN onehundred AS TEXT
CHECK (VALUE ~ '[1-9][0-9]');


--KLANY
CREATE TABLE Klany(IDKlanu BIGINT DEFAULT nextval('seqK') PRIMARY KEY,
		   NazwaKlanu VARCHAR(45) UNIQUE NOT NULL,
		   PoziomTwierdzy zeroten DEFAULT 0 NOT NULL,
		   DataZalozenia TIMESTAMP default current_timestamp);

CREATE TABLE KlanyW(IDKlanu BIGINT UNIQUE REFERENCES Klany(IDKlanu),
		    ZgromadzoneZasoby BIGINT DEFAULT 0 NOT NULL,
	      IloscBonusowDoZarabiania INTEGER DEFAULT 0 NOT NULL,
		    IloscBonusowDoDoswiadczenia INTEGER DEFAULT 0 NOT NULL);

--UZYTKOWNICY INFORMACJE O UZYTKOWNIKACH
CREATE TABLE UzytkownicyP(IDUzytkownika BIGINT DEFAULT nextval('seqU') PRIMARY KEY,
			  IDKlanu BIGINT DEFAULT null REFERENCES Klany(IDKlanu),
			  Nick VARCHAR(40) UNIQUE,
			  DataDolaczenia TIMESTAMP default current_timestamp);

CREATE TABLE UzytkownicyW(IDUzytkownika BIGINT UNIQUE REFERENCES UzytkownicyP(IDUzytkownika),
			  EMail VARCHAR(60) NOT NULL UNIQUE, 
		    Haslo VARCHAR(40) NOT NULL,
			  BanInfo TIMESTAMP DEFAULT null,
			  CzasKontaPremium INTEGER DEFAULT 3 NOT NULL,
			  DostepneZloto BIGINT DEFAULT 200 NOT NULL, 
			  DostepneKredyty BIGINT DEFAULT 10000 NOT NULL,
		    DostepneDoswiadczenie BIGINT DEFAULT 5000 NOT NULL);

CREATE TABLE OsiagnieciaU(IDUzytkownika BIGINT UNIQUE REFERENCES UzytkownicyP(IDUzytkownika),
			  IloscBitew INTEGER DEFAULT 0 NOT NULL,
			  IloscObrazenZadanych BIGINT DEFAULT 0 NOT NULL,
			  PrzyjeteObrazenia BIGINT DEFAULT 0 NOT NULL,
			  ZniszczoneCzolgi INTEGER DEFAULT 0 NOT NULL,
        WyspotowaneCzolgi BIGINT DEFAULT 0 NOT NULL,
			  DoswiadczenieZaBitwy BIGINT DEFAULT 0 NOT NULL,
			  Zwyciestwa INTEGER DEFAULT 0 NOT NULL,
			  Przegrane INTEGER DEFAULT 0 NOT NULL,
			  Remisy INTEGER DEFAULT 0 NOT NULL);

--KOMPONENTY DO CZOLGU 
CREATE TABLE Silnik(IDSilnika INTEGER DEFAULT nextval('seqS') PRIMARY KEY,
		    Moc INTEGER NOT NULL,
		    SzansaNaZapalenie onehundred NOT NULL,
		    NazwaSilnika VARCHAR NOT NULL UNIQUE);

CREATE TABLE Dzialo(IDDziala INTEGER DEFAULT nextval('seqD') PRIMARY KEY,
        NazwaDziala VARCHAR NOT NULL UNIQUE,
		    Kaliber VARCHAR NOT NULL,
		    ZadawaneObrazenia INTEGER NOT NULL,
		    CzasPrzeladowania VARCHAR NOT NULL);

CREATE TABLE Rola(IDRoli INTEGER PRIMARY KEY DEFAULT nextval('seqR'),
									NazwaRoli VARCHAR(40) NOT NULL);

--ZALOGANT
CREATE TABLE Zalogant(IDZaloganta INTEGER DEFAULT nextval('seqZ') PRIMARY KEY,
											Imie VARCHAR(30) NOT NULL,
											Nazwisko VARCHAR(30) NOT NULL,
											Rola INTEGER NOT NULL REFERENCES Rola(IDRoli));

--CZOLGI
CREATE TABLE Czolg(IDCzolgu INTEGER DEFAULT nextval('seqC') PRIMARY KEY,
		   IDSilnika INTEGER REFERENCES Silnik(IDSilnika),
		   IDDziala INTEGER REFERENCES Dzialo(IDDziala),
			 IDZaloganta INTEGER REFERENCES Zalogant(IDZaloganta),
		   Tier oneten NOT NULL,
		   NazwaCzolgu VARCHAR(60) NOT NULL UNIQUE,
		   Pancerz VARCHAR NOT NULL,
		   Szybkosc VARCHAR NOT NULL,
		   Wytrzymalosc INTEGER NOT NULL,
		   ZasiegWidzenia VARCHAR NOT NULL,
       Waga VARCHAR NOT NULL,
       KosztDoswiadczenia INTEGER NOT NULL,
		   KosztKredyty INTEGER NOT NULL,
		   Obrazek url NOT NULL);

--CZOLGI UZYTKOWNIKOW
CREATE TABLE CzolgiWGarazu(IDTable BIGINT PRIMARY KEY DEFAULT nextval('seqCWG'), 
			   IDCzolgu INTEGER REFERENCES Czolg(IDCzolgu),
			   IDUzytkownika BIGINT REFERENCES UzytkownicyP(IDUzytkownika),
				 PancerzU number NOT NULL DEFAULT 20,
	       WytrzymaloscU number NOT NULL DEFAULT 20,
	       ZasiegWidzeniaU number NOT NULL DEFAULT 20);

--ADMINISTRATOR - uprawnienia do dodawania obiektów
CREATE TABLE Admin(IDAdmin INTEGER PRIMARY KEY DEFAULT nextval('seqA'),
								   Nick VARCHAR NOT NULL UNIQUE,
	                 Haslo VARCHAR NOT NULL);

--wyzwalacz

CREATE OR REPLACE FUNCTION dodanieDanychCzolg() RETURNS TRIGGER AS '
BEGIN
  NEW.Pancerz := NEW.Pancerz || ''mm'';
  NEW.Szybkosc := NEW.Szybkosc || ''km\h'';
	NEW.ZasiegWidzenia := NEW.ZasiegWidzenia || ''m'';
	NEW.Waga := NEW.Waga || ''t'';
  RETURN NEW;
END' LANGUAGE 'plpgsql';

CREATE TRIGGER dodawanieCzolg BEFORE INSERT ON Czolg FOR EACH ROW EXECUTE PROCEDURE dodanieDanychCzolg();

CREATE OR REPLACE FUNCTION dodanieDanychSilnik() RETURNS TRIGGER AS '
BEGIN
  NEW.SzansaNaZapalenie := NEW.SzansaNaZapalenie || ''%'';
  RETURN NEW;
END' LANGUAGE 'plpgsql';

CREATE TRIGGER dodawanieSilnik BEFORE INSERT ON Silnik FOR EACH ROW EXECUTE PROCEDURE dodanieDanychSilnik();

CREATE OR REPLACE FUNCTION dodanieDanychDzialo() RETURNS TRIGGER AS '
BEGIN
  NEW.Kaliber := NEW.Kaliber || ''mm'';
	NEW.CzasPrzeladowania := NEW.CzasPrzeladowania || ''s'';
  RETURN NEW;
END' LANGUAGE 'plpgsql';

CREATE TRIGGER dodawanieDzialo BEFORE INSERT ON Dzialo FOR EACH ROW EXECUTE PROCEDURE dodanieDanychDzialo();

CREATE OR REPLACE FUNCTION czolgDoGarazu() RETURNS TRIGGER AS '
BEGIN
		IF((NEW.PancerzU::INTEGER+NEW.WytrzymaloscU::INTEGER+NEW.ZasiegWidzeniaU::INTEGER)=60) THEN
	 		RETURN NEW;
		END IF;
	RETURN NULL;
END ' LANGUAGE 'plpgsql';

CREATE TRIGGER czolgGaraz BEFORE INSERT ON CzolgiWGarazu FOR EACH ROW EXECUTE PROCEDURE czolgDoGarazu();

--gdy czolg jest kupowany - (sprzedawany wartosc do insertu z plusem
CREATE OR REPLACE FUNCTION ubytek() RETURNS TRIGGER AS '
BEGIN
	NEW.DostepneKredyty := OLD.DostepneKredyty + NEW.DostepneKredyty;
	NEW.DostepneDoswiadczenie := OLD.DostepneDoswiadczenie + NEW.DostepneDoswiadczenie;
	RETURN NEW;
END ' LANGUAGE 'plpgsql';

CREATE TRIGGER ubytekZlotaDoswiadczenia BEFORE UPDATE ON UzytkownicyW FOR EACH ROW EXECUTE PROCEDURE ubytek();



INSERT INTO Klany(NazwaKlanu, PoziomTwierdzy) VALUES('Dywizjon Czolgistów', 10);
INSERT INTO KlanyW(IDKlanu, ZgromadzoneZasoby, IloscBonusowDoZarabiania, IloscBonusowDoDoswiadczenia) VALUES(50000, 10000, 5, 20);
INSERT INTO UzytkownicyP(IDKlanu, Nick) VALUES (50000, 'test');
INSERT INTO UzytkownicyW(IDUzytkownika, EMail, Haslo, CzasKontaPremium, DostepneZloto, DostepneKredyty, DostepneDoswiadczenie) VALUES (600000,'test@o2.pl', 'test', 10, 500, 200000000, 20000000);
INSERT INTO OsiagnieciaU(IDUzytkownika, IloscBitew, IloscObrazenZadanych, PrzyjeteObrazenia, ZniszczoneCzolgi, WyspotowaneCzolgi, DoswiadczenieZaBitwy, Zwyciestwa, Przegrane, Remisy) VALUES (600000, 20000, 3000000, 2500099, 80000, 1000000, 100000000, 10000, 10000, 100);
INSERT INTO Silnik(Moc, SzansaNaZapalenie, NazwaSilnika) VALUES (700, 12, 'V-2-54IS');
INSERT INTO Dzialo(NazwaDziala, Kaliber, ZadawaneObrazenia, CzasPrzeladowania) VALUES ('152 mm BL-10', 152, 750, 17.5);
INSERT INTO Rola(NazwaRoli) VALUES('Dowodzenie');
INSERT INTO Rola(NazwaRoli) VALUES('Szybsze celowanie');
INSERT INTO Rola(NazwaRoli) VALUES('Lepsza jazda');
INSERT INTO Rola(NazwaRoli) VALUES('Lepsze celowanie');
INSERT INTO Rola(NazwaRoli) VALUES ('Lepszy zasięg widzenia');
INSERT INTO Rola(NazwaRoli) VALUES ('Mniejsza szansa na podpalenie');
INSERT INTO Rola(NazwaRoli) VALUES ('Mniejsza szansa na wybuch magazynu');
INSERT INTO Zalogant(Imie, Nazwisko, Rola) VALUES('Siergiej', 'Lawnuszko', 1);
INSERT INTO Czolg(IDSilnika, IDDziala, IDZaloganta, Tier, NazwaCzolgu, Pancerz, Szybkosc, Wytrzymalosc, ZasiegWidzenia,
									Waga, KosztDoswiadczenia, KosztKredyty, Obrazek) VALUES(200, 300, 1, 10, 'Bat-chat',
									100, 50, 1500, 300, 15, 200000, 5000000, 'http://wiki.gcdn.co/images/2/28/AnnoLtraktor.png');
INSERT INTO CzolgiWGarazu(IDCzolgu, IDUzytkownika) VALUES(4000, 600000);
INSERT INTO Admin(Nick, Haslo) VALUES ('admin', 'admin');




