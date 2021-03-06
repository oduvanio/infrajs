<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="{~date(:Y-m-d H:i,~true)}">
<shop>

	<name>{conf.yml.name}</name>
	<company>{conf.yml.company}</company>
	<url>http://{site}</url>
	<platform>Infrajs</platform>
	<agency>{conf.yml.agency}</agency>
	<email>{conf.admin.support}</email>
	<currencies>
		<currency id="RUR" rate="1"/>
	</currencies>
	<categories>{groups::category}
	</categories>
	<offers>
		{poss::pos}	
		
	</offers>
 </shop>
 </yml_catalog>
 {category:}
 		<category id="{id}" parentId="{parentId}">{title}</category>
{pos:}
 	<offer type="vendor.model" id="{id}" available="{Наличие на складе=:В наличии?:true?:false}">
		<url>http://{...site}?{~encode(:Каталог)}/{~encode(Производитель)}/{~encode(article)}</url>
		<price>{Цена}</price>
		<currencyId>RUB</currencyId>
		<categoryId>{categoryId}</categoryId >
		{images::image}
		<store>true</store>
		<pickup>true</pickup>
		<delivery>true</delivery>
		<vendor>{Производитель}</vendor>
		<model>{article}</model>
		<description>{Описание}</description>
		{more::param}
		
	</offer>
{param:}
	<param name="{~key}">{.}</param>
{image:}
	<picture>http://{site}{.}</picture>