/**
 * Created by deleviretta on 15.01.17.
 */
public class Uzytkownicy {
    private String nick;
    private Long idKlanu;


    public Uzytkownicy(String nick, Long idKlanu) {
        this.nick = nick;
        this.idKlanu = idKlanu;
    }

    public String getNick() {
        return nick;
    }

    public Long getIdKlanu() {
        return idKlanu;
    }
}
