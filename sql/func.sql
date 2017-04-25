DROP VIEW displayClans;
DROP VIEW displayTanks;
DROP VIEW gunName;
DROP VIEW engineName;
DROP VIEW role;
DROP VIEW uzytkownicy;
DROP VIEW klanyIlosc;
DROP VIEW zaloganciV;
DROP VIEW sumaObrazen;
DROP VIEW najDzialo;
DROP VIEW minDzialo;
DROP FUNCTION getTankInfo(character varying);
DROP FUNCTION getengineinfo(character varying);
DROP FUNCTION getguninfo(character varying);
DROP FUNCTION maxTier(BIGINT);
DROP FUNCTION tankstobuy(bigint);
DROP FUNCTION sellTank(BIGINT, BIGINT);
DROP FUNCTION getgunid(character varying);
DROP FUNCTION getengineid(character varying);
--funckje

/*CREATE OR REPLACE FUNCTION displayTanks() RETURNS table(v varchar, i integer) AS'
	SELECT NazwaCzolgu, Tier FROM Czolg;
'LANGUAGE 'sql';*/

CREATE OR REPLACE FUNCTION selectNick(varchar) RETURNS BOOLEAN LANGUAGE 'plpgsql' AS'
  DECLARE
    val INT;
  BEGIN
	  SELECT COUNT(*) INTO val FROM UzytkownicyP WHERE Nick=$1;
    IF val!=0 THEN
      RETURN 1;
    END IF;
    RETURN 0;
  END;
  ';

CREATE OR REPLACE FUNCTION selectEmail(varchar) RETURNS BOOLEAN LANGUAGE 'plpgsql' AS'
  DECLARE
    val INT;
  BEGIN
    SELECT COUNT(*) INTO val FROM UzytkownicyW WHERE EMail=$1;
  IF val!=0 THEN
    RETURN 1;
  END IF;
  RETURN 0;
  END;
';

CREATE OR REPLACE FUNCTION selectAdmin(varchar) RETURNS BOOLEAN LANGUAGE 'plpgsql' AS'
  DECLARE
    val INT;
  BEGIN
	  SELECT COUNT(*) INTO val FROM ADMIN WHERE Nick=$1;
    IF val!=0 THEN
      RETURN 1;
    END IF;
    RETURN 0;
  END;
  ';

CREATE OR REPLACE FUNCTION selectAdminPass(varchar) RETURNS text AS '
  SELECT Haslo FROM Admin WHERE Nick=$1;
'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION selectPassword(VARCHAR) RETURNS text AS '
  SELECT Haslo FROM UzytkownicyW WHERE EMail=$1;
'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getIDUzytkownika(VARCHAR) RETURNS BIGINT AS'
  SELECT IDUzytkownika FROM UzytkownicyW WHERE EMail=$1;
'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getTankInfo(VARCHAR) RETURNS table(tier INTEGER, pancerz VARCHAR, szybk VARCHAR,
wytrzymalosc INTEGER, widzenie VARCHAR, waga VARCHAR, doswiadczenie INTEGER, kredyty INTEGER, obrazek VARCHAR) AS '
  SELECT Tier::INTEGER, Pancerz, Szybkosc, Wytrzymalosc, ZasiegWidzenia, Waga, KosztDoswiadczenia, KosztKredyty, Obrazek FROM
    Czolg WHERE NazwaCzolgu=$1;
' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getEngineGunCrewID(VARCHAR) RETURNS table(IDSilnika INTEGER, IDDziala INTEGER, IDZ INTEGER) AS '
  SELECT IDSilnika, IDDziala, IDZaloganta FROM Czolg WHERE NazwaCzolgu=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getEngineName(INTEGER) RETURNS text AS '
  SELECT NazwaSilnika FROM Silnik WHERE IDSilnika = $1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getGunName(INTEGER) RETURNS text AS '
  SELECT NazwaDziala FROM Dzialo WHERE IDDziala = $1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getCrewName(INTEGER) RETURNS table(i text,n text,r text) AS '
  SELECT z.Imie, z.Nazwisko, r.NazwaRoli FROM Zalogant z INNER JOIN Rola r ON z.Rola=r.IDRoli WHERE IDZaloganta = $1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getEngineInfo(VARCHAR) RETURNS table(moc INTEGER, zapalenie VARCHAR) AS '
  SELECT Moc, SzansaNaZapalenie FROM Silnik WHERE NazwaSilnika=$1;
  'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getGunInfo(VARCHAR) RETURNS table(kaliber VARCHAR, obrazenia INTEGER, przeladowanie VARCHAR) AS '
  SELECT Kaliber, ZadawaneObrazenia, CzasPrzeladowania FROM Dzialo WHERE NazwaDziala=$1;
  'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getOsiagniecia(BIGINT) RETURNS table(bitwy INTEGER, obrazeniaZ BIGINT, obrazeniaP BIGINT,
  zniszczoneczolgi INTEGER, wyspotowaneczolgi BIGINT, doswiadczeniezabitwy BIGINT, zwyciestwa INTEGER, przegrane INTEGER,
  remisy INTEGER) AS '
  SELECT IloscBitew, IloscObrazenZadanych, PrzyjeteObrazenia, ZniszczoneCzolgi, WyspotowaneCzolgi, DoswiadczenieZaBitwy,
  Zwyciestwa, Przegrane, Remisy FROM OsiagnieciaU WHERE IdUzytkownika=$1;
  'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getZasoby(BIGINT) RETURNS table(ban TIMESTAMP, premium INTEGER, zloto BIGINT, kredyty BIGINT,
doswiadczenie BIGINT) AS'
  SELECT BanInfo, CzasKontaPremium, DostepneZloto, DostepneKredyty, DostepneDoswiadczenie FROM UzytkownicyW WHERE
    IDUzytkownika=$1;
' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION countClanPart(BIGINT) RETURNS BIGINT AS '
  SELECT COUNT(*) FROM UzytkownicyP WHERE IDKlanu=$1;
' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getClanID(BIGINT) RETURNS BIGINT AS '
  SELECT IDKlanu FROM UzytkownicyP WHERE IDUzytkownika=$1;
' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION updateClan(BIGINT, BIGINT) RETURNS VOID AS '
  UPDATE UzytkownicyP SET IDKlanu = $1 WHERE IDUzytkownika = $2;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION zasobyKlanu(BIGINT) RETURNS table(zasoby BIGINT, zarabianie INTEGER, kredyty INTEGER) AS '
  SELECT ZgromadzoneZasoby, IloscBonusowDoZarabiania, IloscBonusowDoDoswiadczenia FROM KlanyW WHERE IDKlanu=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getClanName(BIGINT) RETURNS text AS '
  SELECT NazwaKlanu FROM Klany WHERE IDKlanu = $1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getIDU(VARCHAR) RETURNS BIGINT AS'
  SELECT IDUzytkownika FROM UzytkownicyP WHERE Nick=$1;
'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION countTanks(BIGINT) RETURNS BIGINT AS '
  SELECT COUNT(*) FROM CzolgiWGarazu WHERE IDUzytkownika=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION maxTier(BIGINT) RETURNS INTEGER AS '
  SELECT MAX(Tier::INTEGER) FROM Czolg INNER JOIN CzolgiWGarazu ON Czolg.IDCzolgu = CzolgiWGarazu.IDCzolgu WHERE IDUzytkownika=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getClanName1(BIGINT) RETURNS text AS ' --idUzytkownika
  SELECT NazwaKlanu FROM Klany INNER JOIN UzytkownicyP ON UzytkownicyP.IDKlanu = Klany.IDKlanu WHERE
    UzytkownicyP.IDUzytkownika = $1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getTanksGarage(BIGINT) RETURNS table(id INTEGER, pancerz INTEGER, hp INTEGER, widzenie INTEGER) AS '
  SELECT IDCzolgu, PancerzU::INTEGER, WytrzymaloscU::INTEGER, ZasiegWidzeniaU::INTEGER FROM CzolgiWGarazu WHERE IDUzytkownika=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION tanksToBuy(BIGINT) RETURNS table(id INTEGER, nazwa VARCHAR, kredyty INTEGER, doswiadczenie INTEGER) AS '
  SELECT IDCzolgu, NazwaCzolgu, KosztKredyty, KosztDoswiadczenia FROM Czolg WHERE Czolg.IDCzolgu NOT IN (SELECT CzolgiWGarazu.IDCzolgu FROM CzolgiWGarazu WHERE
  CzolgiWGarazu.IDUzytkownika=$1);
  'LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION isInGarage(BIGINT, BIGINT) RETURNS BIGINT AS'
  SELECT IDTable FROM CzolgiWGarazu WHERE IDUzytkownika=$1 AND IDCzolgu=$2;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION sellTank(BIGINT, BIGINT) RETURNS INTEGER AS'
  DELETE FROM CzolgiWGarazu WHERE IDUzytkownika=$1 AND IDCzolgu=$2 RETURNING 1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION creditsAndExpU(BIGINT) RETURNS table(kredyty BIGINT, doswiadczenie BIGINT) AS'
  SELECT DostepneKredyty, DostepneDoswiadczenie FROM UzytkownicyW WHERE IDUzytkownika=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION creditsAndExpT(INTEGER) RETURNS table(kredyty INTEGER, doswiadczenie INTEGER) AS '
  SELECT KosztKredyty, KosztDoswiadczenia FROM Czolg WHERE IDCzolgu = $1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getEngineID(VARCHAR) RETURNS INTEGER AS '
  SELECT IDSilnika FROM Silnik WHERE NazwaSilnika=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getGunID(VARCHAR) RETURNS INTEGER AS '
  SELECT IDDziala FROM Dzialo WHERE NazwaDziala=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getRolaID(VARCHAR) RETURNS INTEGER AS '
  SELECT IDRoli FROM Rola WHERE NazwaRoli=$1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getTankName(INTEGER) RETURNS VARCHAR AS '
  SELECT NazwaCzolgu FROM Czolg WHERE IDCzolgu = $1;
  ' LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION getUserName(BIGINT) RETURNS text AS '
  SELECT Nick FROM UzytkownicyP WHERE IDUzytkownika = $1;
 ' LANGUAGE 'sql';

--Statystyki

--widoki
CREATE VIEW displayClans AS SELECT * FROM Klany;

CREATE VIEW displayTanks AS SELECT NazwaCzolgu, Tier FROM Czolg ORDER BY Tier;

CREATE VIEW engineName AS SELECT NazwaSilnika FROM Silnik ORDER BY NazwaSilnika;

CREATE VIEW gunName AS SELECT NazwaDziala FROM Dzialo ORDER BY NazwaDziala;

CREATE VIEW role AS SELECT NazwaRoli FROM Rola;

CREATE VIEW uzytkownicy AS SELECT COUNT(*) FROM UzytkownicyP;

CREATE VIEW klanyIlosc AS SELECT COUNT(*) FROM Klany;

CREATE VIEW sumaObrazen AS SELECT SUM(IloscObrazenZadanych) FROM OsiagnieciaU;

CREATE VIEW najDzialo AS SELECT d.NazwaDziala, d.ZadawaneObrazenia FROM Dzialo d WHERE d.ZadawaneObrazenia =
            (SELECT MAX(ZadawaneObrazenia) FROM Dzialo);

CREATE VIEW minDzialo AS SELECT d.NazwaDziala, d.ZadawaneObrazenia FROM Dzialo d WHERE d.ZadawaneObrazenia =
                                                                                       (SELECT MIN(ZadawaneObrazenia) FROM Dzialo);

CREATE VIEW zaloganciV AS SELECT z.IDZaloganta, z.Imie, z.Nazwisko, r.NazwaRoli FROM Zalogant z INNER JOIN
  Rola r ON r.IDRoli = z.Rola ORDER BY r.NazwaRoli;

--wyzwalacz




































