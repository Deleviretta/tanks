import java.util.Random;

/**
 * Created by deleviretta on 15.01.17.
 */
public class OsiagnieciaU {
    public static Integer getIdUzytkownika() {
        return idUzytkownika;
    }

    /*
        INSERT INTO OsiagnieciaU(IloscBitew, IloscObrazenZadanych, PrzyjeteObrazenia, ZniszczoneCzolgi, WyspotowaneCzolgi,
                                 DoswiadczenieZaBitwy, Zwyciestwa, Przegrane, Remisy)
        VALUES (20000, 3000000, 2500099, 80000, 1000000, 100000000, 10000, 10000, 0);
        */
    private static Integer idUzytkownika=600000;
    private Integer iloscBitew;
    private Integer iloscObrazenZadanych;
    private Integer przyjeteObrazenia;
    private Integer zniszczoneCzolgi;
    private Integer wyspotowaneCzolgi;
    private Integer doswiadczenieZaBitwy;
    private Integer zwyciestwa;
    private Integer przegrane;
    private Integer remisy;
    public OsiagnieciaU() {
        Random r = new Random();
        iloscBitew = r.nextInt(100000);
        iloscObrazenZadanych = r.nextInt(5000000);
        przyjeteObrazenia = r.nextInt(5000000);
        zniszczoneCzolgi = r.nextInt(2)*iloscBitew;
        wyspotowaneCzolgi = r.nextInt(3)*iloscBitew;
        doswiadczenieZaBitwy = r.nextInt(300000000);
        zwyciestwa = r.nextInt(iloscBitew);
        przegrane = iloscBitew - zwyciestwa;
        remisy = 0;
        idUzytkownika++;
    }

    public Integer getIloscBitew() {
        return iloscBitew;
    }

    public Integer getIloscObrazenZadanych() {
        return iloscObrazenZadanych;
    }

    public Integer getPrzyjeteObrazenia() {
        return przyjeteObrazenia;
    }

    public Integer getZniszczoneCzolgi() {
        return zniszczoneCzolgi;
    }

    public Integer getWyspotowaneCzolgi() {
        return wyspotowaneCzolgi;
    }

    public Integer getDoswiadczenieZaBitwy() {
        return doswiadczenieZaBitwy;
    }

    public Integer getZwyciestwa() {
        return zwyciestwa;
    }

    public Integer getPrzegrane() {
        return przegrane;
    }

    public Integer getRemisy() {
        return remisy;
    }
}
