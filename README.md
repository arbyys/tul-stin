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
- při strhnutí peněz z účtu se nejprve systém pokusí strhnout peníze ze zůstatku v dané měně převodu 
  - pokud zůstatek v dané měně neobsahuje dost peněz, strhne ho z výchozí měny CZK
  - pokud ani měna CZK neobsahuje dostatečný zůstatek, platba je zamítnuta
- systém udržuje aktuální kurzovní lístek podle ČNB
    - systém si cachuje aktuálně stažený kurzovní lístek společně s datem, ze kterého pochází
    - pokud je zrovna pracovní den, čas kdykoliv po 14:30 a dnešní datum nesedí s datem uloženého kurzovního lístku, systém při novém requestu na jakýkoliv úkon vyžadující práci s penězi nejprve stáhne nový kurzovní lístek
    - kurzovní lístek je uložen v DB, kam se převede z defaultního CSV formátu z webu ČNB
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
    - mysql
    - ukládání uživatelů + historie plateb
    - ukládání ČNB kurzovního lístku
- automatické testy, code coverage + nasazení přes CI/CD

## Požadavky na uživatele
- jakýkoliv moderní webový prohlížeč s podporou JavaScriptu

## Rizika
- změna formátu kurzovního lístku ČNB
- selhání hostingu aplikace (VPS)
- selhání služeb google poskytující 2FA

## Reakce na chyby
- v případě vzniku chyby v aplikaci se tato chyba zaloguje do error logu
- uživateli se konkrétní chyba nevrací, pouze informace o stavovém kódu HTTP
- chyby lze zpětně v logu dohledat a poté příslušně vyřešit

## Odhad času
- analýza procesu: **1h**
- výběr knihoven + technologií, testování technologií: **2h**
- programování aplikace: **12h**
- psaní testů + CI/CD scriptu: **4h**
- finalní kroky, nasazení aplikace: **2h**
- **celkem: 21h**
