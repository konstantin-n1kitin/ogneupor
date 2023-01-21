function isDate (str,mask) {
	cursor=0;
	if (str.length==mask.length) {
		for (i=0;i<=str.length;i++){
			if (mask.charAt(i)=='.' || mask.charAt(i)==' ' || mask.charAt(i)==':' || i==str.length) {
				//alert("i="+i+" cursor="+cursor+" char="+str.charAt(i)+" mask="+mask.charAt(i));
				if (str.substring(cursor,i)>mask.substring(cursor,i) || str.charAt(i)!=mask.charAt(i)) return false;
				if (str.substring(cursor,i).length==4 && str.substring(cursor,i)<1970) return false;
				cursor=i+1;
			}
			else {
				if (str.charCodeAt(i)<48 || str.charCodeAt(i)>57) return false;
			}
		}
	}
	else return false;
	return true;
	
}