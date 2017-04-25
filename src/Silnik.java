/**
 * Created by deleviretta on 15.01.17.
 */
public class Silnik {
    /*
    INSERT INTO Silnik(Moc, SzansaNaZapalenie, NazwaSilnika) VALUES (1000, '20%', 'Diesel');
    */
    private String insert;

    public Silnik(String value) {
        insert = new String(value);
    }

    public String getInsert() {
        return insert;
    }
}
