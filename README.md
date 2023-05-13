# Bankovní aplikace - STIN
## Požadavky na aplikaci
- výsledkem je jednoduchý bankovní systém
- obsahuje přihlášení k účtu pomocí dvoufaktorové autentifikace
- každý uživatel obsahuje:
    - jméno, příjmení majitele účtu
    - unikátní číslo uživatele
- každý účet obsahuje:
    - uživatel, kterému účet náleží
    - zůstatek v libovolné z existujících peněžních měn
- každý uživatel musí mít zůstatek v CZK + libovolné množství dalších měn
    - každá měna má vlastní zůstatek
- při strhnutí peněz z účtu se nejprve systém pokusí strhnout peníze ze zůstatku v CZK - pokud zůstatek CZK neobsahuje dost peněz, strhne ho z jiné měny
- systém udržuje aktuální kurzovní lístek podle ČNB
    - stahuje si nový kurzovní lístek, pokud má neaktuální (každý den po 14:30) a ten si cachuje
## Popis UI
- výpis všech účtů uživatele
- výpis pohybů na konkrétním účtu + zůstatku
- tlačítko na simulaci placení s formulářem pro zadání částky pro tyto případy:
    - příchozí platba
        - peníze se přičtou na účet s korespondující měnou, případně převedou podle kurzovního lístku na CZK
    - odchozí platba
        - peníze se odečtou ze zadaného účtu, pokud jich má uživatel dostatek
- administrace účtu (nastavení měn, atd.)

## Technické požadavky
- backend
    - framework Laravel, verze 9
    - PHP verze 8.0
- frontend
    - JavaScript pro requesty na backend pomocí metody fetch
    - framework Alpine.js pro jednoduchou frontend logiku
- databáze
    - mysql, ukládání uživatelů + historie plateb
- celá aplikace zabalena v Dockeru
- automatické testy, code coverage + nasazení přes CI/CD

## Požadavky na uživatele
- jakýkoliv moderní webový prohlížeč s podporou JavaScriptu
