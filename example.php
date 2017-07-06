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
	$i->setFields(["Invoice.id", "Invoice.total", "Invoice.paymentmethod", "InvoiceContent.name", "InvoiceContent.count", "ContractorDetail.nip", "ContractorDetail.name", "Invoice.paymentstate"]);
	$result = $i->execute();
	$invoices = json_decode($result, true);

	foreach($invoices['invoices'] as $in) {
		echo "Kontrahent: ".$in['invoice']['contractor_detail']['name']."<br />";
		echo "Kwota: ".$in['invoice']['total']."<br />";
	}