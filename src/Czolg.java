/**
 * Created by deleviretta on 15.01.17.
 */
public class Czolg {
    /*
    INSERT INTO Czolg(IDSilnika, IDDziala, Tier, NazwaCzolgu, Pancerz, Szybkosc,
    Wytrzymalosc, ZasiegWidzenia, Waga, KosztDoswiadczenia, KosztKredyty)
    VALUES(200,300, 10, 'Bat-chat', 100, 50, 1500, 300, 15000, 200000, 5000000);
     */
    private String insert;
    public Czolg(String value) {
        insert = new String(value);
    }

    public String getInsert() {
        return insert;
    }
}
