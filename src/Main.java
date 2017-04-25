import java.io.*;
import java.util.Scanner;

/**
 * Created by deleviretta on 15.01.17.
 */


public class Main {
    public static void main(String[] args) {
        /**
         * Zmienne
         */
        String filePath, separator, insertDetail;
        /**
         * Obsluga dziala
         */
        filePath = "/home/deleviretta/Pulpit/bazy/dzialo.txt";
        separator = "--INSERT FOR DZIALA\n";
        insertDetail = "INSERT INTO Dzialo(NazwaDziala, Kaliber, ZadawaneObrazenia, CzasPrzeladowania) VALUES";
        Metody.insertDziala(filePath,separator,insertDetail);
        /**
         * Obsulga silnikow
         */
        filePath = "/home/deleviretta/Pulpit/bazy/silnik.txt";
        separator = "\n--INSERT FOR SILNIKI\n";
        insertDetail = "INSERT INTO Silnik(Moc, SzansaNaZapalenie, NazwaSilnika) VALUES";
        Metody.insertSilniki(filePath, separator, insertDetail);
        /**
         * Obsluga zalogantow
         */
        filePath = "/home/deleviretta/Pulpit/bazy/zalogant.txt";
        separator = "\n--INSERT FOR ZALOGANT\n";
        insertDetail = "INSERT INTO Zalogant(Imie, Nazwisko, Rola) VALUES";
        Metody.insertZalogant(filePath, separator, insertDetail);
        /**
         * Obsluga czolgow
         */
        filePath = "/home/deleviretta/Pulpit/bazy/czolg.txt";
        separator = "\n--INSERT FOR CZOLG\n";
        insertDetail = "INSERT INTO Czolg(IDSilnika, IDDziala, IDZaloganta, Tier, NazwaCzolgu, Pancerz, Szybkosc, Wytrzymalosc, ZasiegWidzenia, Waga, KosztDoswiadczenia, KosztKredyty, Obrazek) VALUES";
        Metody.insertCzolg(filePath, separator, insertDetail);
        /**
         * Obsluga uzytkownikow
         */
        filePath = "/home/deleviretta/Pulpit/bazy/nicki.txt";
        separator = "\n--INSERT FOR UZYTKOWNICY\n";
        insertDetail = "INSERT INTO UzytkownicyP(IDKlanu, Nick) VALUES";
        Metody.insertUzytkownicy(filePath, separator, insertDetail);
        /**
         * Obsluga osiagniec
         */
        separator = "\n--INSERT FOR CZOLG\n";
        insertDetail = "INSERT INTO OsiagnieciaU(IDUzytkownika,IloscBitew, IloscObrazenZadanych, PrzyjeteObrazenia, ZniszczoneCzolgi, WyspotowaneCzolgi, DoswiadczenieZaBitwy, Zwyciestwa, Przegrane, Remisy) VALUES";
        Metody.insertOsiagnieciaU(separator,insertDetail, Metody.countLines("/home/deleviretta/Pulpit/bazy/nicki.txt"));

        /**
         * Obsluga klanow
         */
        filePath = "/home/deleviretta/Pulpit/bazy/klany.txt";
        separator = "\n--INSERT FOR KLANY\n";
        insertDetail = "INSERT INTO Klany(NazwaKlanu, PoziomTwierdzy) VALUES";
        Metody.insertKlany(filePath,separator,insertDetail);
        /**
         * Obsluga klanowW
         */
        separator = "\n--INSERT FOR KLANYW\n";
        insertDetail = "INSERT INTO KlanyW(IDKlanu, ZgromadzoneZasoby, IloscBonusowDoZarabiania, " +
                "IloscBonusowDoDoswiadczenia) VALUES";
        Metody.insertKlanyW(separator,insertDetail, Metody.countLines("/home/deleviretta/Pulpit/bazy/klany.txt"));
        /**
         * Obsluga uzytkownikowW
         */
        filePath = "/home/deleviretta/Pulpit/bazy/nicki.txt";
        separator = "\n--INSERT FOR UZYTKOWNICYW\n";
        insertDetail = "INSERT INTO UzytkownicyW(IDUzytkownika,EMail, Haslo, CzasKontaPremium, DostepneZloto, " +
                "DostepneKredyty, DostepneDoswiadczenie) VALUES";
        Metody.insertUzytkownicyW(filePath, separator, insertDetail);
    }
}

