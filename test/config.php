<?php
	//ip ????? ??? ???????? ??????? ldap(AD)
	$ldaphost = "10.10.10.10";
 	//??? "mydomain.ru"
	//???? ???????????
	$ldapport = "389";
	//?????? ???? ? ?????? ??????? ?????? ???????????? ???????,
	//??? ?? ?????? ??????????????.
	$memberof = "cn=??????,ou=??????,dc=mydomain,dc=ru";
	//?????? ???????? ??????
	$base = "ou=????????????,dc=mydomain,dc=ru";
	//?????????? ?????? ?????? ?? ???????? ????? ????????????????? ????????????
	$filter = "sAMAccountName=";
	//??? ?????, ??????????? ? ??????? ???????. ????????? ???? ????????
	//??? ??????????? ????? AD, ?? ??????? ? ????????? ???????? ?? ?????.
	$domain = "@mydomain.ru";
?>
