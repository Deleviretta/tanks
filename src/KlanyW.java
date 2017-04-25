import java.util.Random;

/**
 * Created by deleviretta on 15.01.17.
 */
public class KlanyW {
    /**
     * NSERT INTO KlanyW(IDKlanu, ZgromadzoneZasoby,
     * IloscBonusowDoZarabiania, IloscBonusowDoDoswiadczenia) VALUES(50000, 10000, 5, 20);
     */
    private static Integer idKlanu = 50000;
    private Integer zgromadzoneZasoby;
    private Integer iloscBonusowDoZarabiania;
    private Integer iloscBonusowDoDoswiadczenia;

    public KlanyW() {
        Random r = new Random();
        idKlanu++;
        zgromadzoneZasoby = r.nextInt(200000);
        iloscBonusowDoDoswiadczenia = r.nextInt(20);
        iloscBonusowDoZarabiania = r.nextInt(20);
    }

    public static Integer getIdKlanu() {
        return idKlanu;
    }

    public Integer getZgromadzoneZasoby() {
        return zgromadzoneZasoby;
    }

    public Integer getIloscBonusowDoZarabiania() {
        return iloscBonusowDoZarabiania;
    }

    public Integer getIloscBonusowDoDoswiadczenia() {
        return iloscBonusowDoDoswiadczenia;
    }
}
