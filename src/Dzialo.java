/**
 * Created by deleviretta on 15.01.17.
 */
public class Dzialo {
    /*
    INSERT INTO Dzialo(NazwaDziala, Kaliber,
    ZadawaneObrazenia, CzasPrzeladowania) VALUES ('Dzialo', '122mm', 500, 10);
     */
    private String insert;
    public Dzialo(String value){
        insert = new String(value);
    }

    public String getInsert() {
        return insert;
    }
}
