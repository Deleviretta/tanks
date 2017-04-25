import java.io.*;
import java.util.Scanner;

/**
 * Created by deleviretta on 15.01.17.
 */
public class Metody {
    public static int countLines(String filename) {
        int lines = 0;
        try {
            BufferedReader reader = new BufferedReader(new FileReader(filename));
            while (reader.readLine() != null) lines++;
            reader.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
        return lines;
    }

    public static void insertUzytkownicy(String filePath, String separator, String insertDetail) {
        int linesNumber;
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Scanner odczyt;
        Writer output;
        String line;
        try {
            odczyt = new Scanner(new File(filePath));
            output = new BufferedWriter(new FileWriter(insertPath, true));
            linesNumber = Metody.countLines(filePath);
            Uzytkownicy[] uzytkownicyT = new Uzytkownicy[linesNumber];
            output.write(separator);
            for (int i = 0; i < linesNumber; i++) {
                line = odczyt.nextLine();
                uzytkownicyT[i] = new Uzytkownicy(line, null);
                line = new String(insertDetail);
                line += "(" + uzytkownicyT[i].getIdKlanu() + ", " + uzytkownicyT[i].getNick() + ");" + "\n";
                output.write(line);
            }
            odczyt.close();
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void insertUzytkownicyW(String filePath, String separator, String insertDetail){
        int linesNumber;
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Scanner odczyt;
        Writer output;
        String line;
        /*INSERT INTO UzytkownicyW(IDUzytkownika, EMail, Haslo, CzasKontaPremium, DostepneZloto,
        DostepneKredyty, DostepneDoswiadczenie)
         */
        try {
            odczyt = new Scanner(new File(filePath));
            output = new BufferedWriter(new FileWriter(insertPath, true));
            linesNumber = Metody.countLines(filePath);
            UzytkownicyW[] uzytkownicy = new UzytkownicyW[linesNumber];
            output.write(separator);
            for (int i = 0; i < linesNumber; i++) {
                line = odczyt.nextLine();
                uzytkownicy[i] = new UzytkownicyW(line);
                line = new String(insertDetail);
                line += "(" + uzytkownicy[i].getIdUzytkownika() + "," + uzytkownicy[i].getEmail() + "," +
                        uzytkownicy[i].getPassword() + "," + uzytkownicy[i].getKontoPremium() + "," +
                        uzytkownicy[i].getZloto() + "," + uzytkownicy[i].getKredyty() + "," +
                        uzytkownicy[i].getDoswiadczenie()+ ");" + "\n";
                output.write(line);
            }
            odczyt.close();
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }


    public static void insertCzolg(String filePath, String separator, String insertDetail) {
        int linesNumber;
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Scanner odczyt;
        Writer output;
        String line;
        try {
            odczyt = new Scanner(new File(filePath));
            output = new BufferedWriter(new FileWriter(insertPath, true));
            linesNumber = Metody.countLines(filePath);
            Czolg[] czolgi = new Czolg[linesNumber];
            output.write(separator);
            for (int i = 0; i < linesNumber; i++) {
                line = odczyt.nextLine();
                czolgi[i] = new Czolg(line);
                line = new String(insertDetail);
                line += czolgi[i].getInsert() + "\n";
                output.write(line);
            }
            odczyt.close();
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void insertZalogant(String filePath, String separator, String insertDetail) {
        int linesNumber;
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Scanner odczyt;
        Writer output;
        String line;
        try {
            odczyt = new Scanner(new File(filePath));
            output = new BufferedWriter(new FileWriter(insertPath, true));
            linesNumber = Metody.countLines(filePath);
            Zalogant[] zaloganci = new Zalogant[linesNumber];
            output.write(separator);
            for (int i = 0; i < linesNumber; i++) {
                line = odczyt.nextLine();
                zaloganci[i] = new Zalogant(line);
                line = new String(insertDetail);
                line += zaloganci[i].getInsert() + "\n";
                output.write(line);
            }
            odczyt.close();
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void insertSilniki(String filePath, String separator, String insertDetail) {
        int linesNumber;
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Scanner odczyt;
        Writer output;
        String line;
        try {
            odczyt = new Scanner(new File(filePath));
            output = new BufferedWriter(new FileWriter(insertPath, true));
            linesNumber = Metody.countLines(filePath);
            Silnik[] silniki = new Silnik[linesNumber];
            output.write(separator);
            for (int i = 0; i < linesNumber; i++) {
                line = odczyt.nextLine();
                silniki[i] = new Silnik(line);
                line = new String(insertDetail);
                line += silniki[i].getInsert() + "\n";
                output.write(line);
            }
            odczyt.close();
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void insertDziala(String filePath, String separator, String insertDetail) {
        int linesNumber;
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Scanner odczyt;
        String line;
        PrintWriter zapis;
        try {
            odczyt = new Scanner(new File(filePath));
            zapis = new PrintWriter(insertPath);
            linesNumber = Metody.countLines(filePath);
            Dzialo[] dziala = new Dzialo[linesNumber];
            zapis.println(separator);
            for (int i = 0; i < linesNumber; i++) {
                line = odczyt.nextLine();
                dziala[i] = new Dzialo(line);
                line = insertDetail;
                line += dziala[i].getInsert();
                zapis.println(line);
            }
            odczyt.close();
            zapis.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
    }

    /**
     * Osiagniecia insert
     *
     * @param separator
     * @param insertDetail
     * @param ileU
     */

    public static void insertOsiagnieciaU(String separator, String insertDetail, Integer ileU) {
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Writer output;
        String line;
        try {
            //INSERT INTO OsiagnieciaU(IDUzytkownika, IloscBitew, IloscObrazenZadanych, PrzyjeteObrazenia,
            // ZniszczoneCzolgi, WyspotowaneCzolgi, DoswiadczenieZaBitwy, Zwyciestwa, Przegrane, Remisy)
            output = new BufferedWriter(new FileWriter(insertPath, true));
            OsiagnieciaU[] osiagniecia = new OsiagnieciaU[ileU];
            output.write(separator);
            for (int i = 0; i < ileU; i++) {
                osiagniecia[i] = new OsiagnieciaU();
                line = new String(insertDetail);
                line += "(" + osiagniecia[i].getIdUzytkownika() + "," + osiagniecia[i].getIloscBitew() + "," +
                        osiagniecia[i].getIloscObrazenZadanych() + "," + osiagniecia[i].getPrzyjeteObrazenia() + "," +
                        osiagniecia[i].getZniszczoneCzolgi() + "," + osiagniecia[i].getWyspotowaneCzolgi() + "," +
                        osiagniecia[i].getDoswiadczenieZaBitwy() + "," + osiagniecia[i].getZwyciestwa() + "," +
                        osiagniecia[i].getPrzegrane() + "," + osiagniecia[i].getRemisy() + ");" + "\n";
                output.write(line);
            }
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Klany insert
     *
     * @param filePath
     * @param separator
     * @param insertDetail
     */
    public static void insertKlany(String filePath, String separator, String insertDetail) {
        int linesNumber;
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Scanner odczyt;
        Writer output;
        String line;
        try {
            odczyt = new Scanner(new File(filePath));
            output = new BufferedWriter(new FileWriter(insertPath, true));
            linesNumber = Metody.countLines(filePath);
            Klany[] klanyT = new Klany[linesNumber];
            output.write(separator);
            for (int i = 0; i < linesNumber; i++) {
                line = odczyt.nextLine();
                klanyT[i] = new Klany(line);
                line = new String(insertDetail);
                line += "(" + klanyT[i].getNazwa() + ", " + klanyT[i].getPoziomTwierdzy() + ");" + "\n";
                output.write(line);
            }
            odczyt.close();
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void insertKlanyW(String separator, String insertDetail, Integer ileK) {
        String insertPath = "/home/deleviretta/Pulpit/bazy/insert.sql";
        Writer output;
        String line;
        try {
            //INSERT INTO KlanyW(IDKlanu, ZgromadzoneZasoby, IloscBonusowDoZarabiania, IloscBonusowDoDoswiadczenia)
            output = new BufferedWriter(new FileWriter(insertPath, true));
            KlanyW[] klanyT = new KlanyW[ileK];
            output.write(separator);
            for (int i = 0; i < ileK; i++) {
                klanyT[i] = new KlanyW();
                line = new String(insertDetail);
                line += "(" + klanyT[i].getIdKlanu() + "," + klanyT[i].getZgromadzoneZasoby() + "," +
                klanyT[i].getIloscBonusowDoZarabiania() + "," + klanyT[i].getIloscBonusowDoDoswiadczenia() +
                ");" + "\n";
                output.write(line);
            }
            output.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

}

