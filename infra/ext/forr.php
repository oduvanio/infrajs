<?php
/*
infra_forr
infra_fora
infra_fori
infra_foro
infra_forx
infra_isAssoc
*/
function infra_isEqual(&$a, &$b){//являются ли две переменные ссылкой друг на друга иначе array()===array() а слои то разные
	if(is_object($a)){
		if(!is_object($b))return false;
		$a->____test____=true;
		if($b->____test____){
			unset($a->____test____);
			return true;
		}
		unset($a->____test____);
		return false;
	}
    $t = $a;//Делаем копию со ссылки
    if($r=($b===($a=1))){ $r = ($b===($a=0)); }//Приравниваем а 1 потом 0 и если b изменяется следом значит это одинаковые ссылки.
    $a = $t;//Возвращаем ссылке прежнее значение
    return $r;
}
function infra_isAssoc(&$array){//(c) Kohana http://habrahabr.ru/qa/7689/
	if(!is_array($array))return null;
	$keys = array_keys($array);
	return array_keys($keys) !== $keys;
}
function &infra_forr(&$el,$callback,$nar=array(),$back=false){//Бежим по массиву
	if(is_array($back)){//depricated
		$nar=$back;
		$back=false;
	}
	$r=null;
	if(!$el)return $r;

	$l=sizeof($el);
	if($back){
		for($i=$l-1;$i>=0;$i--){
			if(is_null($el[$i]))continue;
			$r=&infra_forcall($callback,$nar,$el[$i],$i,$el);
			if(!is_null($r))return $r;
		}
	}else{
		for($i=0;$i<$l;$i++){//В callback нельзя удалять... так как i сместится
			if(is_null($el[$i]))continue;

			$r=&infra_forcall($callback,$nar,$el[$i],$i, $el);
			if(!is_null($r))return $r;
		}
	}
	return $r;
}
function &infra_forcall($callback,$nar,&$val,$key=null, &$group=null,$i=null){
	$param=array_merge($nar,array(&$val,$key,&$group,$i));
	/*$param=array();
	$j=0;
	while(sizeof($nar)>$j){
		$param[]=&$nar[$j];
		$j++;
	}
	$param[]=&$val;
	$param[]=&$key;
	$param[]=&$group;
	$param[]=&$i;*/
	
	/*for($i=sizeof($param)-1,$l=10;$i<$l;$i++){
		$param[$i]=null;
	}*/
	
	$r=&$callback(
		$param[0],
		$param[1],
		$param[2],
		$param[3],
		@$param[4],
		@$param[5],
		@$param[6],
		@$param[7],
		@$param[8],
		@$param[9]);
	//$r=call_user_func_array($callback,$param);
	return $r;
}
function &infra_fora(&$el,$callback,$back=false,$nar=array(),&$_group=null,$_key=null){//Бежим по массиву рекурсивно
	if(is_array($back)){
		$nar=$back;
		$back=false;
	}
	if(infra_isAssoc($el)===false){
		$param=array(&$el,$callback,$back,$nar);
		return infra_forr($el,function&(&$el,$callback,$back,$nar, &$v,$i){

			return infra_fora($v,$callback,$back,$nar,$el,$i);
		},$back,$param);
	}else if(!is_null($el)){//Если undefined callback не вызывается, Таким образом можно безжать по переменной не проверя определена она или нет.
		//return $callback($el,$_key,$_group);
		return infra_forcall($callback,$nar,$el,$_key,$_group);
	}
	$r=null;
	return $r;
}
function &infra_fori(&$el,$callback,$nar=false,$back=false,$_key=null,&$_group=null){//Бежим по объекту рекурсивно
	if(infra_isAssoc($el)===true){
		$param=array(&$el,$callback,$nar,$back);
		$r=&infra_foro($el,function&(&$el,$callback,$nar,$back, &$v,$key){
			$r=&infra_fori($v,$callback,$nar,$back,$key,$el);
			if(!is_null($r))return $r;
		},$param,$back);
		if(!is_null($r))return $r;
	}else if(!is_null($el)){
		return infra_forcall($callback,$nar,$el,$_key,$_group);
		//r=this.exec(callback,'infra.fori',[obj,key,group],[back]);//callback,name,context,args,more
		//if(r!==undefined)return r;
	}
};
function &infra_foro(&$obj,$callback,$nar=array(),$back=false){//Бежим по объекту
	$r=null;
	if(infra_isAssoc($obj)!==true)return $r;//Только ассоциативные массивы

	$ar=array();
	foreach($obj as $key=>&$val){
		$ar[]=array('key'=>$key,'val'=>&$val);
	}
	return infra_forr($ar,function&($callback,$nar,&$obj, &$el){
		if(is_null($el['val']))return;
		return infra_forcall($callback,$nar,$el['val'],$el['key'],$obj);
	},array($callback,$nar,&$obj),$back);
};
function &infra_foru(&$el,$callback,$nar=array(),$back=false){//Бежим по массиву
	$r=null;
	if(!is_array($el))return $r;
	$ar=array();
	foreach($el as $key=>&$val){
		$ar[]=array('key'=>$key,'val'=>&$val);
	}
	return infra_forr($ar,function&($callback,$nar,&$el, &$v){
		if(is_null($v['val']))return;
		return infra_forcall($callback,$nar,$v['val'],$v['key'],$el);
	},array($callback,$nar,&$el),$back);
}
function &infra_forx(&$obj,$callback,$nar=array(),$back=false){//Бежим сначало по объекту а потом по его свойствам как по массивам
	return infra_foro($obj,function&(&$obj,$callback,$back,$nar, &$v,$key){
		return infra_fora($v,function&($callback,$nar,$key, &$el,$i,&$group){
			return infra_forcall($callback,$nar,$el,$key,$group,$i);
			//return infra_exec($callback,array_merge($nar,array(&$el,$key,&$group,$i)));//callback,name,context,args,more
		},array($callback,$nar,$key),$back);
	},array(&$obj,$callback,$back,$nar),$back);
};
/*
$ar=array(2,1,0,2,3);

$var='some';
$obj=array(1,2);

echo '<pre>';
$ar=array(1);
infra_forr($ar,function(&$obj){
	$obj[]=1;
},null,array(&$obj));

print_r($obj);
echo "<hr>";
/* */
?>