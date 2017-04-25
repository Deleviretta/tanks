import java.util.Random;

/**
 * Created by deleviretta on 15.01.17.
 */
public class UzytkownicyW {
    /*
    INSERT INTO UzytkownicyW(IDUzytkownika,EMail, Haslo, CzasKontaPremium, DostepneZloto,
    DostepneKredyty, DostepneDoswiadczenie) VALUES ('przed@o2.pl', '123', 10, 500, 199.8, 2000000);
     */
    private static Integer idUzytkownika = 600000;
    private String email;
    private String password;
    private Integer kontoPremium;
    private Integer zloto;
    private Integer kredyty;
    private Integer doswiadczenie;

    public UzytkownicyW(String nazwaU) {
        Random r = new Random();
        password = new String(nazwaU);
        nazwaU = nazwaU.replace("'","");
        email = "'" + nazwaU + "@o2.pl" + "'";
        kontoPremium = r.nextInt(100);
        zloto = r.nextInt(1000);
        kredyty = r.nextInt(1000000);
        doswiadczenie = r.nextInt(10000000);
        idUzytkownika++;
    }

    public static Integer getIdUzytkownika() {
        return idUzytkownika;
    }

    public String getEmail() {
        return email;
    }

    public String getPassword() {
        return password;
    }

    public Integer getKontoPremium() {
        return kontoPremium;
    }

    public Integer getZloto() {
        return zloto;
    }

    public Integer getKredyty() {
        return kredyty;
    }

    public Integer getDoswiadczenie() {
        return doswiadczenie;
    }
}
