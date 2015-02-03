function LinkedList(){
	this.elementos = [];
	this.actual = 0;
};

LinkedList.prototype.add = function(elemento) {
	this.elementos.push(elemento);
};

LinkedList.prototype.remove = function(indice) {
	if(this.elementos.length > 0 && this.elementos.length < indice){
		this.elementos.splice(indice,1);

		//Si eliminaron la última
		if(this.elementos.length - 1 > actual)
			actual = this.elementos.length - 1;
	}
};

LinkedList.prototype.get = function(indice) {
	if(this.elementos.length < indice && indice >= 0){
		return this.elementos[indice];
	}
};

LinkedList.prototype.next = function() {
	if(this.elementos.length == 0)
		return null;

	//Cuando llega al final, si pide la siguiente, devuelve la primera
	if(this.elementos.length < actual)
		actual++;
	else
		actual = 0;

	return this.elementos[actual];
};

LinkedList.prototype.previous = function() {
	if(this.elementos.length == 0)
		return null;

	//Cuando llega al inicio, si piden la anterior, devuelve la úlima
	if(actual > 0)
		actual--;
	else
		actual = this.elementos.length - 1;

	return this.elementos[actual];
};