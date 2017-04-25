CREATE TABLE 
magazyn
(
artykul_id SERIAL,
nazwa TEXT,
ilosc INT
);
CREATE FUNCTION 
obsluga() 
RETURNS 
OPAQUE 
AS
'
DECLARE
o integer;
n integer;
artykul record;
BEGIN
IF (TG_OP = ''UPDATE'') THEN
o := old.ilosc;
n := new.ilosc;
RAISE NOTICE ''Zmieniona liczebnosc z % na %'',o,n;
RETURN NEW;
ELSIF (TG_OP = ''INSERT'') THEN
SELECT * INTO artykul FROM magazyn WHERE nazwa = new.nazwa;
IF NOT FOUND THEN
RAISE NOTICE ''Wszystko dobrze 
-
nie
ma takiego rekordu w tablicy
-
wstawiamy'';
RETURN NEW;
ELSE
RAISE NOTICE ''BLAD 
–
Taki rekord juz istnieje !'';
RETURN NULL;
END IF;
END IF;
END;
' LANGUAGE 'plpgsql';
CREATE TRIGGER 
magazynier 
BEFORE INSERT OR UPDATE ON 
magazyn
FOR EACH ROW EXECUTE PROCEDURE 
obsluga();
