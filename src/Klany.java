import java.util.Random;

/**
 * Created by deleviretta on 15.01.17.
 */
public class Klany {
    private String nazwa;
    private Integer poziomTwierdzy;

    public Klany(String nazwa) {
        Random r = new Random();
        this.nazwa = nazwa;
        this.poziomTwierdzy = r.nextInt(10);
    }

    public String getNazwa() {
        return nazwa;
    }

    public Integer getPoziomTwierdzy() {
        return poziomTwierdzy;
    }
}
