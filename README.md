# wFirma API
Klasa PHP do konstruowania prostych zapytań do API wfirma

Mniej lub bardziej dokładna **dokumentacja API wfirmy** (v2) jest dostępna na stronie - [doc.wfirma.pl]

## Metody
**Nowe zapytanie do wfirmy**
```php 
$wfirma = new wfirmaQuery('MODUŁ', 'AKCJA');
```
Moduły: `contractors`,  `invoices`, `expenses`, `goods`

Akcje: `add`, `edit`, `get`, `find`, `delete`

Pełna lista modułów i ich akcji jest na stronie [doc.wfirma.pl]

Uwaga, można kontruować żądania z elementem zmiennym w URL na przykład `$pdf = new wfirmaQuery('invoices', "download/{$fv}");` aby otrzymać pojedynczy element, jeśli dana akcja obsługuje taki sposób formułowania żądań.

**Ustawienie parametru**
```php
$wfirma->setParameter("PARAMETR", "wartosc");
```

**Dodawanie warunku**
```php
$wfirma->addCondition("POLE", "OPERATOR", "WARTOSC");
```
`POLE` to nazwa pola w zwracanej odpowiedzi np. `date` albo `total`

`OPERATOR` to jeden z operatarów podobnie jak w SQL - `q`, `ne`, `gt`, `lt`, `ge`, `le`, `like`, `not like`, `in`

`WARTOSC` to wartość, którą zgodnie z operatorem ma ten warunek spełnić
## Przykład wykorzystania
```php
<?php
    require "wfirma.class.php";
    /**
     * Przykład wykorzystania klasy
     * Zwraca 8 faktur vat wystawionych w danym dniu (dzisiaj)
     * Z pewnymi polami m.in. kwota, metoda płatności
     **/
    $i = new wfirmaQuery('invoices', 'find');
    $i->setParameter("limit", 8);
    $i->addCondition("date", "like", date("Y-m-d"));
    $i->addCondition("type", "eq", "normal");
    $i->setOrder("created", "desc");
    $i->setFields(["Invoice.total", "InvoiceContent.name", "ContractorDetail.name"]);
    $result = $i->execute();
    $invoices = json_decode($result, true);
    foreach($invoices['invoices'] as $in) {
      echo "Kontrahent: ".$in['invoice']['contractor_detail']['name']."<br />";
      echo "Kwota: ".$in['invoice']['total']."<br />";
    }
?>
```
## Przydatne linki
[Dokumentacja API wg. wfirma (doc.wfirma.pl)](doc.wfirma.pl)

## To-Do
- [ ] Dodać obsługę i rozróżnienie warunków OR i AND w metodzie `addCondition()`

[doc.wfirma.pl]: https://doc.wfirma.pl
