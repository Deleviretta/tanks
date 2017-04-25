/**
 * Created by deleviretta on 15.01.17.
 */
public class Zalogant {
    /**
     * INSERT INTO Zalogant(IDCzolgu, Imie, Nazwisko, Rola) VALUES(null, 'Siergiej', 'Lawnuszko', 'Dowodca');
     */
    private String insert;

    public Zalogant(String value) {
        insert = new String(value);
    }

    public String getInsert() {
        return insert;
    }
}
